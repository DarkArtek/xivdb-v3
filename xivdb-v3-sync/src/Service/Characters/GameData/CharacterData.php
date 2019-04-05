<?php

namespace App\Service\Characters\GameData;

use App\Service\Content\ContentMinified;
use XIVCommon\Redis\RedisCache;

class CharacterData
{
    /** @var RedisCache */
    private $redis;
    /** @var \stdClass */
    public $data;
    
    public function __construct($data)
    {
        $this->data = $data;
        $this->redis = new RedisCache();
        
        // Extend game data
        $this->profile();
        $this->profileGrandCompany();
        $this->profileClassJobs();
        $this->profileCollectables();
    }
    
    private function getData($key)
    {
        return ContentMinified::mini(
            $this->redis->get($key)
        );
    }
    
    /**
     * Extend basic profile info
     */
    private function profile()
    {
        $profile = [
            [ 'title',      'xiv_Title_%s' ],
            [ 'race',       'xiv_Race_%s' ],
            [ 'clan',       'xiv_Tribe_%s' ],
            [ 'guardian',   'xiv_GuardianDeity_%s' ],
            [ 'city',       'xiv_Town_%s' ],
        ];
        
        foreach ($profile as $pro) {
            [ $field, $key ] = $pro;
            
            $key = sprintf($key, $this->data->{$field});
            $this->data->{$field} = $this->getData($key);
        }
    }
    
    private function profileGrandCompany()
    {
        $gcGender = $this->data->gender == 2 ? 'Female' : 'Male';
        
        $gcRankKeyArray = [
            null,
            "xiv_GCRankLimsa{$gcGender}Text_%s",
            "xiv_GCRankGridania{$gcGender}Text_%s",
            "xiv_GCRankUldah{$gcGender}Text_%s"
        ];
        
        $gcRankIconKeyArray = [
            null,
            "IconMaelstrom",
            "IconSerpents",
            "IconFlames"
        ];
        
        $gcRankQuestKeyArray = [
            null,
            "QuestMaelstrom",
            "QuestSerpents",
            "QuestFlames"
        ];
        
        foreach ($this->data->grandcompany as $id => $rank) {
            $gc     = $this->getData(sprintf('xiv_GrandCompany_%s', $id));
            $rank   = $this->getData(sprintf($gcRankKeyArray[$id], $rank));
            $icon   = $this->getData(sprintf('xiv_GrandCompanyRank_%s', $rank->ID));
            
            // assign a general icon and quest for easier access
            $icon->Icon = $gcRankIconKeyArray[$id] ? $icon->{$gcRankIconKeyArray[$id]} : null;
            $icon->Quest = $gcRankQuestKeyArray[$id] ? $icon->{$gcRankQuestKeyArray[$id]} : null;
            
            $this->data->grandcompany->{$id} = [
                // Grand Company
                'gc'    => $gc,
                // Grand Company Rank
                'rank'  => $rank,
                // Grand Company Rank Icon
                'icon'  => $icon
            ];
        }
    }
    
    private function profileClassJobs()
    {
        foreach ($this->data->classjobs as $classjob) {
            $classjob->class = $this->getData("xiv_ClassJob_{$classjob->classId}");
            $classjob->job   = $this->getData("xiv_ClassJob_{$classjob->jobId}");
        }
        
        // Active class job
        $this->data->activeClassJob->class = $this->getData("xiv_ClassJob_{$this->data->activeClassJob->classId}");
        $this->data->activeClassJob->job   = $this->getData("xiv_ClassJob_{$this->data->activeClassJob->jobId}");
    }
    
    private function profileCollectables()
    {
        $minions = [];
        $mounts  = [];
        
        foreach ($this->data->collectables->minions as $minionId) {
            $minions[] = $this->getData("xiv_Companion_{$minionId}");
        }
    
        foreach ($this->data->collectables->mounts as $mountsId) {
            $mounts[] = $this->getData("xiv_Mount_{$mountsId}");
        }
    
        $this->data->collectables->minions = $minions;
        $this->data->collectables->mounts  = $mounts;
    }
}
