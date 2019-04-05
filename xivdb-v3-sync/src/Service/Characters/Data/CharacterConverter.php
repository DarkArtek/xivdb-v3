<?php

namespace App\Service\Characters\Data;

use App\Utils\Converter;

/**
 * This converts character data sent by the Lodestone API and flattens it into
 * a very small amount of information to be stored on the server. This information
 * is then converted by Service/Characters/GameData/CharacterData in order to provide
 * a much more rich response.
 */
trait CharacterConverter
{
    /**
     * Converts the players data into ids so that
     * it can be assigned automatically and support
     * translations
     */
    protected function convertCharacterData()
    {
        // convert changer to something more simplified
        $this->new->gender = $this->new->gender == 'male' ? 1 : 2;

        // convert profile data
        $this->convertRace();
        $this->convertTribe();
        $this->convertTitle();
        $this->convertGuardian();
        $this->convertCity();
        $this->convertGrandCompany();
        $this->convertClassJobs();
        $this->convertGear();
        $this->convertCollectables();
        $this->convertActiveClass();
        
        return $this;
    }

    /**
     * Convert the players active class (just removes text)
     */
    private function convertActiveClass()
    {
        $this->io->text('---> '. __METHOD__);

        unset($this->new->activeClassJob->className);
        unset($this->new->activeClassJob->jobName);
    }

    /**
     * Convert gear (simplifies data and gets real game ids)
     * This will remove gear from "new" and assign
     * it to "gear", it will attempt to load any existing data
     *
     * It also takes the attributes off new data and puts them onto the gear
     */
    private function convertGear()
    {
        $this->io->text('---> '. __METHOD__);

        // create set
        $set = new \stdClass();
        $set->classId       = $this->new->activeClassJob->classId;
        $set->jobId         = $this->new->activeClassJob->jobId;
        $set->level         = $this->new->activeClassJob->level;
        $set->attributes    = $this->new->attributes;
        $set->gear          = new \stdClass();
        
        // convert attributes
        $new = [];
        foreach ($this->new->attributes as $i => $attribute) {
            // convert stupid shit because SE are inconsistent
            $attribute->name = ($attribute->name === 'Critical Hit Rate') ? 'Critical Hit' : $attribute->name;
            
            // convert
            $attribute->id = $this->getData('BaseParam', $attribute->name);
            unset($attribute->name);
            
            $new[$attribute->id] = $attribute->value;
        }
    
        $set->attributes = $new;
        
        // convert gear
        foreach ($this->new->gear as $gear) {
            $slot = $gear->slot;
            
            // todo - this must fail gracefully

            // item id
            $hash = Converter::hash($gear->name);
            $gear->id = $this->data['Equipment'][$hash];

            // check for dye
            if (isset($gear->dye) && $gear->dye) {
                $hash = Converter::hash($gear->dye->name);
                $gear->dye = $this->data['Dye'][$hash];
            }
    
            // check for mirage
            if (isset($gear->mirage) && $gear->mirage) {
                $hash = Converter::hash($gear->mirage->name);
                $gear->mirage = $this->data['Equipment'][$hash];
            }

            // check for materia
            if (isset($gear->materia) && $gear->materia) {
                foreach($gear->materia as $m => $materia) {
                    $this->io->text('-----| materia: '. $materia->name);
                    $hash = Converter::hash($materia->name);
                    $gear->materia[$m] = $this->data['Materia'][$hash];
                }
            } else {
                $gear->materia = [];
            }
    
            // remove junk
            unset($gear->slot);
            unset($gear->name);
            unset($gear->category);

            $set->gear->{$slot} = $gear;
        }

        // if no previous gearset, start a new one
        if (!$this->gear) {
            $this->gear = new \stdClass();
        }

        // add set to gear data
        $index = "{$set->classId}_{$set->jobId}";
        $this->gear->{$index} = $set;

        // remove stuff
        unset($this->new->gear);
        unset($this->new->attributes);
    }

    /**
     * Convert job/class (just removes text)
     */
    private function convertClassJobs()
    {
        $this->io->text('---> '. __METHOD__);

        foreach($this->new->classjobs as $classjob) {
            unset($classjob->className);
            unset($classjob->jobName);
        }
    }

    /**
     * Convert grand company name + rank
     */
    private function convertGrandCompany()
    {
        $this->io->text('---> '. __METHOD__);

        // character may not be in a grand company
        if (!$this->new->grandcompany || !$this->new->grandcompany->name) {
            return;
        }

        $town = [
            'Maelstrom' => 'GCRankLimsa',
            'Order of the Twin Adder' => 'GCRankGridania',
            'Immortal Flames' => 'GCRankUldah',
        ];

        $datasetTown = $town[$this->new->grandcompany->name];
        $nameHash = Converter::hash($this->new->grandcompany->name);
        $rankHash = Converter::hash($this->new->grandcompany->rank);

        $datasetGender = $this->new->gender == 1 ? 'Male' : 'Female';
        $rankDataSet = "{$datasetTown}{$datasetGender}Text";

        $id = $this->data['GrandCompany'][$nameHash];

        // assign gc
        $this->new->grandcompany = new \stdClass();
        $this->new->grandcompany->{$id} = $this->data[$rankDataSet][$rankHash];
    }

    /**
     * Convert city/town
     */
    private function convertCity()
    {
        $this->io->text('---> '. __METHOD__);

        $hash = Converter::hash($this->new->city->name);
        $this->new->city = $this->data['Town'][$hash];
    }

    /**
     * Convert guardian
     */
    private function convertGuardian()
    {
        $this->io->text('---> '. __METHOD__);

        $hash = Converter::hash($this->new->guardian->name);
        $this->new->guardian = $this->data['GuardianDeity'][$hash];
    }
    
    /**
     * Convert race
     */
    private function convertRace()
    {
        $this->io->text('---> '. __METHOD__);
    
        // small fix for some data that comes out as html entities
        $fix = [
            '&#39;' => "'",
        ];
    
        $this->new->race = str_ireplace(array_keys($fix), $fix, $this->new->race);
        
        $hash = Converter::hash($this->new->race);
        $this->new->race = $this->data['Race'][$hash];
    }
    
    /**
     * Convert tribe
     */
    private function convertTribe()
    {
        $this->io->text('---> '. __METHOD__);
    
        // small fix for some data that comes out as html entities
        $fix = [
            '&#39;' => "'",
        ];
    
        $this->new->clan = str_ireplace(array_keys($fix), $fix, $this->new->clan);
        
        $hash = Converter::hash($this->new->clan);
        $this->new->clan = $this->data['Tribe'][$hash];
    }
    
    /**
     * Convert
     */
    private function convertTitle()
    {
        $this->io->text('---> '. __METHOD__);
        
        $hash = Converter::hash($this->new->title);
        $this->new->title = $this->data['Title'][$hash];
    }

    /**
     * Converts collectibles into an Hash => ID format
     */
    private function convertCollectables()
    {
        $this->io->text('---> '. __METHOD__);

        // minions
        if ($this->new->collectables->minions) {
            $temp = [];
            foreach ($this->new->collectables->minions as $i => $minion) {
                $hash = Converter::Hash($minion->name);
                $temp[] = $this->data['Companion'][$hash];
            }
    
            $this->new->collectables->minions = $temp;
        }

        // mounts
        if ($this->new->collectables->mounts) {
            $temp = [];
            foreach ($this->new->collectables->mounts as $i => $mount) {
                $hash = Converter::Hash($mount->name);
                $temp[] = $this->data['Mount'][$hash];
            }
    
            $this->new->collectables->mounts = $temp;
        }
    }
}
