<?php

namespace XIV\Services;

use XIV\Guzzle\Guzzle;
use GuzzleHttp\RequestOptions;

class Data
{
    const ENDPOINT = 'MS_DATA';
    
    /**
     * Get a list of content
     */
    public function list(string $filename, array $columns = [])
    {
        $options = !$columns ?: [
            RequestOptions::QUERY => [
                'columns' => implode(",", $columns)
            ]
        ];
        
        Guzzle::GET(self::ENDPOINT, "/{$filename}", $options);
        return Guzzle::Response();
    }
    
    /**
     * Get some game data content
     */
    public function get(string $filename, string $id)
    {
        Guzzle::GET(self::ENDPOINT, "/{$filename}/{$id}");
        return Guzzle::Response();
    }
    
    /**
     * Get some game data content
     */
    public function getPatchList()
    {
        Guzzle::GET(self::ENDPOINT, "/PatchList");
        return Guzzle::Response();
    }
    
    /**
     * Create a Data API Key
     */
    public function createKey(string $name, string $who, ?string $website = null)
    {
        Guzzle::POST(self::ENDPOINT, "/dev/api/key", [
            RequestOptions::JSON => [
                'name'      => $name,
                'who'       => $who,
                'website'   => $website
            ]
        ]);
        
        return Guzzle::Response();
    }
    
    /**
     * Update a Data API Key
     */
    public function updateKey(array $key)
    {
        Guzzle::POST(self::ENDPOINT, "/dev/api/update", [
            RequestOptions::JSON => $key
        ]);
        
        return Guzzle::Response();
    }
}
