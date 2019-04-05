<?php

namespace App\Services\Sync;

use App\Entity\Repository\SyncServerRepository;
use App\Entity\SyncServer;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class SyncServerList
{
    /** @var EntityManagerInterface $manager */
    protected $manager;
    
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->manager = $entityManager;
    }
    
    /**
     * @param $names
     */
    public function getLowPopulatedServer($names)
    {
        /** @var SyncServerRepository $repo */
        $repo = $this->manager->getRepository(SyncServer::class);
        $server = $repo->getLowestPopulatedServer($names);

        if (!$server) {
            die('You have not imported any server names ... run: php bin/console app:setup:servers');
        }

        return $server;
    }
    
    /**
     * increment server count
     */
    public function incrementServerCount(SyncServer $server)
    {
        $server->setCount( $server->getCount() + 1 );
        $this->manager->merge($server);
        $this->manager->flush();
    }
    
    /**
     * decrease server count
     */
    public function decreaseServerCount(SyncServer $server)
    {
        $server->setCount( $server->getCount() - 1 );
        $this->manager->merge($server);
        $this->manager->flush();
    }

    /**
     * Premium servers can only be specifically selected,
     * I will randomly pick one when someone becomes a
     * premium member however they're free to move anytime.
     *
     * When they move:
     * - Their achievements will move to that server
     * - Their FC will move to that server
     * - They can move over X amount of people (depending on their pledge amount)
     */
    public const PREMIUM = [
        'X-Ifrit',
        'X-Titan',
        'X-Garuda',
        'X-King-Moggle',
        'X-Leviathan',
        'X-Ramuh',
        'X-Shiva',
        'X-Odin',
        'X-Bahamut',
        'Y-OMEGA' // exclusive to app developers
    ];
    
    // characters suspended or chosen not to update, or they
    // are inactive and no longer need updating will move to Limbo
    public const LIMBO = [
        // Server 1
        '11-Limbo',
        '12-Limbo',
        '13-Limbo',
        '14-Limbo',
    ];

    // character servers
    public const CHARACTERS = [
        // Server 1 - SyncCharactersServer1
        '11-Characters',
        '12-Characters',
        '13-Characters',
        '14-Characters',
        // Server 2
        '21-Characters',
        '22-Characters',
        '23-Characters',
        '24-Characters',
        // Server 3
        '31-Characters',
        '32-Characters',
        '33-Characters',
        '34-Characters',
        // Server 4
        '41-Characters',
        '42-Characters',
        '43-Characters',
        '44-Characters',
    ];

    // achievement servers
    public const ACHIEVEMENTS = [
        // Server 1
        '11-Achievements',
        '12-Achievements',
        '13-Achievements',
        '14-Achievements',
        // Server 2
        '21-Achievements',
        '22-Achievements',
        '23-Achievements',
        '24-Achievements',
        // Server 3
        '31-Achievements',
        '32-Achievements',
        '33-Achievements',
        '35-Achievements'
    ];

    // free company servers
    public const FREECOMPANY = [
        // Server 1
        '11-FreeCompany',
        '12-FreeCompany',
        '13-FreeCompany',
        '14-FreeCompany',
        // Server 2
        '21-FreeCompany',
        '22-FreeCompany',
        '23-FreeCompany',
        '24-FreeCompany'
    ];

    // linkshells
    public const LINKSHELL = [
        // Server 1
        '11-Linkshell',
        '12-Linkshell',
        '13-Linkshell',
        '14-Linkshell',
        // Server 2
        '21-Linkshell',
        '22-Linkshell',
        '23-Linkshell',
        '24-Linkshell'
    ];
}
