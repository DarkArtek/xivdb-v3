<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Character;
use XIV\Services\Sync;

class LodestoneController extends Controller
{
    private $manager;
    
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    
    /**
     * We don't care about name or server, it is just for SEO
     * @Route("/character/{id}/{name}/{server}", name="lodestone_character")
     */
    public function character(Character $character, $name = null, $server = null)
    {
        $data = (new Sync())->character($character->getId(), [
            'files'     => 'data,friends,achievements,sync,events,gear,tracking',
            'extend'    => true,
            'fc'        => true,
        ]);
    
        return $this->render('lodestone/character.twig', [
            'entity' => $character,
            'character' => $data
        ]);
    }
    
    /**
     * @Route("/freecompany/{id}/{name}/{server}", name="lodestone_freecompany")
     */
    public function freecompany(Request $request, $id, $name = null, $server = null)
    {
        return $this->render('lodestone/freecompany.twig', [
        
        ]);
    }
    
    /**
     * @Route("/linkshell/{id}/{name}/{server}", name="lodestone_linkshell")
     */
    public function linkshell(Request $request, $id, $name = null, $server = null)
    {
        return $this->render('lodestone/linkshell.twig', [
        
        ]);
    }
}
