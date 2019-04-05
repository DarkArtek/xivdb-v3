<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Routing\Annotation\Route,
    Symfony\Component\HttpFoundation\Request;

use App\Services\Game\LinkshellService;
use Lodestone\Api;

/**
 * @package App\Controller
 * @Route("/linkshell")
 */
class LinkshellController extends Controller
{
    /** @var Api */
    private $api;
    /** @var LinkshellService */
    private $service;

    public function __construct(LinkshellService $service)
    {
        $this->api = new Api();
        $this->service = $service;
    }

    /**
     * @Route("/search", name="lodestone_linkshell_search", methods="GET")
     */
    public function search(Request $request)
    {
        $results = $this->api->searchLinkshell(
            $request->get('name'),
            $request->get('server'),
            $request->get('page')
        );

        $this->service->addPending($results->getLinkshells());

        return $this->json($results->toArray());
    }

    /**
     * @Route("/parse/{id}", name="lodestone_linkshell_parse", methods="GET")
     */
    public function home(Request $request, $id)
    {
        $linkshell = $this->api->getLinkshellMembers($id, $request->get('page'));
        $this->service->addPending([$linkshell ]);

        return $this->json($linkshell->toArray());
    }
}
