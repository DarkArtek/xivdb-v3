<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExampleController extends Controller
{
    /**
     * @Route("/ping", methods="GET")
     */
    public function ping()
    {
        return $this->json(true);
    }
}
