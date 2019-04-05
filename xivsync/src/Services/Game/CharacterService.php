<?php

namespace App\Services\Game;

use App\Services\Service;
use App\Services\Tron\QueueManager;
use Lodestone\Entities\Character\{
    CharacterFriends, CharacterProfile, CharacterSimple
};
use App\Entity\{
    Repository\CharacterPendingRepository,
    CharacterPending,
    Character
};
use Lodestone\Validator\Exceptions\HttpMaintenanceValidationException;
use Lodestone\Validator\Exceptions\HttpNotFoundValidationException;
use Lodestone\Validator\Exceptions\ValidationException;
use XIVCommon\Logger\Logger;

/**
 * @package App\Services
 */
class CharacterService extends Service
{
    use ServiceTrait;
    use CharacterServiceTrait;

    const ENTITY_TYPE = 'character';

    /**
     * Add characters to the pending list
     */
    public function addPending(array $characters)
    {
        /** @var CharacterPendingRepository $repo */
        $repo = $this->manager->getRepository(CharacterPending::class);

        /** @var CharacterSimple $character */
        foreach($characters as $character) {
            // check exists
            if ($repo->findOneBy([ 'lodestoneId' => $character->getId() ])) {
                continue;
            }

            // add character to pending list
            $this->manager->persist(
                new CharacterPending($character->getId())
            );
        }

        $this->manager->flush();
    }
    
    /**
     * Add pending characters to the site
     */
    public function add()
    {
        Logger::info(__CLASS__, __METHOD__ .' Start');

        $characters = $this->redis->get(QueueManager::KEY_ADD);
        if (!$characters) {
            $this->io->text('No characters to add.');
            return;
        }
        
        $this->rabbit->connect(QueueManager::KEY_ADD);
        $this->io->text('Connected to RabbitMQ');
        
        [$characters, $syncServer, $syncServerAchievements] = $characters;
        $this->io->text(sprintf(
            "Adding %s characters ...",
            count($characters)
        ));

        /** @var FreeCompanyService $freeCompanyService */
        $freeCompanyService = $this->container->get(FreeCompanyService::class);

        /** @var CharacterPending $pending */
        Logger::info(__CLASS__, 'Character count: '. count($characters));
        foreach($characters as $i => $pending) {
            if ($this->checkActionTimeout()) {
                break;
            }
            
            // check the character does not already exist
            if ($this->checkIfCharacterExists($pending)) {
                $pending->setDone(true);
                $this->manager->merge($pending);
                $this->manager->flush();
                $this->io->text("- Already exists: {$pending->getLodestoneId()}");
                continue;
            }

            /** @var CharacterProfile $character */
            $character = $this->getCharacterFromLodestone($pending);

            // if the lodestone is on maintenance then stop and do nothing
            if ($character === HttpMaintenanceValidationException::class) {
                break;
            }

            // not found, validation or other exception remove the pending entry
            if ($character === HttpNotFoundValidationException::class) {
                $this->manager->remove($pending);
                $this->manager->flush();
                $this->io->text("Deleted {$pending->getLodestoneId()} as not found or validation failed");
                continue;
            }

            // add character to the site
            $this->addCharacter($pending, $character, $syncServer, $syncServerAchievements);

            // if the character is in an FC, add that
            if ($character->getFreecompany()) {
                $freeCompanyService->addPending([ $character->getFreecompany() ]);
            }

            // save data - Save each time incase a character messes up script
            $this->io->text("- Added: <comment>{$character->getId()} {$character->getName()}</comment>");
            $this->incrementStats('addCharacters');
    
            // increment server count
            $this->syncServerList->incrementServerCount($syncServer);
            $this->syncServerList->incrementServerCount($syncServerAchievements);
        }

        // close rabbit and record stats
        $this->rabbit->close();
        Logger::info(__CLASS__, __METHOD__ .' Finish');
    }

