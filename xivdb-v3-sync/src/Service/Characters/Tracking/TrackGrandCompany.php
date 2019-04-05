<?php

namespace App\Service\Characters\Tracking;

use App\Entity\GrandCompanyEvent;

/**
 * Grand company tracking
 */
trait TrackGrandCompany
{
    private $currentGrandCompanyId;

    /**
     * Records various data about the caracter
     */
    private function recordGrandCompanyChanges()
    {
        $this->io->text('---> '. __METHOD__);

        if (!$this->new->grandcompany) {
            return;
        }

        $this->appendOldGrandCompanyData();
        $this->recordGrandCompanyRankChange();
    }

    /**
     * Record a tracking event for a character
     */
    private function addGrandCompanyEvent($gc, $old, $new)
    {
        $this->io->text("-----| gc change: {$gc}: {$old} --> {$new}");
        $this->addTracking(
            new GrandCompanyEvent($gc, $old, $new)
        );
    }

    /**
     * Merge the old gc data onto the new gc data
     */
    private function appendOldGrandCompanyData()
    {
        $this->io->text('---> '. __METHOD__);

        reset($this->new->grandcompany);
        $this->currentGrandCompanyId = key($this->new->grandcompany);

        // append only non-current gc data
        foreach($this->old->grandcompany as $id => $rankId) {
            if (!isset($this->new->grandcompany->{$id})) {
                $this->new->grandcompany->{$id} = $rankId;
            }
        }
    }

    /**
     * Record any rank changes
     */
    private function recordGrandCompanyRankChange()
    {
        $this->io->text('---> '. __METHOD__);

        $old = $this->old->grandcompany->{$this->currentGrandCompanyId};
        $new = $this->new->grandcompany->{$this->currentGrandCompanyId};

        // check for diff, so simple
        if ($old != $new) {
            $this->addGrandCompanyEvent(
                $this->currentGrandCompanyId,
                $old,
                $new
            );
        }
    }
}
