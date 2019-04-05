<?php

namespace App\Command;

use App\Service\Mail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestEmailCommand extends Command
{
    /** @var Mail */
    private $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:email:test')
            ->setDescription('Send a weekly test email to me')
            ->addArgument('email', InputArgument::REQUIRED, 'Email to send to');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->text('Sending test email');

        // send test email
        $this->mail->send(
            getenv('DEV_EMAIL'),
            'Test email from XIVDB',
            'test'
        );

        $io->text([
            'Email sent to: '. $input->getArgument('email'),
            ''
        ]);
    }
}
