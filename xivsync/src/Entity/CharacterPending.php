<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="ms_sync_characters_pending",
 *     indexes={
 *          @ORM\Index(name="added", columns={"added"}),
 *          @ORM\Index(name="done", columns={"done"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\CharacterPendingRepository")
 */
class CharacterPending
{
    use EntityTrait;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=42, unique=true)
     */
    public $lodestoneId;
    /**
     * @ORM\Column(type="integer")
     */
    public $added = 0;
    /**
     * @ORM\Column(type="boolean")
     */
    public $done = false;
    
    function __construct($lodestoneId)
    {
        $this->lodestoneId = $lodestoneId;
        $this->added = time();
    }
    
    public function getLodestoneId()
    {
        return $this->lodestoneId;
    }
    
    public function setLodestoneId($lodestoneId)
    {
        $this->lodestoneId = $lodestoneId;
        
        return $this;
    }
    
    public function getAdded()
    {
        return $this->added;
    }
    
    public function setAdded($added)
    {
        $this->added = $added;
        
        return $this;
    }
    
    public function getDone()
    {
        return $this->done;
    }
    
    public function setDone($done)
    {
        $this->done = $done;
        
        return $this;
    }
}
