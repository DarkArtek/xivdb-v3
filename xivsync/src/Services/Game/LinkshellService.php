<?php

namespace App\Services\Game;

use App\Services\Service;
use App\Services\Sync\SyncServerList;
use Lodestone\Entities\Linkshell\{
    LinkshellSimple
};
use App\Entity\{
    Repository\LinkshellPendingRepository, LinkshellPending, Linkshell, SyncServer
};
use Lodestone\Validator\Exceptions\HttpMaintenanceValidationException;
use Lodestone\Validator\Exceptions\HttpNotFoundValidationException;

/**
 * @package App\Services
 */
class LinkshellService extends Service
{
    use ServiceTrait;
    use LinkshellServiceTrait;

    const ENTITY_TYPE = 'linkshell';
    const MAX_ADD = 50;
    const MAX_UPDATE = 50;

    /**
     * Add linkshells to the pending list
     */
    public function addPending(array $linkshells)
    {
        /** @var LinkshellPendingRepository $repo */
        $repo = $this->manager->getRepository(LinkshellPending::class);

        /** @var LinkshellSimple $linkshell */
        foreach($linkshells as $linkshell) {
            // check exists
            if ($repo->findOneBy([ 'lodestoneId' => $linkshell->getId() ])) {
                continue;
            }

            // add character to pending list
            $this->manager->persist(
                new LinkshellPending($linkshell->getId())
            );
        }

        $this->manager->flush();
    }

    /**
     * Add pending linkshells to the site
     */
    public function add()
    {
        $this->rabbit->connect('linkshells_add_'. getenv('API_RABBIT_QUEUE'));
        
        // get pending free companies
        $result = $this->manager
            ->getRepository(LinkshellPending::class)
            ->findBy([ 'done' => false ], [ 'added' => 'asc'], self::MAX_ADD);

        // if none
        if (!$result) {
            $this->io->text('No linkshells to add!');
            return;
        }
    
        // get a sync server
        /** @var SyncServer $syncServer */
        $syncServer = $this->syncServerList->getLowPopulatedServer(SyncServerList::LINKSHELL);
        $this->io->text('Sync Server = '. $syncServer->getName());

        /** @var LinkshellPending $pending */
        foreach($result as $pending) {
            if ($this->checkActionTimeout()) {
                break;
            }

            // check the linkshell does not already exist
            if ($this->checkIfLinkshellExists($pending)) {
                continue;
            }

            /** @var \Lodestone\Entities\Linkshell\Linkshell $linkshell */
            $linkshell = $this->getLinkshellFromLodestone($pending);

            // if on maintenance, end
            if ($linkshell === HttpMaintenanceValidationException::class) {
                break;
            }

            // not found, validation or other exception remove the pending entry
            if ($linkshell === HttpNotFoundValidationException::class) {
                $this->manager->remove($pending);
                $this->manager->flush();
                $this->io->text("Deleted {$pending->getLodestoneId()} as not found or validation failed");
                continue;
            }

            // add character to the site
            $this->addLinkshell($pending, $linkshell, $syncServer);

            // save data - Save each time incase a character messes up script
            $this->io->text("- Added: <comment>{$linkshell->getId()} {$linkshell->getName()}</comment>");
            $this->stats++;
    
            // increment server count
            $this->syncServerList->incrementServerCount($syncServer);
            $this->incrementStats('addLinkshell');
        }

        $this->rabbit->close();
    }

    /**
     * Update linkshells on the site
     */
    public function update($server)
    {
        
        // get linkshell to update
        $linkshell = $this->manager
            ->getRepository(Linkshell::class)
            ->findBy(
                [ 'syncServer' => $server, 'deleted' => false ],
                [ 'lastUpdated' => 'asc' ],
                self::MAX_UPDATE
            );

        // if none
        if (!$linkshell) {
            $this->io->text('No linkshell to update??');
            return;
        }
    
        $this->rabbit->connect('linkshells_update_'. $server);
        $this->io->text(sprintf('Updating %s linkshell', count($linkshell)));
    
        /** @var CharacterService $characterService */
        $characterService = $this->container->get(CharacterService::class);
        
        /** @var Linkshell $entity */
        foreach($linkshell as $entity) {
            if ($this->checkActionTimeout()) {
                break;
            }

            /** @var \Lodestone\Entities\Linkshell\Linkshell $linkshell */
            $linkshell = $this->getLinkshellFromLodestone($entity);

            // if on maintenance, end
            if ($linkshell === HttpMaintenanceValidationException::class) {
                break;
            }

            // if deleted
            if ($linkshell === HttpNotFoundValidationException::class) {
                $entity->setDeleted(true);
            }

            // add initial characters
            if (!$entity->getDeleted()) {
                $characterService->addPending($linkshell->getCharacters());
                $members = $linkshell->toArray()['characters'];
            }

            // if linkshell hasn't been deleted or invalidated, get all members
            // (don't get anymore if not more than 1 page
            if (!$entity->getDeleted() && $linkshell->getPageTotal() > 1) {

                // supports 1000 members (start at page 2 since initial parse includes page 1
                foreach(range(2,20) as $page) {
                    /** @var \Lodestone\Entities\Linkshell\Linkshell $res */
                    $res = $this->getLinkshellFromLodestone($entity, $page);
                    $this->io->text("-- ({$res->getId()} :: Members) Page: {$page}/{$res->getPageTotal()}");

                    // add characters to pending
                    $characterService->addPending($res->getCharacters());
                    $members = array_merge($members, $res->toArray()['characters']);

                    // at page limit?
                    if ($page == $res->getPageTotal()) {
                        break;
                    }
                }
            }

            $this->updateLinkshell($entity, $linkshell, $members);

            // save data - Save each time incase a character messes up script
            $this->io->text("- Updated: <comment>{$entity->getLodestoneId()}</comment>");
            $this->incrementStats('updateLinkshell');
        }

        $this->rabbit->close();
    }
}
