<?php

namespace XIV\Services;

use XIV\Guzzle\Guzzle;
use GuzzleHttp\RequestOptions;

class Comments
{
    use CRUD;

    const ENDPOINT = 'MS_COMMENTS';

    /**
     * Search for a list of comments
     */
    public function search(string $idUnique, ?string $order = null, ?int $limit = null, ?int $page = null)
    {
        $query = [
            'idUnique' => $idUnique,
        ];
        
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
