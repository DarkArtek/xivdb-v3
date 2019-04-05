<?php

namespace XIV\Services;

use XIV\Guzzle\Guzzle;
use GuzzleHttp\RequestOptions;

class Translation
{
    use CRUD;

    const ENDPOINT = 'TRANSLATIONS';

    /**
     * Search for a list of pages
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
}
