<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use XIV\XivService;
use XIVCommon\Redis\RedisCache;

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
        return;
        
        if (getenv('APP_ENV') === 'dev') {
            return;
        }
        
        $redis   = new RedisCache();
        
        $request = $event->getRequest();
        $ex      = $event->getException();
        
        $ignore = [
            NotFoundHttpException::class
        ];
        
        if (in_array(get_class($ex), $ignore)) {
            return;
        }
    
        $hash = sha1($ex->getMessage() . $ex->getLine() . $ex->getFile());
        $key  = 'SiteExceptionEmails_'. $hash;
        
        if (getenv('SEND_ERROR_EMAIL') && !$redis->get($key)) {
            $sdk = new XivService();
            $sdk->Email->send('josh@viion.co.uk', 'XIVDB ERROR', 'exception', [
                'Exception'     => [
                    'message'   => $ex->getMessage(),
                    'line'      => $ex->getLine(),
                    'file'      => $ex->getFile(),
                    'trace'     => $ex->getTraceAsString(),
                ],
                'Request' => [
                    'url' => $request->getUri(),
                ],
            ]);
    
            // cache error for 5 minutes so we don't get a spam of emails
            $redis->set($key, 'EmailSent', (60*5));
        }
        
        // set response
        $response = new RedirectResponse('/rezplz.html');
        $event->setResponse($response);
    }
}
