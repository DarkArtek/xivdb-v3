<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Routing\Annotation\Route,
    Symfony\Component\HttpFoundation\Request;

use App\Services\Game\CharacterService;
use App\Services\Game\FreeCompanyService;
use Lodestone\Api;
use Lodestone\Entities\Search\SearchCharacter;

/**
 * @package App\Controller
 * @Route("/character")
 */
class CharacterController extends Controller
{
    /** @var Api */
    private $api;
    /** @var CharacterService */
    private $service;
    /** @var FreeCompanyService */
    private $freecompanyService;

    public function __construct(CharacterService $service, FreeCompanyService $freeCompanyService)
    {
        $this->api = new Api();
        $this->service = $service;
        $this->freecompanyService = $freeCompanyService;
    }

    /**
     * @Route("/search", name="lodestone_character_search", methods="GET")
     */
    public function search(Request $request)
    {
        /** @var SearchCharacter $results */
        $results = $this->api->searchCharacter(
            $request->get('name'),
            $request->get('server'),
            $request->get('page')
        );

        if (getenv('ADD_CHARACTERS')) {
            $this->service->addPending($results->getCharacters());
        }

        return $this->json($results->toArray());
    }

    /**
     * @Route("/parse/{id}", name="lodestone_character_parse", methods="GET")
     */
    public function parse($id)
    {
        $result = $this->api->getCharacter($id);
        
        if (getenv('ADD_CHARACTERS')) {
            $this->service->addPending([ $result ]);
        }

        if ($result->getFreecompany()) {
            $this->freecompanyService->addPending([
                $result->getFreecompany()
            ]);
        }

        return $this->json($result->toArray());
    }

    /**
     * @Route("/parse/{id}/friends", name="lodestone_character_friends", methods="GET")
     */
    public function friends(Request $request, $id)
    {
        $results = $this->api->getCharacterFriends($id, $request->get('page'));
        
        if (getenv('ADD_CHARACTERS')) {
            $this->service->addPending($results->getCharacters());
        }

        return $this->json($results->toArray());
    }

    /**
     * @Route("/parse/{id}/following", name="lodestone_character_following", methods="GET")
     */
    public function following(Request $request, $id)
    {
        $results = $this->api->getCharacterFollowing($id, $request->get('page'));
        
        if (getenv('ADD_CHARACTERS')) {
            $this->service->addPending($results->getCharacters());
        }

        return $this->json($results->toArray());
    }

    /**
     * @Route("/parse/{id}/achievements", name="lodestone_character_achievements", methods="GET")
     */
    public function achievements(Request $request, $id)
    {
        // if category passed, get that one
        if ($categoryId = $request->get('category')) {
            $results = $this->api->getCharacterAchievements($id, $categoryId)->toArray();
        } else {
            // get all categories
            $results = [];
            foreach([1,2,3,4,5,6,8,11,12,13] as $categoryId) {
                $results[] = $this->api->getCharacterAchievements($id, $categoryId)->toArray();
            }
        }

        return $this->json($results);
    }
}
