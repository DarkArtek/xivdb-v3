<?php

namespace XIV\Services;

use XIV\Guzzle\Guzzle;
use GuzzleHttp\RequestOptions;

class Mognet
{
    const ENDPOINT = 'MS_MOGNET';

    /**
     * Send something to MogNet
     */
    public function send(string $route, array $data): bool
    {
        Guzzle::POST(self::ENDPOINT, $route, [
            RequestOptions::JSON => $data
        ]);

        return Guzzle::Response();
    }
}
