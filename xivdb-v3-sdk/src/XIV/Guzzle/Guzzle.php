<?php

namespace XIV\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;

class Guzzle
{
    const TIMEOUT = 15.0;
    const VERIFY = false;

    /** @var Response */
    private static $response = null;

    public static function Query($method, $baseUri, $apiEndpoint, $options = [])
    {
        $client = new Client([
            'base_uri'  => getenv($baseUri),
            'timeout'   => self::TIMEOUT,
            'verify'    => self::VERIFY,
        ]);

        try {
            $options = array_merge($options, [
                RequestOptions::HEADERS => [
                    'Authorization' => "Bearer ". getenv("{$baseUri}_KEY")
                ]
            ]);

            $response = $client->request($method, $apiEndpoint, $options);
        } catch (ClientException $ex) {
            $response = $ex->getResponse();
        }

        self::$response = $response;
    }

    public static function Response()
    {
        return json_decode(self::$response->getBody());
    }

    public static function StatusCode()
    {
        return self::$response->getStatusCode();
    }

    /**
     * Get Request
     */
    public static function GET($baseUri, $apiEndpoint, $options = [])
    {
        self::Query('GET', $baseUri, $apiEndpoint, $options);
    }

    /**
     * Post Request
     */
    public static function POST($baseUri, $apiEndpoint, $options = [])
    {
        self::Query('POST', $baseUri, $apiEndpoint, $options);
    }

    /**
     * Put Request
     */
    public static function PUT($baseUri, $apiEndpoint, $options = [])
    {
        self::Query('PUT', $baseUri, $apiEndpoint, $options);
    }

    /**
     * Delete Request
     */
    public static function DELETE($baseUri, $apiEndpoint, $options = [])
    {
        self::Query('DELETE', $baseUri, $apiEndpoint, $options);
    }
}
