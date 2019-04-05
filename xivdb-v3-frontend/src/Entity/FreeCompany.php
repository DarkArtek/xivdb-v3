<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="site_free_companies",
 *     indexes={
 *          @ORM\Index(name="name", columns={"name"}),
 *          @ORM\Index(name="server", columns={"server"}),
 *          @ORM\Index(name="updated", columns={"updated"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\FreeCompanyRepository")
 */
class FreeCompany
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=64)
     */
    private $id;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $elastic = false;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $server;
    /**
     * @ORM\Column(type="string", length=256)
     */
    private $crest;
    /**
     * @ORM\Column(type="integer")
     */
    private $updated;
    
    public function url()
    {
        return '/freecompany/'. $this->id .'/'. str_replace(' ', '+', $this->name);
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
    
    public function getElastic()
    {
        return $this->elastic;
    }
    
    public function setElastic($elastic)
    {
        $this->elastic = $elastic;
        return $this;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }
    
    public function getServer(): string
    {
        return $this->server;
    }
    
    public function setServer(string $server)
    {
        $this->server = $server;
        return $this;
    }
    
    public function getCrest(): string
    {
        return $this->crest;
    }
    
    public function setCrest(string $crest)
    {
        $this->crest = $crest;
        return $this;
    }
    
    public function getUpdated()
    {
        return $this->updated;
    }
    
    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }
}
