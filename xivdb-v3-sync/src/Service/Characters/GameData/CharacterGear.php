<?php

namespace App\Service\Characters\GameData;

use XIVCommon\Redis\RedisCache;

class CharacterGear
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
        $this->gear();
    }
    
    private function gear()
    {
        foreach ($this->data as $set) {
            $set->class = $this->redis->get("xiv_ClassJob_{$set->classId}_min");
            $set->job   = $this->redis->get("xiv_ClassJob_{$set->jobId}_min");
            
            // attributes
            $attributes = [];
            foreach ($set->attributes as $id => $value) {
                $attributes[] = [
                    'param' => $this->redis->get("xiv_BaseParam_{$id}_min"),
                    'value' => $value
                ];
            }
            $set->attributes = $attributes;
            
            // gear
            foreach ($set->gear as $slot => $gear) {
                $gear->item = $this->redis->get("xiv_Item_{$gear->id}_min");
                
                // any mirage?
                if ($gear->mirage) {
                    $gear->mirage = $this->redis->get("xiv_Item_{$gear->mirage}_min");
                }
                
                // any dye?
                if ($gear->dye) {
                    $gear->dye = $this->redis->get("xiv_Item_{$gear->dye}_min");
                }
                
                // any materia?
                if ($gear->materia) {
                    foreach ($gear->materia as $i => $materia) {
                        $gear->materia[$i] = $this->redis->get("xiv_Item_{$materia}_min");
                    }
                }
            }
        }
    }
}
