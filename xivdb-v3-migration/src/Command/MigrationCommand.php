<?php

namespace App\Command;

use App\Handler;
use App\Migration\Users;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MigrationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:migration')
            ->setDescription('Migrate v2 to v3!')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (new Handler(new SymfonyStyle($input, $output)))->go();
    }
}
