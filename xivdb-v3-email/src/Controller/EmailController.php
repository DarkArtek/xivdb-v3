<?php

namespace App\Controller;

use App\Entity\Email;
use App\Repository\EmailRepository;
use App\Service\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends Controller
{
    /** @var Mail */
    private $mail;
    /** @var EntityManagerInterface */
    private $em;

    function __construct(Mail $mail, EntityManagerInterface $em)
    {
        $this->mail = $mail;
        $this->em = $em;
    }

    /**
     * @Route("/", methods="POST")
     */
    public function send(Request $request)
    {
        // decode post request
        $post = json_decode($request->getContent(), true);
        
        /** @var Mail $mail */
        $response = $this->mail->send(
            trim($post['email']),
            trim($post['subject']),
            trim($post['template']),
            $post['data'] ?? []
        );

        // user is not subscribed
        if ($response === 404) {
            throw new NotFoundHttpException();
        }

        return $this->json([
            'response' => $response
        ]);
    }

    /**
     * @Route("/multi", methods="POST")
     */
    public function multi(Request $request)
    {
        // decode post request
        $post = json_decode($request->getContent(), true);

        // get emails and remove duplicates
        $emails = array_unique(explode(',', $post['email']));

        /** @var Mail $mail */
        $response = $this->mail->multi(
            $emails,
            trim($post['subject']),
            trim($post['template']),
            $post['data'] ?? []
        );

        // user is not subscribed
        if ($response === 404) {
            throw new NotFoundHttpException();
        }

        return $this->json([
            'response' => $response
        ]);
    }

    /**
     * @Route("/unsubscribe/{hash}", methods="GET")
     */
    public function unsubscribe($hash)
    {
        /** @var EmailRepository $repo */
        $repo = $this->em->getRepository(Email::class);
        $email = $repo->findByHash($hash);

        if (!$email) {
            throw new NotFoundHttpException();
        }

        $email->setSubscribed(false);
        $this->em->persist($email);
        $this->em->flush();

        return $this->render('unsubscribe.html.twig');
    }

    /**
     * @Route("/subscribe/{hash}", methods="GET")
     */
    public function subscribe($hash)
    {
        /** @var EmailRepository $repo */
        $repo = $this->em->getRepository(Email::class);
        $email = $repo->findByHash($hash);

        if (!$email) {
            throw new NotFoundHttpException();
        }

        $email->setSubscribed(true);
        $this->em->persist($email);
        $this->em->flush();

        return $this->json([
            'status' => true
        ]);
    }
}
