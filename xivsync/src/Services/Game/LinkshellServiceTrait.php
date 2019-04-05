<?php

namespace App\Services\Game;

use App\Entity\Linkshell;
use App\Entity\LinkshellPending;
use App\Entity\SyncServer;
use Lodestone\Validator\Exceptions\HttpMaintenanceValidationException;
use Lodestone\Validator\Exceptions\HttpNotFoundValidationException;
use Lodestone\Validator\Exceptions\ValidationException;

/**
 * Common functionality for linkshell service
 */
trait LinkshellServiceTrait
{
    /**
     * Check if a linkshell has already been added to the system or not
     */
    protected function checkIfLinkshellExists($entity)
    {
        $repo = $this->manager->getRepository(Linkshell::class);
        if ($repo->findOneBy([ 'lodestoneId' => $entity->getLodestoneId() ])) {
            return true;
        }

        return false;
    }

    /**
     * Get a linkshell from lodestone and handle all exceptions
     */
    protected function getLinkshellFromLodestone($entity, $page = false)
    {
        try {
            return $this->api->getLinkshellMembers($entity->getLodestoneId(), $page);
        } catch (\Exception $ex) {
            return get_class($ex);
        }
    }

    /**
     * Add a new linkshell (requires the parsed object)
     */
    protected function addLinkshell(
        LinkshellPending $pending,
        \Lodestone\Entities\Linkshell\Linkshell $linkshell,
        SyncServer $syncServer
    ) {
        // build entity
        $entity = new Linkshell(
            $linkshell->getId(),
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
                'profile' => [
                    'id' => $linkshell->getId(),
                    'name' => $linkshell->getName(),
                    'server' => $linkshell->getServer(),
                ],
                'members' => [],
            ],
            'sync' => $entity->toArray(),
        ]));
    }

    /**
     * Update an existing linkshell (requires the parsed object)
     */
    protected function updateLinkshell(Linkshell $entity, $linkshell, $members)
    {
        // update timestamps, the "last changed" is updated if the linkshells hash changes
        $entity->setLastUpdated(time());

        // save linkshell
        $this->manager->persist($entity);
        $this->manager->flush();

        // submit to rabbit
        if (!$entity->getDeleted()) {
            $this->rabbit->sendMessage(json_encode([
                'type' => 'update',
                'data' => [
                    'profile' => [
                        'id' => $linkshell->getId(),
                        'name' => $linkshell->getName(),
                        'server' => $linkshell->getServer(),
                    ],
                    'members' => $members,
                ],
                'sync' => $entity->toArray(),
            ]));
        }
    }
}
