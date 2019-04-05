<?php

namespace XIV\Services;

use XIV\Guzzle\Guzzle;
use GuzzleHttp\RequestOptions;

class Email
{
    const ENDPOINT  = 'MS_EMAIL';
    const DEV       = 'josh@viion.co.uk';
    
    /**
     * Send an email
     */
    public function send(string $email, string $subject, string $template, ?array $data = [])
    {
        Guzzle::POST(self::ENDPOINT, '/', [
            RequestOptions::JSON => [
                'email'     => $email,
                'subject'   => $subject,
                'template'  => $template,
                'data'      => $data,
            ]
        ]);

        return Guzzle::Response();
    }
    
    /**
     * Send multiple emails
     */
    public function multi(string $emails, string $subject, string $template, ?array $data = [])
    {
        Guzzle::POST(self::ENDPOINT, '/multi', [
            RequestOptions::JSON => [
                'email'     => $emails,
                'subject'   => $subject,
                'template'  => $template,
                'data'      => $data,
            ]
        ]);

        return Guzzle::Response();
    }
    
    public function unsubscribe(string $hash)
    {
        // todo - not sure if this needs implementing?
    }
    
    public function subscription(string $hash)
    {
        // todo - not sure if this needs implementing?
    }
}
