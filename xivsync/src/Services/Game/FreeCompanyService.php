<?php

namespace App\Services\Game;

use App\Services\Service;
use App\Services\Sync\SyncServerList;
use Lodestone\Entities\FreeCompany\{
    FreeCompanyMembers, FreeCompanySimple
};
use App\Entity\{
    Repository\FreeCompanyPendingRepository, FreeCompanyPending, FreeCompany, SyncServer
};
use Lodestone\Validator\Exceptions\HttpMaintenanceValidationException;
use Lodestone\Validator\Exceptions\HttpNotFoundValidationException;

/**
 * @package App\Services
 */
class FreeCompanyService extends Service
{
    use ServiceTrait;
    use FreeCompanyServiceTrait;

    const ENTITY_TYPE = 'freecompany';
    const MAX_ADD = 50;
    const MAX_UPDATE = 50;

    /**
     * Add free companies to the pending list
     */
    public function addPending(array $freeCompanies)
    {
        /** @var FreeCompanyPendingRepository $repo */
        $repo = $this->manager->getRepository(FreeCompanyPending::class);
        
        /** @var FreeCompanySimple $freeCompany */
        foreach($freeCompanies as $freeCompany) {
            // when a character is added, the FC is just a string at that point. So we support both
            $id = is_string($freeCompany) ? $freeCompany : $freeCompany->getId();
            
            // check exists
            if ($repo->findOneBy([ 'lodestoneId' => $id ])) {
                return;
            }
    
            // add character to pending list
            $this->manager->persist(
                new FreeCompanyPending($id)
            );
        }
    
        $this->manager->flush();
    }

    /**
     * Add pending free companies to the site
     */
    public function add()
    {
        // get pending free companies
        $result = $this->manager
            ->getRepository(FreeCompanyPending::class)
            ->findBy([ 'done' => false ], [ 'added' => 'asc' ], self::MAX_ADD);

        // if none
        if (!$result) {
            $this->io->text('No free companies to add');
            return;
        }
    
        // get a sync server
        /** @var SyncServer $syncServer */
        $syncServer = $this->syncServerList->getLowPopulatedServer(SyncServerList::FREECOMPANY);
        $this->rabbit->connect('freecompany_add');
        $this->io->text('Sync Server = '. $syncServer->getName());

        /** @var FreeCompanyPending $pending */
        foreach($result as $pending) {
            if ($this->checkActionTimeout()) {
                break;
            }

            // check the freecompany does not already exist
            if ($this->checkIfFreeCompanyExists($pending)) {
                continue;
            }

            /** @var \Lodestone\Entities\FreeCompany\FreeCompany $freecompany */
            $freecompany = $this->getFreeCompanyFromLodestone($pending);

            // if on maintenance, end
            if ($freecompany === HttpMaintenanceValidationException::class) {
                break;
            }

            // not found, validation or other exception remove the pending entry
            if ($freecompany === HttpNotFoundValidationException::class) {
                $this->manager->remove($pending);
                $this->manager->flush();
                $this->io->text("Deleted {$pending->getLodestoneId()} as not found or validation failed");
                continue;
            }

            // add character to the site
            $this->addFreeCompany($pending, $freecompany, $syncServer);

            // save data - Save each time incase a character messes up script
            $this->io->text("- Added: <comment>{$freecompany->getId()} {$freecompany->getName()}</comment>");
            $this->stats++;
    
            // increment server count
            $this->syncServerList->incrementServerCount($syncServer);
            $this->incrementStats('addFreeCompany');
        }

        $this->rabbit->close();
    }

    /**
     * Update free companies on the site
     */
    public function update($server)
    {

        // get freecompany to update
        $freecompany = $this->manager
            ->getRepository(FreeCompany::class)
            ->findBy(
                [ 'syncServer' => $server, 'deleted' => false ],
                [ 'lastUpdated' => 'asc'],
                self::MAX_UPDATE
            );

        // if none
        if (!$freecompany) {
            $this->io->text('No freecompany to update??');
            return;
        }
    
        $this->rabbit->connect('freecompany_update_'. $server);
        $this->io->text(sprintf('Updating %s freecompany', count($freecompany)));
    
        /** @var CharacterService $characterService */
        $characterService = $this->container->get(CharacterService::class);

        /** @var FreeCompany $entity */
        foreach($freecompany as $entity) {
            if ($this->checkActionTimeout()) {
                break;
            }

            /** @var \Lodestone\Entities\FreeCompany\FreeCompany $freecompany */
            $freecompany = $this->getFreeCompanyFromLodestone($entity);

            // if on maintenance, end
            if ($freecompany === HttpMaintenanceValidationException::class) {
                break;
            }

            // if deleted
            if ($freecompany === HttpNotFoundValidationException::class) {
                $entity->setDeleted(true);
            }

            // if free company hasn't deleted or invalidated, get members
            if (!$entity->getDeleted()) {
                // supports 1000 members (current max is 128)
                $members = [];
                foreach(range(1,20) as $page) {
                    /** @var FreeCompanyMembers $res */
                    $res = $this->getFreeCompanyMembersFromLodestone($entity, $page);
                    
                    if ($res->getCharacters()) {
                        $this->io->text("-- ({$freecompany->getId()} :: Members) Page: {$page}/{$res->getPageTotal()}");
    
                        // add characters to pending
                        $characterService->addPending($res->getCharacters());
                        $members = array_merge($members, $res->toArray()['characters']);
    
                        // at page limit?
                        if ($page == $res->getPageTotal()) {
                            break;
                        }
                    } else {
                        break;
                    }
                }

                $this->io->text(sprintf("-- Added %s characters to pending", count($members)));
            }

            $this->updateFreeCompany($entity, $freecompany, $members);

            // save data - Save each time incase a character messes up script
            $this->io->text("- Updated: <comment>{$entity->getLodestoneId()}</comment>");
            $this->incrementStats('updateFreeCompanies');
        }

        $this->rabbit->close();
    }
}
