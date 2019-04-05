<?php

namespace App\Controller;

use App\Entity\FeedbackEmail;
use App\Entity\Feedback;
use App\Repository\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use XIV\XivService;

class FeedbackController extends Controller
{
    /** @var XivService */
    private $xivService;
    
    public function __construct()
    {
        $this->xivService = new XivService();
    }
    
    /**
     * @Route(
     *     "/{feedback}",
     *     methods="GET",
     *     requirements={"feedback": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function index(Feedback $feedback)
    {
        return $this->json($feedback);
    }

    /**
     * @Route(
     *     "/search",
     *     methods="GET"
     * )
     */
    public function search(Request $request)
    {
        /** @var FeedbackRepository $repo */
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Feedback::class);

        return $this->json($repo->search($request));
    }

    /**
     * @Route(
     *     "/{feedback}",
     *     methods="PUT",
     *     requirements={"feedback": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function update(Request $request, Feedback $feedback)
    {
        $json = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();

        $feedback
            ->setTitle($json->title ?? $feedback->getTitle())
            ->setMessage($json->message ?? $feedback->getMessage())
            ->setData($json->data ?? $feedback->getData())
            ->setCategory($json->category ?? $feedback->getCategory())
            ->setStatus($json->status ?? $feedback->getStatus())
            ->setPrivate($json->private ?? $feedback->isPrivate())
            ->setWaiting($json->waiting ?? $feedback->isWaiting())
            ->setEmailSubscriptions($json->emailSubscriptions ?? $feedback->getEmailSubscriptions())
            ->setScreenshots($json->screenshots ?? $feedback->getScreenshots())
            ->setUpdated();

        $em->persist($feedback);

        // send an email if the status has changed
        if ($feedback->hasChanged()) {
            $email = new FeedbackEmail(
                FeedbackEmail::SINGLE,
                "Feedback Updated: (Ref {$feedback->getRef()}) {$feedback->getTitle()}",
                'feedback_updated',
                $feedback->getId()
            );
            $em->persist($email);
    
            /*
            // send discord bot alert
            $this->xivService->Mognet->send('/api/feedback/updated', [
                'feedback' => $feedback->data(),
            ]);
            */
        }

        $em->flush();
        return $this->json($feedback);
    }

    /**
     * @Route(
     *     "/{feedback}",
     *     methods="DELETE",
     *     requirements={"feedback": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function delete(Request $request, Feedback $feedback)
    {
        $em = $this->getDoctrine()->getManager();

        // if to fully delete or not
        if ($request->get('fully')) {
            $em->remove($feedback);
        } else {
            $feedback->setDeleted(true)->setUpdated();
            $em->persist($feedback);
        }

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

        $feedback = new Feedback();
        $feedback
            ->setUserId($json->userId)
            ->setTitle($json->title)
            ->setMessage($json->message)
            ->setData($json->data ?? $feedback->getData())
            ->setCategory($json->category ?? $feedback->getCategory())
            ->setStatus($json->status ?? $feedback->getStatus())
          	->setPrivate($json->private ?? $feedback->isPrivate())
            ->setEmailSubscriptions($json->emailSubscriptions ?? $feedback->getEmailSubscriptions());

        // append on dev email to all newly created feedback
        $feedback->addEmailSubscription(getenv('DEV_EMAIL'));
        $em->persist($feedback);
    
        $email = new FeedbackEmail(
            FeedbackEmail::SINGLE,
            getenv('DEV_EMAIL'),
            "New Feedback: (Ref {$feedback->getRef()}) {$feedback->getTitle()}",
            'feedback_new_dev',
            $feedback->getId()
        );
        $em->persist($email);
    
        // send discord bot alert
        /*
        $this->xivService->Mognet->send('/api/feedback/new', [
            'feedback' => $feedback->data(),
        ]);
        */

        $em->flush();
        return $this->json($feedback);
    }
}
