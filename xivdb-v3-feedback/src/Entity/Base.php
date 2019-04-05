<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

class Base
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    protected $id;
    /**
     * @ORM\Column(type="integer")
     */
    protected $added;
    /**
     * @ORM\Column(type="integer")
     */
    protected $updated;
    
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->added = time();
        $this->updated = time();
    }
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getAdded(): int
    {
        return $this->added;
    }
    
    public function getUpdated(): int
    {
        return $this->updated;
    }
    
    public function setUpdated()
    {
        $this->updated = time();
        return $this;
    }
}
