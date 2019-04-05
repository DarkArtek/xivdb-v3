<?php

namespace App\Command;

use App\Entity\FeedbackComment;
use App\Entity\FeedbackEmail;
use App\Entity\Feedback;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use XIV\XivService;

class SendEmailsCommand extends Command
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var XivService */
    private $xivService;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->xivService = new XivService();

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:feedback:emails')
            ->setDescription('Send out emails.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->text('Sending out emails');

        // grab feedback
        $list = $this->em->getRepository(FeedbackEmail::class)->findBy([
            'sent' => false,
        ]);

        // 15 minutes
        $timeout = time() - (60*15);

        /** @var FeedbackEmail $email */
        foreach ($list as $email) {
            $io->text("Sending email: {$email->getEmail()} {$email->getSubject()}");

            // if ready to send
            if ($email->getAdded() < $timeout) {
                /** @var Feedback $feedback */
                $feedback = $email->getFeedbackId() ? $this->em->getRepository(Feedback::class)->find($email->getFeedbackId()) : false;
                /** @var FeedbackComment $comment */
                $comment = $email->getCommentId() ? $this->em->getRepository(FeedbackComment::class)->find($email->getCommentId()) : false;

                // action based on email type
                switch($email->getType()) {
                    case FeedbackEmail::SINGLE:
                        $email->setSent(true);
                        $this->xivService->Email->send(
                            $email->getEmail(),
                            $email->getSubject(),
                            $email->getTemplate(),
                            [
                                'feedback' => $feedback ? $feedback->data() : false,
                                'comment' => $comment ? $comment->data() : false,
                            ]
                        );
                        break;

                    case FeedbackEmail::MULTI:
                        $email->setSent(true);
                        $this->xivService->Email->multi(
                            $email->getEmail(),
                            $email->getSubject(),
                            $email->getTemplate(),
                            [
                                'feedback' => $feedback ? $feedback->data() : false,
                                'comment' => $comment ? $comment->data() : false,
                            ]
                        );
                        break;
                }

                $io->text('- Email Sent!');
                $this->em->persist($email);
            } else {
                $io->text('- Time not past 15 minutes');
            }
        }

        $this->em->flush();
        $io->text('Finished');
    }
}
