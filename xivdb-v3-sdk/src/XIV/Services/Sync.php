<?php

namespace XIV\Services;

use GuzzleHttp\RequestOptions;
use XIV\Guzzle\Guzzle;

class Sync
{
    const ENDPOINT = 'MS_SYNC';

    /**
     * Get information on a Character
     */
    public function character(int $lodestoneId, array $options = [])
    {
        Guzzle::GET(self::ENDPOINT, "/Character/{$lodestoneId}", [
            RequestOptions::QUERY => $options,
        ]);
        return Guzzle::Response();
    }

    /**
     * Get information on a Free Company
     */
    public function freecompany(string $lodestoneId, array $options = [])
    {
        $lodestoneId = $lodestoneId[0] == 'i' ? substr($lodestoneId, 1) : $lodestoneId;

        Guzzle::GET(self::ENDPOINT, "/FreeCompany/{$lodestoneId}", [
            RequestOptions::QUERY => $options,
        ]);
        return Guzzle::Response();
    }

    /**
     * Get information on a Linkshell
     */
    public function linkshell(string $lodestoneId, array $options = [])
    {
        $lodestoneId = $lodestoneId[0] == 'i' ? substr($lodestoneId, 1) : $lodestoneId;

        Guzzle::GET(self::ENDPOINT, "/Linkshell/{$lodestoneId}", [
            RequestOptions::QUERY => $options,
        ]);
        return Guzzle::Response();
    }
}
