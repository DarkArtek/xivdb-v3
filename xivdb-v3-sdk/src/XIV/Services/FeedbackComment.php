<?php

namespace XIV\Services;

use XIV\Guzzle\Guzzle;
use GuzzleHttp\RequestOptions;

class FeedbackComment
{
    use CRUD;

    const ENDPOINT = 'MS_FEEDBACK';

    /**
     * Create feedback comment
     */
    public function create(string $id, array $data)
    {
        Guzzle::POST(self::ENDPOINT, "/{$id}/comment", [
            RequestOptions::JSON => $data
        ]);

        return Guzzle::Response();
    }

    /**
     * Update a feedback comment
     */
    public function update(string $id, string $commentId, array $data)
    {
        Guzzle::POST(self::ENDPOINT, "/{$id}/comment/{$commentId}", [
            RequestOptions::JSON => $data
        ]);

        return Guzzle::Response();
    }

    /**
     * Delete a feedback comment
     */
    public function delete(string $id, string $commentId, ?bool $fully = false)
    {
        Guzzle::DELETE(self::ENDPOINT, "/{$id}/comment/{$commentId}", [
            RequestOptions::QUERY => [
                'fully' => $fully
            ]
        ]);

        return Guzzle::Response();
    }
}
