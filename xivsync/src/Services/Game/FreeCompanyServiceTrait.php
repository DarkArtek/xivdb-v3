<?php

namespace App\Services\Game;

use App\Entity\FreeCompany;
use App\Entity\FreeCompanyPending;
use App\Entity\SyncServer;
use Lodestone\Validator\Exceptions\HttpMaintenanceValidationException;
use Lodestone\Validator\Exceptions\HttpNotFoundValidationException;
use Lodestone\Validator\Exceptions\ValidationException;

/**
 * Common functionality for freecompany service
 */
trait FreeCompanyServiceTrait
{
    /**
     * Check if a freecompany has already been added to the system or not
     */
    protected function checkIfFreeCompanyExists($entity)
    {
        $repo = $this->manager->getRepository(FreeCompany::class);
        if ($repo->findOneBy([ 'lodestoneId' => $entity->getLodestoneId() ])) {
            return true;
        }

        return false;
    }

    /**
     * Get a freecompany from lodestone and handle all exceptions
     */
    protected function getFreeCompanyFromLodestone($entity)
    {
        try {
            return $this->api->getFreeCompany($entity->getLodestoneId());
        } catch (\Exception $ex) {
            return get_class($ex);
        }
    }

    /**
     * Get a freecompany from lodestone and handle all exceptions
     */
    protected function getFreeCompanyMembersFromLodestone($entity, $page = false)
    {
        try {
            return $this->api->getFreeCompanyMembers($entity->getLodestoneId(), $page);
        } catch (\Exception $ex) {
            return get_class($ex);
        }
    }

    /**
     * Add a new freecompany (requires the parsed object)
     */
    protected function addFreeCompany(
        FreeCompanyPending $pending,
        \Lodestone\Entities\FreeCompany\FreeCompany $freecompany,
        SyncServer $syncServer
    ) {
        // build entity
        $entity = new FreeCompany(
            $freecompany->getId(),
            $syncServer->getName()
        );
        $this->manager->persist($entity);

        // updating pending
        $pending->setDone(true);
        $this->manager->persist($pending);
        $this->manager->flush();

        // submit to rabbit
        $this->rabbit->sendMessage(json_encode([
            'type' => 'add',
            'data' => [
                'profile' => $freecompany->toArray(),
                'members' => [],
            ],
            'sync' => $entity->toArray(),
        ]));
    }

    /**
     * Update an existing freecompany (requires the parsed object)
     */
    protected function updateFreeCompany(FreeCompany $entity, $freecompany, $members)
    {
        // update timestamps, the "last changed" is updated if the free companies hash changes
        $entity->setLastUpdated(time());

        // save freecompany
        $this->manager->persist($entity);
        $this->manager->flush();

        // submit to rabbit
        if (!$entity->getDeleted()) {
            $this->rabbit->sendMessage(json_encode([
                'type' => 'update',
                'data' => [
                    'profile' => $freecompany->toArray(),
                    'members' => $members,
                ],
                'sync' => $entity->toArray(),
            ]));
        }
    }
}
