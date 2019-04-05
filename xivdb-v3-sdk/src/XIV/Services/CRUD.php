<?php

namespace XIV\Services;

use XIV\Guzzle\Guzzle;
use GuzzleHttp\RequestOptions;

trait CRUD
{
    /**
     * Create something
     */
    public function create(array $data)
    {
        Guzzle::POST(self::ENDPOINT, '/', [
            RequestOptions::JSON => $data
        ]);

        return Guzzle::Response();
    }

    /**
     * Get something
     */
    public function get(string $id)
    {
        Guzzle::GET(self::ENDPOINT, "/{$id}");
        return Guzzle::Response();
    }

    /**
     * Update something
     */
    public function update(string $id, array $data)
    {
        Guzzle::PUT(self::ENDPOINT, "/{$id}", [
            RequestOptions::JSON => $data
        ]);
        return Guzzle::Response();
    }

    /**
     * Delete something
     */
    public function delete(string $id)
    {
        Guzzle::DELETE(self::ENDPOINT, "/{$id}");
        return Guzzle::Response();
    }
}
