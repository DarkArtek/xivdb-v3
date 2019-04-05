<?php

namespace App\Command;

use Lodestone\Api;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;
use XIVCommon\Logger\Logger;

/**
 * @package App\Command
 */
class ManageLogsCommand extends ContainerAwareCommand
{
    /**
     * Configure search engine
     */
    protected function configure()
    {
        $this
            ->setName('app:logs')
            ->setDescription('Manage the application logs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Getting all recorded logs');
        $logs = Logger::readAll();

        // do something with $logs
        // todo --


        $output->writeln('Cleaning up logs');
        Logger::cleanup(true);
    }
}
