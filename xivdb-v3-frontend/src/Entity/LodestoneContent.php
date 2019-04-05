<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(
 *     name="site_lodestone_content",
 *     indexes={
 *          @ORM\Index(name="time", columns={"time"}),
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\LodestoneContentRepository")
 */
class LodestoneContent
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=64)
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $hash;
    /**
     * @ORM\Column(type="string", length=32)
     */
    private $type;
    /**
     * @ORM\Column(type="integer")
     */
    private $time;
    /**
     * @ORM\Column(type="text")
     */
    private $data;
    
    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function getHash()
    {
        return $this->hash;
    }
    
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    
    public function getTime()
    {
        return $this->time;
    }
    
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }
    
    public function getData()
    {
        return json_decode($this->data);
    }
    
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
