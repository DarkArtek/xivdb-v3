<?php

namespace App\Controller;

use App\Services\Game\CharacterService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Routing\Annotation\Route,
    Symfony\Component\HttpFoundation\Request;

use App\Services\Game\FreeCompanyService;
use Lodestone\Api;
use Lodestone\Entities\Search\SearchFreeCompany;

/**
 * @package App\Controller
 * @Route("/freecompany")
 */
class FreeCompanyController extends Controller
{
    /** @var Api */
    private $api;
    /** @var FreeCompanyService */
    private $service;
    /** @var CharacterService */
    private $characterService;

    public function __construct(FreeCompanyService $service, CharacterService $characterService)
    {
        $this->api = new Api();
        $this->service = $service;
        $this->characterService = $characterService;
    }

    /**
     * @Route("/search", name="lodestone_free_company_search", methods="GET")
     */
    public function search(Request $request)
    {
        /** @var SearchFreeCompany $results */
        $results = $this->api->searchFreeCompany(
            $request->get('name'),
            $request->get('server'),
            $request->get('page')
        );

        $this->service->addPending($results->getFreeCompanies());
        return $this->json($results->toArray());
    }

    /**
     * @Route("/parse/{id}", name="lodestone_free_company_parse", methods="GET")
     */
    public function parse(Request $request, $id)
    {
        $freecompany = $this->api->getFreeCompany($id);
        $this->service->addPending([ $freecompany ]);

        return $this->json($freecompany->toArray());
    }

    /**
     * @Route("/parse/{id}/members", name="lodestone_free_company_members", methods="GET")
     */
    public function freeCompanyMembersAction(Request $request, $id)
    {
        $freecompany = $this->api->getFreeCompanyMembers($id, $request->get('page'));
        $this->characterService->addPending($freecompany->getCharacters());

        return $this->json($freecompany->toArray());
    }
}
