<?php

namespace App\Service\Characters\Tracking;

use App\Entity\CollectableEvent;

/**
 * Tracking minions and mounts
 */
trait TrackCollectables
{
    /**
     * Record a tracking event for a collectable
     */
    private function addCollectableEvent($type, $id)
    {
        $this->io->text("-----| new collectable: {$type} {$id}");
        $this->addTracking(
            new CollectableEvent($type, $id)
        );
    }

    /**
     * Track minions
     */
    protected function recordMinionChanges()
    {
        $this->io->text('---> '. __METHOD__);
        if (!$this->new->collectables->minions) {
            return;
        }

        foreach($this->new->collectables->minions as $id) {
            // check for new ones
            if (!in_array($id, $this->old->collectables->minions)) {
                $this->addCollectableEvent('minion', $id);
            }
        }
    }

    /**
     * Track mounts
     */
    protected function recordMountChanges()
    {
        $this->io->text('---> '. __METHOD__);
        if (!$this->new->collectables->mounts) {
            return;
        }

        foreach($this->new->collectables->mounts as $id) {
            // check for new ones
            if (!in_array($id, $this->old->collectables->mounts)) {
                $this->addCollectableEvent('mount', $id);
            }
        }
    }
}
