<?php

namespace XIV\Services;

use XIV\Guzzle\Guzzle;
use GuzzleHttp\RequestOptions;

class Feedback
{
    use CRUD;

    const ENDPOINT = 'MS_FEEDBACK';

    /**
     * Search for a list of feedback messages
     */
    public function search(?array $filters = [], ?string $order = null, ?int $limit = null, ?int $page = null)
    {
        $query = $filters;
        
        if ($order) {
            $query['order'] = $order;
        }
        
        if ($limit) {
            $query['limit'] = $limit;
        }
        
        if ($page) {
            $query['page'] = $page;
        }

        Guzzle::GET(self::ENDPOINT, '/search', [
            RequestOptions::QUERY => $query
        ]);

        return Guzzle::Response();
    }

    /**
     * Permanently delete some feedback
     */
    public function deletePermanent(string $id)
    {
        Guzzle::DELETE(self::ENDPOINT, "/{$id}", [
            RequestOptions::QUERY => [
                'fully' => true
            ]
        ]);

        return Guzzle::Response();
    }
}
