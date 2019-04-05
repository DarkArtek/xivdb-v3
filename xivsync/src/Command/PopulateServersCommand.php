<?php

namespace App\Command;

use App\Entity\SyncServer;
use App\Services\Sync\SyncServerList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

/**
 * @package App\Command
 */
class PopulateServersCommand extends ContainerAwareCommand
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        EntityManagerInterface $em,
        $name = null
    ) {
        parent::__construct($name);
        $this->em = $em;
    }


    /**
     * Configure search engine
     */
    protected function configure()
    {
        $this
            ->setName('app:setup:servers')
            ->setDescription('Setup sync server names')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Add sync servers to the site');

        $servers = array_merge(
            SyncServerList::LIMBO,
            SyncServerList::PREMIUM,
            SyncServerList::CHARACTERS,
            SyncServerList::ACHIEVEMENTS,
            SyncServerList::FREECOMPANY,
            SyncServerList::LINKSHELL
        );
        
        foreach($servers as $name) {
            $this->em->persist(
                new SyncServer($name)
            );
        }

        $this->em->flush();
        $output->writeln('Finished');
    }
}
