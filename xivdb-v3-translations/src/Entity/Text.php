<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ms_text")
 * @ORM\Entity(repositoryClass="App\Repository\TextRepository")
 */
class Text extends Base
{
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    protected $idString;
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    protected $hash;
    /**
     * @ORM\Column(type="text")
     */
    protected $en;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $de;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $fr;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $ja;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $cn;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $kr;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $notes;
    
    public function getIdString()
    {
        return $this->idString;
    }
    
    public function setIdString($idString)
    {
        $this->idString = $idString;
        
        return $this;
    }
    
    public function getHash()
    {
        return $this->hash;
    }
    
    public function setHash()
    {
        // prevents duplicates
        $this->hash = sha1(strtolower(preg_replace("/[^ \w]+/", "", $this->en)));
        
        return $this;
    }
    
    public function getEn()
    {
        return $this->en;
    }
    
    public function setEn($en)
    {
        $this->en = $en;
        $this->setHash();
        
        return $this;
    }
    
    public function getDe()
    {
        return $this->de;
    }
    
    public function setDe($de)
    {
        $this->de = $de;
        
        return $this;
    }
    
    public function getFr()
    {
        return $this->fr;
    }
    
    public function setFr($fr)
    {
        $this->fr = $fr;
        
        return $this;
    }
    
    public function getJa()
    {
        return $this->ja;
    }
    
    public function setJa($ja)
    {
        $this->ja = $ja;
        
        return $this;
    }
    
    public function getCn()
    {
        return $this->cn;
    }
    
    public function setCn($cn)
    {
        $this->cn = $cn;
        
        return $this;
    }
    
    public function getKr()
    {
        return $this->kr;
    }
    
    public function setKr($kr)
    {
        $this->kr = $kr;
        
        return $this;
    }
    
    public function getNotes()
    {
        return $this->notes;
    }
    
    public function setNotes($notes)
    {
        $this->notes = $notes;
        
        return $this;
    }
}
