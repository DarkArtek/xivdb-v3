<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MonitorController extends Controller
{
    /**
     * @Route("/", methods="GET")
     */
    public function index()
    {
        return $this->json(
            json_decode(file_get_contents(__DIR__.'/../Service/results.json'), true)
        );
    }
}
