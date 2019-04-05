<?php

namespace App\Service\Characters\Tracking;

/**
 * Record character events (aka "Timeline")
 * This includes:
 * - EXP Events
 * - Level Events
 * - Profile Changes
 * - New Minions or Mounts
 * - Joining or Leaving a Free Company
 * - Joining or Leaving a Linkshell
 */
trait CharacterTracking
{
    /**
     * record events
     */
    protected $recorded = [];

    /**
     * Add to the contents tracking data
     */
    protected function addTracking($data)
    {
        $this->recorded[] = (string)$data;
        return $this;
    }

    /**
     * Save a contents log data
     */
    protected function saveTracking()
    {
        if (!$this->recorded) {
            return $this;
        }

        $data = [
            [
                'time' => (new \DateTime())->format(\DateTime::ISO8601),
                'data' => $this->recorded,
            ]
        ];

        // grab existing tracking
        $existing = $this->storage->load("{$this->new->id}_tracking", []);
        $existing = array_merge($existing, $data);

        // save
        $this->storage->save("{$this->new->id}_tracking", $existing);
        return $this;
    }
}
