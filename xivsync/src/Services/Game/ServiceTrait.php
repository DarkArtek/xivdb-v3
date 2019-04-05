<?php

namespace App\Services\Game;

trait ServiceTrait
{
    protected $time = 0;
    protected $stats = 0;

    public function init()
    {
        $this->time = time();

        if (!getenv('API_RABBIT_QUEUE')) {
            die("!!! API_RABBIT_QUEUE NOT SET IN .ENV FILE !!!");
        }
    }
}