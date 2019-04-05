<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * These are xivsync specific servers
 *
 * @ORM\Table(
 *     name="ms_sync_servers",
 *     indexes={
 *          @ORM\Index(name="count", columns={"count"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\SyncServerRepository")
 */
class SyncServer
{
    use EntityTrait;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=32)
     */
    public $name;
    /**
     * @ORM\Column(type="string", length=32)
     */
    public $count;

    function __construct($name)
    {
        $this->name = $name;
        $this->count = 0;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getCount()
    {
        return $this->count;
    }
    
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }
}
