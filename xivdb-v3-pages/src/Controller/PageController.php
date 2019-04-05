<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Page;
use App\Repository\PageRepository;

class PageController extends Controller
{
    /**
     * @Route("/ping", methods="GET")
     */
    public function ping()
    {
        return $this->json(true);
    }
    
    /**
     * @Route(
     *     "/{page}",
     *     methods="GET",
     *     requirements={"page": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function index(Request $request, Page $page)
    {
        $page->incrementView();
    
        $em = $this->getDoctrine()->getManager();
        $em->persist($page);
        $em->flush();
        
        return $this->json($page);
    }
    
    /**
     * @Route(
     *     "/search",
     *     methods="GET"
     * )
     */
    public function search(Request $request)
    {
        /** @var PageRepository $repo */
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Page::class);
    
        return $this->json($repo->search($request));
    }
    
    /**
     * @Route(
     *     "/{page}",
     *     methods="PUT",
     *     requirements={"page": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function update(Request $request, Page $page)
    {
        $json = json_decode($request->getContent());
        
        $page
            ->setTitle($json->title ?? $page->getTitle())
            ->setHtml($json->html ?? $page->getHtml())
            ->setJs($json->js ?? $page->getJs())
            ->setCss($json->css ?? $page->getCss())
            ->setPublished($json->published ?? $page->isPublished())
            ->setSeries($json->series ?? $page->getSeries())
            ->setUpdated()
            ->setUrl();
    
        $em = $this->getDoctrine()->getManager();
        $em->persist($page);
        $em->flush();
        
        return $this->json($page);
    }
    
    /**
     * @Route(
     *     "/{page}",
     *     methods="DELETE",
     *     requirements={"page": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function delete(Request $request, Page $page)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();
        
        return $this->json([
            'status' => true,
        ]);
    }
    
    /**
     * @Route(
     *     "/",
     *     methods="POST"
     * )
     */
    public function create(Request $request)
    {
        $json = json_decode($request->getContent());
        
        $page = new Page();
        $page
            ->setTitle($json->title)
            ->setHtml($json->html)
            ->setJs($json->js ?? null)
            ->setCss($json->css ?? null)
            ->setSeries($json->series ?? 'misc')
            ->setUrl();
    
        $em = $this->getDoctrine()->getManager();
        $em->persist($page);
        $em->flush();
        
        return $this->json($page);
    }
}
