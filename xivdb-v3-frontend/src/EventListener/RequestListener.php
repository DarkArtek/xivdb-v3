<?php

namespace App\EventListener;

use App\Service\Analytics\Counter;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use App\Service\User\LanguageService;

class RequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        
        // initialize users request
        LanguageService::init($request);
        
        // track
        Counter::track();
    }
}
