<?php

namespace App\Controller;

use App\Entity\LodestoneContent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(LodestoneContent::class);
        
        return $this->render('home/index.twig', [
            'LodestoneContent' => $repo->findBy([], ['time' => 'desc'], 50),
        ]);
    }
    
    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('home/about.twig');
    }
    
    /**
     * @Route("/ping", name="ping")
     */
    public function ping()
    {
        return $this->json(true);
    }
}
