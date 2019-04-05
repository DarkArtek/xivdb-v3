<?php

namespace App\Command;

use App\Services\Tron\QueueManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

class AutoUpdateManagerCommand extends ContainerAwareCommand
{
    private $em;
    
    public function __construct(EntityManagerInterface $em, ?string $name = null)
    {
        $this->em = $em;
        parent::__construct($name);
    }
    
    /**
     * Configure search engine
     */
    protected function configure()
    {
        $this
            ->setName('app:manage')
            ->setDescription('XIVSync Manage Update Entries')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        new QueueManager(
            new SymfonyStyle($input, $output),
            $this->em
        );
    }
}
