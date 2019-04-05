<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MicroService\FeedbackMicroService;
use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use XIV\XivService;
use XIVCommon\Redis\RedisCache;

class FeedbackController extends Controller
{
    const TYPES = [
        'Site Bug',
        'Account Issues',
        'Content Data',
        'Dev App Issues',
        'Other'
    ];
    
    /** @var UserService */
    private $userService;
    /** @var RedisCache */
    private $redisCache;
    /** @var Session */
    private $session;
    /** @var XivService */
    private $sdk;
    
    public function __construct(UserService $userService, SessionInterface $session)
    {
        $this->userService  = $userService;
        $this->session      = $session;
        $this->redisCache   = new RedisCache();
        $this->sdk          = new XivService();
    }
    
    /**
     * @Route("/issues", name="issues")
     */
    public function index(Request $request)
    {
        [$public, $private, $counts] = FeedbackMicroService::list($request->get('cat'));
        
        return $this->render('support/public.twig', [
            'SupportIssues'  => $public,
            'SupportCounts'  => $counts,
        ]);
    }
    
    /**
     * @Route("/issues/private", name="issues_private")
     */
    public function private(Request $request)
    {
        [$public, $private, $counts] = FeedbackMicroService::list($request->get('cat'));
    
        return $this->render('support/private.twig', [
            'SupportIssues'  => $private,
            'SupportCounts'  => $counts,
        ]);
    }
    
    /**
     * @Route("/issues/create", name="issues_create")
     */
    public function create(Request $request)
    {
        $user = $this->userService->getUser();
        
        if (!$user) {
            return $this->json([
                'error' => 'You do not seem to be logged in, refresh the page and try again'
            ]);
        }
        
        $data = [
            'userId'    => $user->getId(),
            'title'     => 'XIVDB Issue',
            'message'   => trim($request->get('sMessage')),
            'category'  => trim($request->get('sType')),
            'status'    => 'Open',
            'private'   => $request->get('sPrivate'),
        ];
        
        if ($request->get('sEmail')) {
            $data['emailSubscriptions'] = [ $user->getEmail() ];
        }
        
        // post
        $feedback = $this->sdk->Feedback->create($data);
        
        return $this->json([
            'id'  => $feedback->id,
            'ref' => $feedback->ref
        ]);
    }
    
    /**
     * @Route("/issues/{id}", name="feedback_issue")
     */
    public function issue($id)
    {
        [$public, $private, $counts] = FeedbackMicroService::list();
        $issue = $this->sdk->Feedback->get($id);
        
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $issue->user = $userRepository->findOneBy([ 'id' => $issue->userId ]);
        
        foreach (array_reverse($issue->comments) as $comment) {
            $comment->user = $userRepository->findOneBy([ 'id' => $comment->userId ]);
        }
        
        return $this->render('support/issue.twig', [
            'Issue' => $issue,
            'SupportCounts'  => $counts,
        ]);
    }
    
    /**
     * @Route("/issues/issue/{id}/comment", name="issues_comment")
     */
    public function comment(Request $request, $id)
    {
        $user = $this->userService->getUser();
    
        if (!$user) {
            return $this->json([
                'error' => 'You do not seem to be logged in, refresh the page and try again'
            ]);
        }
        
        $this->sdk->FeedbackComment->create($id, [
            'userId'  => $user->getId(),
            'message' => $request->get('sComment'),
        ]);
        
        
        $this->session->getFlashBag()->set('feedback_comment_new', true);
        
        return $this->redirectToRoute('feedback_issue', [ 'id' => $id ]);
    }
    
    /**
     * @Route("/issues/issue/{id}/comment/{commentId}/delete", name="issues_comment_delete")
     */
    public function commentDelete($id, $commentId)
    {
        $user = $this->userService->getUser();
    
        if (!$user) {
            return $this->json([
                'error' => 'You do not seem to be logged in, refresh the page and try again'
            ]);
        }
        
        $this->sdk->FeedbackComment->delete($id, $commentId, true);
        
        $this->session->getFlashBag()->set('feedback_comment_deleted', true);

        return $this->redirectToRoute('feedback_issue', [
            'id' => $id,
        ]);
    }
    
    /**
     * @Route("/issues/issue/{id}/comment/{commentId}/update", name="issues_comment_update")
     */
    public function commentUpdate($id)
    {
        // todo
    }
}
