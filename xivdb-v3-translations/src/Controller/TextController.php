<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Text;
use App\Repository\TextRepository;

class TextController extends Controller
{
    /**
     * @Route(
     *     "/{text}",
     *     methods="GET",
     *     requirements={"text": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function index(Text $text)
    {
        return $this->json($text);
    }
    
    /**
     * @Route(
     *     "/search",
     *     methods="GET"
     * )
     */
    public function search(Request $request)
    {
        /** @var TextRepository $repo */
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Text::class);
        
        return $this->json($repo->search($request));
    }
    
    /**
     * @Route(
     *     "/{text}",
     *     methods="PUT",
     *     requirements={"text": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function update(Request $request, Text $text)
    {
        $json = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();
        
        $text
            ->setIdString($json->idString ?? $text->getIdString())
            ->setNotes($json->notes ?? $text->getNotes())
            ->setEn($json->en ?? $text->getEn())
            ->setDe($json->de ?? $text->getDe())
            ->setFr($json->fr ?? $text->getFr())
            ->setJa($json->ja ?? $text->getJa())
            ->setCn($json->cn ?? $text->getCn())
            ->setKr($json->kr ?? $text->getKr())
            ->setUpdated();
        
        $em->persist($text);
        $em->flush();
        
        return $this->json($text);
    }
    
    /**
     * @Route(
     *     "/{text}",
     *     methods="DELETE",
     *     requirements={"text": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function delete(Text $text)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($text);
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
        $em = $this->getDoctrine()->getManager();
        
        $text = new Text();
        $text
            ->setIdString($json->idString)
            ->setEn($json->en)
            ->setHash();

        $em->persist($text);
        $em->flush();
        
        return $this->json($text);
    }
}
