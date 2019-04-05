<?php

namespace App\Controller;

use App\Service\FreeCompany\FreeCompanyRabbitHandler;
use App\Service\Storage\StorageService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Routing\Annotation\Route,
    Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Controller
 * @Route("/FreeCompany")
 */
class FreeCompanyController extends Controller
{
    /** @var StorageService */
    private $storage;

    public function __construct(StorageService $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @Route("/{id}", name="FreeCompany", methods="GET")
     */
    public function data(Request $request, $id)
    {
        $response = new \stdClass();
        
        // setup storage
        $this->storage->setBucket(FreeCompanyRabbitHandler::BUCKET);

        // build response
        $allowedDataSet = [
            'data','sync','members'
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
