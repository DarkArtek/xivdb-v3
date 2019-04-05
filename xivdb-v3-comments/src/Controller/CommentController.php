<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommentRepository;
use App\Entity\Comment;

class CommentController extends Controller
{
    /**
     * @Route(
     *     "/{comment}",
     *     methods="GET",
     *     requirements={"comment": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function index(Comment $comment)
    {
        return $this->json($comment);
    }
    
    /**
     * @Route(
     *     "/search",
     *     methods="GET"
     * )
     */
    public function search(Request $request)
    {
        /** @var CommentRepository $repo */
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Comment::class);
        
        return $this->json(
            $repo->search($request)
        );
    }
    
    /**
     * @Route(
     *     "/{comment}",
     *     methods="PUT",
     *     requirements={"comment": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function update(Request $request, Comment $comment)
    {
        $json = json_decode($request->getContent());
        
        $comment->setMessage($json->message ?? $comment->getMessage());
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();
        
        return $this->json($comment);
    }
    
    /**
     * @Route(
     *     "/{comment}",
     *     methods="DELETE",
     *     requirements={"comment": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function delete(Request $request, Comment $comment)
    {
        $comment->setDeleted(true);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
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
        
        $comment = new Comment();
        $comment
            ->setIdUnique($json->idUnique)
            ->setIdReply($json->idReply)
            ->setIdUser($json->idUser)
            ->setMessage($json->message);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();
        
        return $this->json($comment);
    }
}
