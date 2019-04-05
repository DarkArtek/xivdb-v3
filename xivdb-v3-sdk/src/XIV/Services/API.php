<?php

namespace XIV\Services;

use XIV\Guzzle\Guzzle;
use GuzzleHttp\RequestOptions;

class API
{
    const ENDPOINT = 'MS_API';
    
    public function saveLodestoneData(array $data)
    {
        Guzzle::PUT(self::ENDPOINT, "/lodestone", [
            RequestOptions::JSON => $data,
            RequestOptions::QUERY => [
                'key' => ''
            ]
        ]);
    
        return Guzzle::Response();
    }
}
