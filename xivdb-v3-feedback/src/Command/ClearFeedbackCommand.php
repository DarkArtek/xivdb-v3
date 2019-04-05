<?php

namespace App\Command;

use App\Entity\Feedback;
use App\Repository\FeedbackRepository;
use App\Strings\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClearFeedbackCommand extends Command
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:feedback:clear')
            ->setDescription('Clear out dead.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->text('Clearing out dead feedback...');

        // grab feedback
        $list = $this->em->getRepository(Feedback::class)->findBy([
            'waiting' => true,
        ]);

        // 60 days
        $timeout = (60*60*24*60);

        /** @var Feedback $feedback */
        foreach ($list as $feedback) {
            $io->text('Feedback: '. $feedback->getTitle());

            // delete if it is past the timeout
            if ((time() - $feedback->getUpdated()) > $timeout) {
                // delete
                $feedback
                    ->setDeleted(true)
                    ->setWaiting(false)
                    ->setUpdated()
                    ->setStatus(Status::NO_ACTIVITY_AUTO_DELETE);

                $this->em->persist($feedback);

                $io->text('- Feedback Deleted');
            }
        }

        $this->em->flush();

        $io->text('Finished');
    }
}
