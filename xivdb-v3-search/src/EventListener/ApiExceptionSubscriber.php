<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (getenv('APP_ENV') === 'dev') {
            return;
        }
        
        $ex = $event->getException();

        $response = new JsonResponse(
            [
                'error' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(),
            ],
            method_exists($ex, 'getStatusCode') ? $ex->getStatusCode() : 500
        );

        $response->headers->set('Content-Type', 'application/problem+json');
        $event->setResponse($response);
    }
}
