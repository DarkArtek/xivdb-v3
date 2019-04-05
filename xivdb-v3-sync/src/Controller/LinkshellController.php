<?php

namespace App\Controller;

use App\Service\Linkshells\LinkshellRabbitHandler;
use App\Service\Storage\StorageService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Routing\Annotation\Route,
    Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Controller
 * @Route("/Linkshell")
 */
class LinkshellController extends Controller
{
    /** @var StorageService */
    private $storage;

    public function __construct(StorageService $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @Route("/{id}", name="Linkshell", methods="GET")
     */
    public function data(Request $request, $id)
    {
        $response = new \stdClass();

        // setup storage
        $this->storage->setBucket(LinkshellRabbitHandler::BUCKET);
    
        // build response
        $allowedDataSet = [
            'data','sync'
        ];
    
        // get data set to response with
        $set = $request->get('files') ? str_getcsv($request->get('files')) : ['data'];
    
        // grab all data!
        foreach($set as $name) {
            if (in_array($name, $allowedDataSet)) {
                $response->{$name} = $this->storage->load("{$id}_{$name}");
            }
        }
    
        // convert ID
        if (isset($response->data)) {
            $response->data->id = "i{$response->data->id}";
        }

        return $this->json($response);
    }
}
