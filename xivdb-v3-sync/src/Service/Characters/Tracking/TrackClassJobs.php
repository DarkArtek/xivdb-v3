<?php

namespace App\Service\Characters\Tracking;

use App\Entity\EventCategory;

/**
 * Grand company tracking
 */
trait TrackClassJobs
{
    private $expPerLevel = [];
    private $maxLevel;

    /**
     * Records various data about the character
     */
    private function recordClassJobEvents()
    {
        $this->io->text('---> '. __METHOD__);

        // set data values first
        $this->setDataValues();

        // loop through classjobs
        foreach($this->new->classjobs as $i => $newClassJob) {
            $oldClassJob = $this->old->classjobs[$i];

            // store the total EXP gained
            $newClassJob->expTotal = $newClassJob->level > 0
                ? $this->expPerLevel[$newClassJob->level] + $newClassJob->expLevel
                : 0;

            // save the new class with the total exp
            $this->new->classjobs[$i] = $newClassJob;

            // if the "expTotal" is not on the old data, continue.
            // it will be added when "new" is saved
            if (!isset($oldClassJob->expTotal)) {
                continue;
            }

            // if any of the new values are 0, skip
            if ($newClassJob->level == 0 || $newClassJob->expTotal == 0) {
                continue;
            }

            // skip if we're at the max level already (via old data)
            if ($oldClassJob->level == $this->maxLevel) {
                continue;
            }

            // ensure the new comparison data is not a "job unlock"
            if ($this->isRoleUnlock($oldClassJob, $newClassJob)) {
                continue;
            }

            // work out the gains
            $expGain = $newClassJob->expTotal - $oldClassJob->expTotal;
            $levelGain = $newClassJob->level - $oldClassJob->level;

            // if EXP gain is above the threshold, make an event
            // we record the new value and do all the math on XIVDB
            if ($expGain > 100) {
                $this->addClassJobEvent(
                    EventCategory::EXPERIENCE, $newClassJob->classId, $newClassJob->expTotal
                );
            }

            // if the LEVEL gain is above the threshold, make an event
            // we record the new value and do all the math on XIVDB
            if ($levelGain > 0) {
                $this->addClassJobEvent(
                    EventCategory::LEVEL, $newClassJob->classId, $newClassJob->level
                );
            }
        }
    }

    /**
     * Set the data values
     */
    private function setDataValues()
    {
        // get max level
        end($this->data['Exp']);
        $this->maxLevel = key($this->data['Exp']);

        // we can't gain EXP For the last level, so reset it
        $this->data['Exp'][$this->maxLevel] = 0;

        // build an array of total EXP gained per level (doesn't include the EXP of that level
        $total = 0;
        foreach($this->data['Exp'] as $level => $exp) {
            // you don't include the current levels EXP into the total
            $this->expPerLevel[$level] = $total;
            $total += $exp;
        }
    }

    /**
     * Record a tracking event for a character
     */
    private function addClassJobEvent($type, $jobclass, $newValue)
    {
        $this->io->text("'-----| [{$type}] {$jobclass}: {$newValue}");

        $this->events[] = [
            time(), $type, $jobclass, $newValue
        ];
    }

    /**
     * Checks if the old and new is not a role unlock, this
     * is assumed via:
     *
     *      new level - old level > 30 ?
     *      old level = 0
     *
     * If the levels gained are above 30 and the old level was 0
     * we will assume it is a job unlock and not record any events.
     */
    private function isRoleUnlock($old, $new)
    {
        $diff = $new->level - $old->level;

        if (!$old->level && $diff > 30) {
            return true;
        }

        return false;
    }
}
