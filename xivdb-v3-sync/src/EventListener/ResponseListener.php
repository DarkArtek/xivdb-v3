<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        /** @var \Symfony\Component\HttpFoundation\JsonResponse $response */
        $response = $event->getResponse();
        
        // only process if response is a JsonResponse
        if (get_class($response) === JsonResponse::class) {
            // sort keys
            $json = json_decode($response->getContent());
            $this->ksort($json);
            
            // save
            $response->setContent(json_encode($json, JSON_NUMERIC_CHECK));
            
            // if pretty printing
            if ($event->getRequest()->get('pretty')) {
                $response->setContent(
                    json_encode(
                        json_decode($response->getContent()),
                        JSON_PRETTY_PRINT
                    )
                );
            }
            
            // save response
            $event->setResponse($response);
        }
    }
    
    /**
     * Recursively ksort an object
     */
    private function ksort($object)
    {
        if (!$object) {
            return;
        }
        
        foreach ($object as $i => $value) {
            if (is_object($value)) {
                $this->ksort($value);
            }
        }
        
        $ksort = new \ArrayObject($object);
        $ksort->ksort();
    }
}
