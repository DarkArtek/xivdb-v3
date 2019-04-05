<?php

namespace App\Service;

use App\Entity\Email;
use Doctrine\ORM\EntityManagerInterface;
use Postmark\PostmarkClient;

class MailHelper
{
    /** @var \Twig_Environment */
    protected $twig;
    /** @var EntityManagerInterface */
    protected $em;

    function __construct(
        \Twig_Environment $twig,
        EntityManagerInterface $em
    ) {
        $this->twig = $twig;
        $this->em = $em;
    }

    /**
     * Provides access to postmark
     */
    protected function getPostmarkClient()
    {
        return new PostmarkClient(getenv('POSTMARK_KEY'));
    }

    /**
     * Send an email using postmark
     */
    protected function sendMail(Email $email, string $subject, string $template, array $data)
    {
        $from = getenv('FROM');
        [$html, $text] = $this->renderTemplate($template, [
            'email' => $email,
            'data' => $data,
        ]);

        // send email via postmark
        return $this
            ->getPostmarkClient()
            ->sendEmail(
                $from,
                $email->getEmail(),
                $subject,
                $html,
                $text,
                $template,
                true,
                $from
            );
    }

    /**
     * Gets an email object for a given email string
     */
    protected function getEmail($email)
    {
        $obj = $this
            ->em
            ->getRepository(Email::class)
            ->findOneBy(['email' => $email]);

        // if there is no existing email obj
        // then create a new one
        if (!$obj) {
            $obj = new Email();
            $obj->setEmail($email)->setHash(sha1($email . time()));

        }

        $obj->setTotalEmailsSent(
            $obj->getTotalEmailsSent()+1
        );

        $this->em->persist($obj);
        $this->em->flush();

        return $obj;
    }

    /**
     * Render a template via twig, returns html and plaintext
     */
    protected function renderTemplate(string $template, array $data)
    {
        $html = $this->twig->render("emails/{$template}.twig", $data);

        return [
            $html,
            strip_tags($html)
        ];
    }
}
