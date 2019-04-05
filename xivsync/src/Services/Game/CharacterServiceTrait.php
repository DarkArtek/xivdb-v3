<?php

namespace App\Services\Game;

use App\Entity\Character;
use App\Entity\CharacterPending;
use App\Entity\SyncServer;
use Lodestone\Entities\Character\CharacterProfile;
use Lodestone\Validator\Exceptions\HttpNotFoundValidationException;

/**
 * Common functionality for character service
 */
trait CharacterServiceTrait
{
    /**
     * Check if a character has already been added to the system or not
     */
    protected function checkIfCharacterExists($entity)
    {
        $repo = $this->manager->getRepository(Character::class);
        if ($repo->findOneBy([ 'lodestoneId' => $entity->getLodestoneId() ])) {
            return true;
        }

        return false;
    }

    /**
     * Get a character from lodestone and handle all exceptions
     */
    protected function getCharacterFromLodestone($entity)
    {
        try {
            return $this->api->getCharacter($entity->getLodestoneId());
        } catch (\Exception $ex) {
            return get_class($ex);
        }
    }

    /**
     * Get a character friends from lodestone
     */
    protected function getCharacterFriendsFromLodestone($entity, $page)
    {
        try {
            return $this->api->getCharacterFriends($entity->getLodestoneId(), $page);
        } catch (\Exception $ex) {
            return get_class($ex);
        }
    }

    /**
     * Get a character followers from lodestone
     */
    protected function getCharacterFollowersFromLodestone($entity, $page)
    {
        try {
            return $this->api->getCharacterFollowing($entity->getLodestoneId(), $page);
        } catch (\Exception $ex) {
            return get_class($ex);
        }
    }

    /**
     * Get a characters achievement list from lodestone and handle all exceptions
     */
    protected function getCharacterAchievementsFromLodestone(Character $entity)
    {
        $categories = [1,2,4,5,6,8,11,12,13];

        // remove legacy category if the player doesn't have it
        if (!$entity->getAchievementsLegacy()) {
            array_pop($categories);
        }

        // grab all achievements
        $achievements = [];
        foreach($categories as $category) {
            try {
                $achievements[$category] = $this->api->getCharacterAchievements($entity->getLodestoneId(), $category)->toArray();
            } catch (HttpNotFoundValidationException $hnfvex) {
                // this can be valid for category 13
                if ($category !== 13) {
                    return HttpNotFoundValidationException::class;
                }
            } catch (\Exception $ex) {
                return get_class($ex);
            }
        }

        return $achievements;
    }

    /**
     * Add a new character (requires the parsed object)
     */
    protected function addCharacter(CharacterPending $pending, CharacterProfile $character, SyncServer $syncServer, SyncServer $syncServerAchievements)
    {
        // updating pending
        $pending->setDone(true);
        $this->manager->merge($pending);
        $this->manager->flush();

        // double check
        if ($this->checkIfCharacterExists($pending)) {
            return;
        }

        // save character
        $entity = new Character(
            $character->getId(),
            $character->getHash(),
            $syncServer->getName(),
            $syncServerAchievements->getName()
        );
        $this->manager->persist($entity);
        $this->manager->flush();

        // submit to rabbit
        $this->rabbit->sendMessage(json_encode([
            'type' => 'add',
            'data' => $character->toArray(),
            'sync' => $entity->toArray()
        ]));
    }

    /**
     * Update an existing character (requires the parsed object)
     */
    protected function updateCharacter(Character $entity, CharacterProfile $character)
    {
        // update timestamps, the "last changed" is updated
        // if the characters hash changes
        $entity->setLastUpdated(time());

        if (!$entity->getDeleted()) {
            $entity->setLastChanged(
                $character->getHash() == $entity->getSyncHash()
                    ? $entity->getLastChanged()
                    : time()
            )
            ->setSyncHash($character->getHash());
        }

        // save character
        $this->manager->merge($entity);
        $this->manager->flush();

        // submit to rabbit
        if (!$entity->getDeleted()) {
            $this->rabbit->sendMessage(json_encode([
                'type' => 'update',
                'data' => $character->toArray(),
                'sync' => $entity->toArray(),
            ]));
        }
    }

    /**
     * Update an existing characters achievements
     */
    protected function updateCharacterAchievements(Character $entity, $achievements)
    {
        $entity->setLastUpdatedAchievements(time());

        // save character
        $this->manager->merge($entity);
        $this->manager->flush();

        // submit to rabbit
        if (!$entity->getDeleted() && !$entity->getAchievementsPrivate()) {
            $this->rabbit->sendMessage(json_encode([
                'type' => 'achievements',
                'data' => [
                    'id' => $entity->getLodestoneId(),
                    'achievements' => $achievements
                ],
                'sync' => $entity->toArray(),
            ]));
        }
    }
}
