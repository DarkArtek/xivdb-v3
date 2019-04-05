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
     * @Route("/", methods="GET")
     */
    public function index()
    {
        return $this->json('hello world');
    }
}
