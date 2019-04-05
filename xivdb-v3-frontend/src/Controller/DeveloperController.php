<?php

namespace App\Controller;

use App\Service\API\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DeveloperController extends Controller
{
    /**
     * @Route("/api", name="api")
     * @Route("/api/{file}", name="api_file")
     */
    public function api($file = null)
    {
        return $this->render(
            'developers/api.twig',
            (new ApiService())->getDocs($file)
        );
    }
    
    /**
     * @Route("/developers", name="developers")
     */
    public function index()
    {
        // todo
    }
    
    /**
     * @Route("/developers/blog", name="developers_blog")
     */
    public function blog()
    {
        // todo
    }
    
    /**
     * @Route("/developers/me", name="developers_me")
     */
    public function me()
    {
        // todo
    }
    
    /**
     * @Route("/developers/create-app", name="developers_create")
     */
    public function create()
    {
        // todo
    }
    
    /**
     * @Route("/developers/app/{id}", name="developers_app")
     */
    public function app($id)
    {
        // todo
    }
    
    /**
     * @Route("/developers/app/{id}/update", name="developers_update")
     */
    public function update($id)
    {
        // todo
    }
    
    /**
     * @Route("/developers/app/{id}/delete", name="developers_delete")
     */
    public function delete($id)
    {
        // todo
    }
    
    /**
     * @Route("/developers/git/{repo}", name="developers_git")
     */
    public function repo($repo)
    {
        // todo
    }
    
    /**
     * @Route("/developers/git/{repo}/{file}", name="developers_git_file")
     */
    public function repoFile($repo, $file)
    {
        // todo
    }
    
    /**
     * @Route("/developers/tooltips", name="developers_tooltips")
     */
    public function tooltips()
    {
        // todo
    }
    
    /**
     * @Route("/developers/mapper", name="developers_mapper")
     */
    public function mapper()
    {
        // todo
    }
}
