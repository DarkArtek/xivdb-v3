<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Routing\Annotation\Route,
    Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="home", methods="GET")
     */
    public function home()
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/iframe", name="iframe", methods="GET")
     */
    public function iframe()
    {
        return $this->render('iframe.html.twig');
    }
}
