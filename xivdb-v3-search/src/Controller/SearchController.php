<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Routing\Annotation\Route,
    Symfony\Component\HttpFoundation\Request;

use App\Service\Search\SearchRequest;
use App\Service\Search\SearchResponse;
use App\Service\Search\Search;

/**
 * @package App\Controller
 */
class SearchController extends Controller
{
    /** @var Search */
    private $search;

    function __construct(Search $search)
    {
        $this->search = $search;
    }

    /**
     * @Route("/Search")
     * @Route("/search")
     * @throws
     */
    public function search(Request $request)
    {
        $searchRequest = new SearchRequest();
        $searchRequest->buildFromRequest($request);
        
        $searchResponse = new SearchResponse($searchRequest);
        $this->search->handleRequest($searchRequest, $searchResponse);

        return $this->json($searchResponse->response);
    }

    /**
     * @Route("/Filters")
     * @Route("/filters")
     */
    public function filters(Request $request)
    {
        // todo - generate filters automatically
    }
}
