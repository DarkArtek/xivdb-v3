<?php

namespace App\Controller;

use App\Entity\Character;
use App\Service\API\ApiService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use XIV\XivService;
use XIVCommon\Redis\RedisCache;

class ContentController extends Controller
{
    /** @var RedisCache */
    private $redis;
    
    public function __construct()
    {
        $this->redis = new RedisCache();
    }
    
    /**
     * @Route("/game/patch", name="content_game_patch")
     */
    public function gamePatches()
    {
        return $this->render('content/patch.twig', [
            'PatchList' => (new ApiService())->getPatchList()
        ]);
    }
    
    /**
     * @Route("/game/patch/{version}", name="content_game_patch_view")
     */
    public function gamePatchView($version)
    {
        return $this->render('content/patch_view.twig', [
            'Patch' => (new ApiService())->getPatchFromVersion($version)
        ]);
    }
    
    /**
     * @Route("/character/{id}")
     * @Route("/character/{id}/{name}", name="character")
     */
    public function character(Request $request, $id, $name)
    {
        /** @var Character $character */
        $character = $this->getDoctrine()->getRepository(Character::class)->findOneBy([ 'id' => $id ]);
        
        if (!$character) {
            throw new NotFoundHttpException('Character could not be found');
        }

        // pull character data
        $key = 'character_profile_'. $id;
        if (getenv('BYPASS_CACHE') || !$content = $this->redis->get($key)) {
            $content = (new XivService())->Sync->character($character->getId(), [
                'files'  => 'data,friends,achievements,sync,events,gear,tracking',
                'extend' => 1,
                'fc'     => 1,
            ]);
            
            $this->redis->set($key, $content, (60*60));
        }
    
        $data = [
            'character' => $character,
            'content'   => $content
        ];
        
        return $this->render('content/lodestone/character.twig', $data);
    }
    
    /**
     * @Route("/freecompany/{id}/{name}", name="freecompany")
     */
    public function freecompany(Request $request, $id, $name)
    {
        // todo
    }
    
    /**
     * @Route("/linkshell/{id}/{name}", name="linkshell")
     */
    public function linkshell(Request $request, $id, $name)
    {
        // todo
    }
    
    /**
     * @Route("/achievement/{id}/{name}")
     * @Route("/action/{id}/{name}")
     * @Route("/enemy/{id}/{name}")
     * @Route("/minion/{id}/{name}")
     * @Route("/emote/{id}/{name}")
     * @Route("/npc/{id}/{name}")
     * @Route("/fate/{id}/{name}")
     * @Route("/instance/{id}/{name}")
     * @Route("/item/{id}/{name}")
     * @Route("/leve/{id}/{name}")
     * @Route("/mount/{id}/{name}")
     * @Route("/placename/{id}/{name}")
     * @Route("/quest/{id}/{name}")
     * @Route("/recipe/{id}/{name}")
     * @Route("/status/{id}/{name}")
     * @Route("/title/{id}/{name}")
     * @Route("/weather/{id}/{name}")
     */
    public function content(Request $request, $id, $name = null)
    {
        $name = pathinfo($request->getPathInfo())['dirname'];
        $name = trim(explode('/', $name)[1]);
        
        // check cache
        $key = "content_{$name}_{$id}";
        if (!$content = $this->redis->get($key)) {
            $content = (new XivService())->Data->get($name, $id);
            
            if ($content) {
                $this->redis->set($key, $content, RedisCache::DEFAULT_TIME);
            }
        }
    
        // content not found
        if (!$content) {
            throw new NotFoundHttpException("Content not found");
        }
    
        return $this->render("content/game/{$name}.twig", [
            'content' => $content,
        ]);
    }
}
