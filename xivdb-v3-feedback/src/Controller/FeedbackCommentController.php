<?php

namespace App\Controller;

use App\Entity\FeedbackComment;
use App\Entity\FeedbackEmail;
use App\Entity\Feedback;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use XIV\XivService;

class FeedbackCommentController extends Controller
{
    /** @var XivService */
    private $xivService;
    
    public function __construct()
    {
        $this->xivService = new XivService();
    }
    
    /**
     * @Route(
     *     "/{feedback}/comment/{comment}",
     *     methods="PUT",
     *     requirements={
     *         "feedback": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}",
     *         "comment": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"
     *     }
     * )
     */
    public function update(Request $request, Feedback $feedback, FeedbackComment $comment)
    {
        $json = json_decode($request->getContent());

        $em = $this->getDoctrine()->getManager();

        $comment->setMessage($json->message ?? $comment->getMessage())->setUpdated();

        $em->persist($comment);
        $em->flush();

        return $this->json($comment);
    }

    /**
     * @Route(
     *     "/{feedback}/comment/{comment}",
     *     methods="DELETE",
     *     requirements={
     *         "feedback": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}",
     *         "comment": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"
     *     }
     * )
     */
    public function delete(Request $request, Feedback $feedback, FeedbackComment $comment)
    {
        $em = $this->getDoctrine()->getManager();

        // if to fully delete or not
        if ($request->get('fully')) {
            $em->remove($comment);
        } else {
            $comment->setDeleted(true)->setUpdated();
            $em->persist($comment);
        }
        
        // remove any prepared emails
        $feedbackEmail = $em->getRepository(FeedbackEmail::class)->findOneBy([
            'commentId' => $comment->getId()
        ]);
        
        if ($feedbackEmail) {
            $em->remove($feedbackEmail);
        }

        $em->flush();

        return $this->json([
            'status' => true,
        ]);
    }

    /**
     * @Route(
     *     "/{feedback}/comment",
     *     methods="POST",
     *     requirements={
     *         "feedback": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"
     *     }
     * )
     */
    public function create(Request $request, Feedback $feedback)
    {
        $json = json_decode($request->getContent());

        $em = $this->getDoctrine()->getManager();

        // create comment
        $comment = new FeedbackComment();
        $comment
            ->setUserId($json->userId)
            ->setMessage(trim($json->message))
            ->setFeedback($feedback);

        $em->persist($comment);

        // update feedback, this will mark it is not
        // waiting for anyone and not deleted
        $feedback
            ->setUpdated()
            ->setWaiting(false)
            ->setDeleted(false);
        $em->persist($feedback);

        // send subscription email
        $email = new FeedbackEmail(
            FeedbackEmail::MULTI,
            $feedback->getEmailSubscriptions(true),
            'Feedback Comment: '. $feedback->getTitle(),
            'feedback_comment',
            $feedback->getId(),
            $comment->getId()
        );
        $em->persist($email);

        // send discord bot alert
        /*
        $this->xivService->Mognet->send('/api/feedback/comment', [
            'feedback' => $feedback->data(),
            'comment' => $comment->data()
        ]);
        */

        $em->flush();
        return $this->json($feedback);
    }
}