    /**
     * Check characters that are marked as "deleted"
     */
    public function delete()
    {
        Logger::info(__CLASS__, __METHOD__ .' Start');

        $this->rabbit->connect('characters_deleted_'. getenv('API_RABBIT_QUEUE'));

        // get characters to update
        $characters = $this->manager
            ->getRepository(Character::class)
            ->findDeleted(self::MAX_UPDATE);

        // if none
        if (!$characters) {
            $this->io->text('No characters to check');
            return;
        }

        /** @var Character $entity */
        foreach($characters as $entity) {
            if ($this->checkActionTimeout()) {
                break;
            }

            /** @var CharacterProfile $character */
            $character = $this->getCharacterFromLodestone($entity);

            // up the delete check count if still deleted
            if ($character === HttpNotFoundValidationException::class) {
                $entity->setDeleteChecks($entity->getDeleteChecks() + 1)->setLastUpdated(time());

            // un-delete if not deleted
            } else {
                $entity->setDeleteChecks(0)->setDeleted(false)->setLastUpdated(0);
            }

            $this->manager->persist($entity);
            $this->manager->flush();

            // if marked as deleted over the limit we set, send out a message which
            // will trigger an email + discord bot to confirm the character does not exist
            if ($entity->getDeleteChecks() >= self::MAX_DELETE_CHECKS) {
                $this->rabbit->sendMessage(json_encode([
                    'type' => 'deleted',
                    'sync' => $entity->toArray(),
                ]));
            }

            // save data - Save each time incase a character messes up script
            $this->io->text("- Delete checked: <comment>{$entity->getLodestoneId()}</comment>");
            $this->stats++;
        }

        $this->recordStats('characters_delete');
        $this->rabbit->close();

        Logger::info(__CLASS__, __METHOD__ .' Finish');
    }
    
    /**
     * Update characters on the site
     */
    public function update($server)
    {
        Logger::info(__CLASS__, __METHOD__ .' Start');

        $key = sprintf(QueueManager::KEY_UPDATE, $server);
        $characters = $this->redis->get($key);
        
        if (!$characters) {
            $this->io->text('No characters to add.');
            return;
        }
    
        $this->rabbit->connect($key);
        $this->io->text('Connected to RabbitMQ');
        $this->io->text(sprintf("Updating %s characters ...", count($characters)));

        /** @var FreeCompanyService $freeCompanyService */
        $freeCompanyService = $this->container->get(FreeCompanyService::class);
        
        /** @var Character $entity */
        foreach($characters as $entity) {
            if ($this->checkActionTimeout()) {
                break;
            }
            
            /** @var CharacterProfile $character */
            $character = $this->getCharacterFromLodestone($entity);

            // if on maintenance, end
            if ($character === HttpMaintenanceValidationException::class) {
                break;
            }

            // if deleted
            if ($character === HttpNotFoundValidationException::class) {
                $entity->setDeleted(true);
            }

            $this->updateCharacter($entity, $character);

            // if the character is in an FC, add that
            if ($character->getFreecompany()) {
                $freeCompanyService->addPending([ $character->getFreecompany() ]);
            }

            // save data - Save each time incase a character messes up script
            $this->io->text("- Updated: <comment>{$entity->getLodestoneId()}</comment>");
            $this->incrementStats('updateCharacters');
        }

        $this->rabbit->close();
        Logger::info(__CLASS__, __METHOD__ .' Finish');
    }

