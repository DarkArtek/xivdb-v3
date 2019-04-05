<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Routing\Annotation\Route,
    Symfony\Component\HttpFoundation\Request;

use App\Entity\{
    Character, CharacterPending, FreeCompany, FreeCompanyPending, Linkshell, LinkshellPending, SyncServer
};
use App\Entity\Repository\{
    CharacterRepository, FreeCompanyRepository, LinkshellRepository, SyncServerRepository
};
use App\Services\Sync\SyncServerList;
use Lodestone\Entities\Character\CharacterProfile;
use Lodestone\Api;
use XIVCommon\Redis\RedisCache;

class DefaultController extends Controller
{
    /** @var SyncServerList */
    private $syncServerList;
    
    public function __construct(SyncServerList $syncServerList)
    {
        $this->syncServerList = $syncServerList;
    }
    
    /**
     * @Route("/", methods="GET")
     */
    public function home(Request $request, EntityManagerInterface $em)
    {
        /** @var SyncServerRepository $repo */
        $repo = $em->getRepository(SyncServer::class);
        
        $redis = new RedisCache();
        $redis->setOption('useSerializer', true);
        $stats = $redis->keys('stats,*');

        $counts = [];
        foreach($stats as $i => $key) {
            [$a, $name, $date] = str_getcsv($key);

            if (!isset($counts[$name])) {
                $counts[$name] = [];
            }

            $counts[$name][$date] = $redis->get($key);

            krsort($counts[$name]);
        }

        return $this->render('home.twig', [
            'counts' => $counts,
            'stats' => [
                'premium'       => $repo->getServerPopulation(SyncServerList::PREMIUM),
                'characters'    => $repo->getServerPopulation(SyncServerList::CHARACTERS),
                'achievements'  => $repo->getServerPopulation(SyncServerList::ACHIEVEMENTS),
                'freecompany'   => $repo->getServerPopulation(SyncServerList::FREECOMPANY),
                'linkshell'     => $repo->getServerPopulation(SyncServerList::LINKSHELL),
                'limbo'         => $repo->getServerPopulation(SyncServerList::LIMBO),
            ],
        ]);
    }

    /**
     * @Route("/set/server", methods="GET")
     */
    public function setServer(Request $request, EntityManagerInterface $em)
    {
        /** @var SyncServerRepository $repo */
        $repo = $em->getRepository(SyncServer::class);
        
        $server = $request->get('server');
        $servers = array_merge(
            SyncServerList::PREMIUM,
            SyncServerList::CHARACTERS,
            SyncServerList::LINKSHELL,
            SyncServerList::FREECOMPANY,
            SyncServerList::ACHIEVEMENTS,
            SyncServerList::LIMBO
        );

        if (!in_array($server, $servers)) {
            return $this->json([ 'error' => 'Invalid server name' ]);
        }

        /** @var Character $character */
        $character = $em->getRepository(Character::class)->findOneBy([
            'lodestoneId' => $request->get('lodestoneId')
        ]);
        
        if (!$character) {
            return $this->json([ 'error' => 'Cannot find the character' ]);
        }
        
        if ($character->getSyncServer() === $server) {
            return $this->json([ 'error' => 'Already on server' ]);
        }
        
        /** @var SyncServer $oldCharacterServer */
        $oldCharacterServer = $repo->findOneBy([ 'name' => $character->getSyncServer() ]);
        /** @var SyncServer $oldAchievementServer */
        $oldAchievementServer = $repo->findOneBy([ 'name' => $character->getSyncServerAchievements() ]);
        /** @var SyncServer $newServer */
        $newServer = $repo->findOneBy([ 'name' => $server ]);
        
        // do things
        $character->setSyncServer($server)->setSyncServerAchievements($server);
        $this->syncServerList->decreaseServerCount($oldCharacterServer);
        $this->syncServerList->decreaseServerCount($oldAchievementServer);
        $this->syncServerList->incrementServerCount($newServer);

        $em->persist($character);
        $em->flush();
        
        return $this->json(1);
    }
}
