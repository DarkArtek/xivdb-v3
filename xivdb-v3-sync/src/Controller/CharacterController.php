<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Routing\Annotation\Route,
    Symfony\Component\HttpFoundation\Request;
use XIVCommon\Language\LanguageConverter;
use App\Service\Characters\CharacterRabbitHandler;
use App\Service\Characters\GameData\CharacterData;
use App\Service\Characters\GameData\CharacterGear;
use App\Service\FreeCompany\FreeCompanyRabbitHandler;
use App\Service\Storage\StorageService;

/**
 * @package App\Controller
 * @Route("/Character")
 */
class CharacterController extends Controller
{
    /** @var StorageService */
    private $storage;

    public function __construct(StorageService $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @Route("/{id}", name="Character", methods="GET")
     */
    public function data(Request $request, $id)
    {
        $response = new \stdClass();
        
        // setup storage
        $this->storage->setBucket(CharacterRabbitHandler::BUCKET);

        // build response
        $allowedDataSet = [
            'data','friends','achievements','sync','events','gear','tracking'
        ];
        
        // get data set to response with
        $set = $request->get('files') ? str_getcsv($request->get('files')) : ['data'];
        
        // grab all data!
        foreach($set as $name) {
            if (in_array($name, $allowedDataSet)) {
                $response->{$name} = $this->storage->load("{$id}_{$name}");
            }
        }
        
        // extend
        if ($request->get('extend')) {
            if (isset($response->data)) {
                $response->data = (new CharacterData($response->data))->data;
            }
            
            if (isset($response->gear)) {
                $response->gear = (new CharacterGear($response->gear))->data;
                
            }
        }
        
        // get fc info
        if ($request->get('fc')) {
            $response->fc = null;
            
            if ($response->data->freecompany) {
                $fcId = substr($response->data->freecompany, 1);
    
                // grab fc
                $this->storage->setBucket(FreeCompanyRabbitHandler::BUCKET);
                $response->fc = $this->storage->load("{$fcId}_data");
                $response->fc->id = "i{$response->fc->id}";
            }
        }
        
        // convert some entries into language versions
        LanguageConverter::handle($response, $request->get('language') ?: 'en');

        return $this->json($response);
    }
}