    /**
     * Update character achievements on the site
     */
    public function achievements($server)
    {
        Logger::info(__CLASS__, __METHOD__ .' Start');

        $key = sprintf(QueueManager::KEY_ACHIEVEMENTS, $server);
        $characters = $this->redis->get($key);
    
        if (!$characters) {
            $this->io->text('No characters to add.');
            return;
        }
    
        $this->rabbit->connect($key);
        $this->io->text('Connected to RabbitMQ');
        $this->io->text(sprintf("Updating %s characters ...", count($characters)));

        /** @var Character $entity */
        foreach($characters as $entity) {
            if ($this->checkActionTimeout()) {
                break;
            }

            /** @var Character $character */
            $achievements = $this->getCharacterAchievementsFromLodestone($entity);
    
            // ignore if validation failed
            if ($achievements === ValidationException::class) {
                $this->io->text("- Validation errors for: <comment>{$entity->getLodestoneId()}</comment>");
                continue;
            }

            // if on maintenance, end
            if ($achievements === HttpMaintenanceValidationException::class) {
                $this->io->text('- Lodestone on Maintenance?');
                break;
            }

            // if private
            if ($achievements === HttpNotFoundValidationException::class) {
                $this->io->text('- Achievements private');
                $entity->setAchievementsPrivate(true);
            }

            // if no 13th category, set legacy
            if (!isset($achievements[13])) {
                $entity->setAchievementsLegacy(false);
            }

            // update achievements
            $this->updateCharacterAchievements($entity, $achievements);

            // save data - Save each time incase a character messes up script
            $this->io->text("- Updated Achievements: <comment>{$entity->getLodestoneId()}</comment>");
            $this->incrementStats('updateAchievements');
        }

        $this->rabbit->close();
        Logger::info(__CLASS__, __METHOD__ .' Finish');
    }

    /**
     * Update characters friends on the site
     */
    public function friends($server)
    {
        Logger::info(__CLASS__, __METHOD__ .' Start');

        $key = sprintf(QueueManager::KEY_FRIENDS, $server);
        $characters = $this->redis->get($key);
    
        if (!$characters) {
            $this->io->text('No characters to add.');
            return;
        }
    
        $this->rabbit->connect($key);
        $this->io->text('Connected to RabbitMQ');
        $this->io->text(sprintf("Updating %s characters ...", count($characters)));

        /** @var Character $entity */
        foreach($characters as $entity) {
            if ($this->checkActionTimeout()) {
                break;
            }

            $this->io->text("Checking for friends: <comment>{$entity->getLodestoneId()}</comment>");
            $friends = [];

            // add in-game friends
            foreach(range(1,10) as $page) {
                /** @var CharacterFriends $res */
                $res = $this->getCharacterFriendsFromLodestone($entity, $page);
                
                if (is_string($res)) {
                    break;
                }

                // add characters to pending
                if ($res && $res->getTotal()) {
                    $this->io->text("- Page: {$page}/{$res->getPageTotal()}");
                    
                    if (getenv('ADD_CHARACTERS')) {
                        $this->addPending($res->getCharacters());
                    }
                    
                    $friends = array_merge($friends, $res->toArray()['characters']);

                    // at page limit?
                    $this->stats++;
                    if ($page == $res->getPageTotal()) {
                        break;
                    }
                } else {
                    break;
                }
            }

            // add lodestone friends (aka followers)
            foreach(range(1,10) as $page) {
                $res = $this->getCharacterFollowersFromLodestone($entity, $page);
    
                if (is_string($res)) {
                    break;
                }

                // add characters to pending
                if ($res && $res->getTotal()) {
                    $this->io->text("- Page: {$page}/{$res->getPageTotal()}");
                    
                    if (getenv('ADD_CHARACTERS')) {
                        $this->addPending($res->getCharacters());
                    }
                    
                    $friends = array_merge($friends, $res->toArray()['characters']);

                    // at page limit?
                    $this->stats++;
                    if ($page == $res->getPageTotal()) {
                        break;
                    }
                } else {
                    break;
                }
            }
            
            // record friends updated timestamp
            $entity->setLastUpdatedFriends(time());
            $this->manager->merge($entity);
    
            // skip if none
            if (!$friends) {
                continue;
            }
            
            $this->rabbit->sendMessage(json_encode([
                'type' => 'friends',
                'data' => [
                    'id' => $entity->getLodestoneId(),
                    'friends' => $friends,
                ],
                'sync' => $entity->toArray(),
            ]));
        }
        
        $this->manager->flush();
        $this->rabbit->close();
        Logger::info(__CLASS__, __METHOD__ .' Finish');
    }
}
