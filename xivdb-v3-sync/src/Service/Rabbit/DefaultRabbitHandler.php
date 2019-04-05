<?php

namespace App\Service\Rabbit;

use App\Service\Storage\StorageService;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Style\SymfonyStyle;

class DefaultRabbitHandler
{
    /** @var StorageService */
    public $storage;
    /** @var Connection */
    public $db;
    /** @var SymfonyStyle */
    public $io;
    
    function __construct(
        StorageService $storage,
        Connection $connection
    ) {
        $this->storage = $storage;
        $this->db = $connection;
    }
    
    public function setSymfonyStyle($input, $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }
}
