<?php

namespace App\Services;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

use Lodestone\Api;
use App\Entity\Statistic;
use App\Services\Sync\SyncServerList;
use App\Services\Rabbit\Rabbit;
use XIVCommon\Redis\RedisCache;

/**
 * @package App\Services
 */
class Service
{
    /** @var SymfonyStyle */
    public $io;
    /** @var KernelInterface */
    public $kernal;
    /** @var EntityManagerInterface $manager */
    public $manager;
    /** @var ContainerInterface $container */
    public $container;
    /** @var Rabbit */
    public $rabbit;
    /** @var SyncServerList */
    public $syncServerList;
    /** @var Api */
    public $api;
    /** @var RedisCache */
    public $redis;

    function __construct(
        KernelInterface $kernel,
        EntityManagerInterface $entityManager,
        ContainerInterface $container,
        Rabbit $rabbit,
        SyncServerList $syncServerList
    ) {
        $this->kernal = $kernel;
        $this->manager = $entityManager;
        $this->container = $container;
        $this->rabbit = $rabbit;
        $this->syncServerList = $syncServerList;
        $this->api = new Api();

        // connect to XIVSYNC redis and enable serializer
        $this->redis = new RedisCache();
        $this->redis->setOption('useSerializer', true);
        $this->init();
    }

    public function setSymfonyStyle(SymfonyStyle $io)
    {
        $this->io = $io;
    }

    /**
     * Check if a cronjob should finish.
     */
    public function checkActionTimeout()
    {
        if (time() - $this->time >= 58) {
            $this->io->text('Finished due to running out of time');
            return true;
        }

        return false;
    }

    /**
     * Increment some stats
     */
    public function incrementStats(string $name)
    {
        $key = 'stats,'. $name .','. date('Y-m-d H:i');
        
        // increment count
        $count = $this->redis->get($key) ?: 0;
        $count++;
        
        // record for 3 days
        $this->redis->set($key, $count, RedisCache::DEFAULT_TIME * 240 * 3);
    }
}
