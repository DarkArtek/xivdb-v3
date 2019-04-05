<?php

namespace App\Service\Characters\Tracking;

use App\Entity\ProfileEvent;

/**
 * Event tracking such as name, server, title, etc
 */
trait TrackProfile
{
    /**
     * Records various data about the character
     */
    private function recordProfileChanges()
    {
        $this->io->text('---> '. __METHOD__);

        $firstTier = [
            'name','server','title','race','clan','gender',
            'nameday','guardian','free_company','city'
        ];

        $secondTier = [
            [ 'grandcompany', 'name' ],
            [ 'grandcompany', 'rank' ],
        ];

        // first tier stuff
        foreach($firstTier as $field) {
            $this->trackFirstTier($field);
        }

        // second tier stuff
        foreach($secondTier as $field) {
            [$type, $field] = $field;
            $this->trackSecondTier($type, $field);
        }
    }

    /**
     * Record a tracking event for a character
     */
    private function addProfileEvent($field, $old, $new)
    {
        $this->io->text("-----| new profile change: ({$field}) {$old} --> {$new}");
        $this->addTracking(
            new ProfileEvent($field, $old, $new)
        );
    }

    /**
     * Track first tier data
     */
    private function trackFirstTier($field)
    {
        $this->io->text('---> '. __METHOD__, [ $field ]);

        $old = isset($this->old->{$field}) ? $this->old->{$field} : null;
		$new = isset($this->new->{$field}) ? $this->new->{$field} : null;

		if (!$old || !$new) {
			return;
		}

		if ($old != $new) {
			$this->addProfileEvent($field, $old, $new);
		}
    }

    /**
     * Track second tier data
     */
    private function trackSecondTier($type, $field)
    {
        $this->io->text('---> '. __METHOD__, [ $type, $field ]);

        $old = isset($this->old->{$type}->{$field}) ? $this->old->{$type}->{$field} : null;
		$new = isset($this->new->{$type}->{$field}) ? $this->new->{$type}->{$field} : null;

		if (!$old || !$new) {
			return;
		}

		if ($old != $new) {
			$this->addProfileEvent(sprintf('%s_%s', $type, $field), $old, $new);
		}
    }
}
