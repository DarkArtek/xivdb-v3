<?php

namespace XIV\Services;

use XIV\Guzzle\Guzzle;
use GuzzleHttp\RequestOptions;

class DevApps
{
    use CRUD;

    const ENDPOINT = 'MS_DEVAPPS';

    /**
     * Search for a list of dev apps
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
