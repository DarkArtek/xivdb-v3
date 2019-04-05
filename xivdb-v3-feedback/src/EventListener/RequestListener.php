<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class RequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest() || getenv('APP_ENV') === 'dev') {
            return;
        }
   
        if ($event->getRequest()->headers->get('Authorization') !== "Bearer " . getenv('SERVICE_KEY')) {
            throw new UnauthorizedHttpException('Not Authorised.');
        }
    }
}
