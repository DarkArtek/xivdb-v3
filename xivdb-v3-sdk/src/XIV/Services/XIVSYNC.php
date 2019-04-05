<?php

namespace XIV\Services;

use GuzzleHttp\RequestOptions;
use XIV\Guzzle\Guzzle;

class XIVSYNC
{
    const ENDPOINT = 'MS_XIVSYNC';
    
    /**
     * Very generic XIVSYNC queries
     */
    public function get(string $endpoint, array $options = [])
    {
        Guzzle::GET(self::ENDPOINT, $endpoint, [
            RequestOptions::QUERY => $options
        ]);
        
        return Guzzle::Response();
    }
}
