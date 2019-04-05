<?php

namespace App\Service\Characters\Data;

use App\Utils\Converter;
use XIVCommon\Redis\RedisCache;

/**
 * Convert character data
 */
trait CharacterDataManager
{
    private $data = [];

    /**
     * Initialize game data to handle character data
     */
    protected function initGameData()
    {
        $this->io->text('Loading Character GameData into memory ...');
        
        $redis  = new RedisCache();
        $keys   = $redis->get('character_keys');
        
        foreach ($keys as $key) {
            $this->data[$key] = $redis->get("character_{$key}");
        }
        
        // convert to an array
        $this->data = json_decode(json_encode($this->data), true);
        
        $this->io->text('Complete');
        
        return $this;
    }
    
    public function getData($category, $name)
    {
        $hash = Converter::hash($name);
        
        if (!isset($this->data[$category][$hash])) {
            throw new \Exception("Could not find {$name} under category: {$category} as hash: {$hash}");
        }
        
        return $this->data[$category][$hash];
    }
}
