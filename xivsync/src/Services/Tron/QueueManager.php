<?php

namespace App\Services\Tron;

use App\Entity\Character;
use App\Entity\CharacterPending;
use App\Services\Sync\SyncServerList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use XIVCommon\Redis\RedisCache;

class QueueManager
{
    const REDIS_TIMEOUT = (60 * 30);
    const MAX_ADD = 100;
    const MAX_UPDATE = 100;
    const MAX_FRIENDS = 100;
    const MAX_ACHIEVEMENTS = 30;
    const KEY_ADD = 'characters_add';
    const KEY_UPDATE = 'characters_update_%s';
    const KEY_FRIENDS = 'characters_friends_%s';
    const KEY_ACHIEVEMENTS = 'characters_achievements_%s';
    
    /** @var SymfonyStyle */
    private $io;
    /** @var EntityManagerInterface */
    private $em;
    /** @var RedisCache */
    private $redis;
    /** @var SyncServerList */
    private $sync;
    
    public function __construct(SymfonyStyle $io, EntityManagerInterface $em)
    {
        $this->io    = $io;
        $this->em    = $em;
        $this->redis = new RedisCache();
        $this->sync  = new SyncServerList($this->em);
        
        // switch to serializer
        $this->redis->setOption('useSerializer', true);
        
        $this->queuePendingCharacters();
        $this->io->newLine(2);
        $this->queueUpdateCharacters();
        $this->io->newLine(2);
        $this->queueAchievementCharacters();
        $this->io->newLine(2);
        $this->queueFriendsCharacters();
    }
    
    /**
     * Queue pending characters
     */
    private function queuePendingCharacters()
    {
        $this->io->text(__METHOD__);
        $repo = $this->em->getRepository(CharacterPending::class);
    
        // get characters, sync server and sync achievements server
        $store = [
            $repo->findBy([ 'done' => false ], [ 'added' => 'asc' ], self::MAX_ADD),
            $this->sync->getLowPopulatedServer(SyncServerList::CHARACTERS),
            $this->sync->getLowPopulatedServer(SyncServerList::ACHIEVEMENTS),
        ];
        
        $this->redis->set(self::KEY_ADD, $store, self::REDIS_TIMEOUT);
        
        $this->io->text(sprintf(
            "Added %s characters to the redis queue: %s",
            count($store[0]),
            self::KEY_ADD
        ));
    }
    
    /**
     * Queue update characters
     */
    private function queueUpdateCharacters()
    {
        $this->io->text(__METHOD__);
        $repo = $this->em->getRepository(Character::class);
    
        $servers = array_merge(
            SyncServerList::CHARACTERS,
            SyncServerList::PREMIUM
        );
        
        foreach ($servers as $server) {
            $filter = [
                'syncServer' => $server,
                'deleted' => false,
                'suspend' => false,
            ];
            
            $sort = [
                'lastUpdated' => 'asc',
            ];
            
            $characters = $repo->findBy($filter, $sort, self::MAX_UPDATE);
            $key = sprintf(self::KEY_UPDATE, $server);
            $this->redis->set($key, $characters, self::REDIS_TIMEOUT);
    
            $this->io->text(sprintf(
                "Added %s characters to the redis queue: %s",
                count($characters),
                $key
            ));
        }
    }
    
    /**
     * Queue achievement characters
     */
    private function queueAchievementCharacters()
    {
        $this->io->text(__METHOD__);
        $repo = $this->em->getRepository(Character::class);
    
        $servers = array_merge(
            SyncServerList::ACHIEVEMENTS,
            SyncServerList::PREMIUM
        );
    
        foreach ($servers as $server) {
            $filter = [
                'syncServerAchievements' => $server,
                'deleted' => false,
                'suspend' => false,
            ];
        
            $sort = [
                'lastUpdatedAchievements' => 'asc',
            ];
        
            $characters = $repo->findBy($filter, $sort, self::MAX_ACHIEVEMENTS);
            $key = sprintf(self::KEY_ACHIEVEMENTS, $server);
            $this->redis->set($key, $characters, self::REDIS_TIMEOUT);
        
            $this->io->text(sprintf(
                "Added %s characters to the redis queue: %s",
                count($characters),
                $key
            ));
        }
    }
    
    /**
     * Queue friends
     */
    private function queueFriendsCharacters()
    {
        $this->io->text(__METHOD__);
        $repo = $this->em->getRepository(Character::class);
    
        $servers = array_merge(
            SyncServerList::CHARACTERS,
            SyncServerList::PREMIUM
        );
    
        foreach ($servers as $server) {
            $filter = [
                'syncServer' => $server,
                'deleted' => false,
                'suspend' => false,
            ];
        
            $sort = [
                'lastUpdatedFriends' => 'asc',
            ];
        
            $characters = $repo->findBy($filter, $sort, self::MAX_FRIENDS);
            $key = sprintf(self::KEY_FRIENDS, $server);
            $this->redis->set($key, $characters, self::REDIS_TIMEOUT);
        
            $this->io->text(sprintf(
                "Added %s characters to the redis queue: %s",
                count($characters),
                $key
            ));
        }
    }
}
