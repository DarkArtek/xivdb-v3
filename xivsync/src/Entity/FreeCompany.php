<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="ms_sync_freecompanies",
 *     indexes={
 *          @ORM\Index(name="autoUpdateFreeCompany", columns={"sync_server", "last_updated"}),
 *          @ORM\Index(name="lastUpdated", columns={"last_updated"}),
 *          @ORM\Index(name="lastSynced", columns={"last_synced"}),
 *          @ORM\Index(name="syncServer", columns={"sync_server"}),
 *          @ORM\Index(name="deleted", columns={"deleted"}),
 *          @ORM\Index(name="deleteChecks", columns={"delete_checks"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\FreeCompanyRepository")
 */
class FreeCompany
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
    public $lastUpdated = 0;
    /**
     * @ORM\Column(type="integer")
     */
    public $lastSynced = 0;
    /**
     * @ORM\Column(type="string", length=32)
     */
    public $syncServer;
    /**
     * @ORM\Column(type="boolean")
     */
    public $deleted = false;
    /**
     * @ORM\Column(type="smallint")
     */
    public $deleteChecks = 0;
    
    public function __construct($lodestoneId, $syncServer)
    {
        $this->lodestoneId = $lodestoneId;
        $this->syncServer = $syncServer;
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
    
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }
    
    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = $lastUpdated;
        
        return $this;
    }
    
    public function getLastSynced()
    {
        return $this->lastSynced;
    }
    
    public function setLastSynced($lastSynced)
    {
        $this->lastSynced = $lastSynced;
        
        return $this;
    }
    
    public function getSyncServer()
    {
        return $this->syncServer;
    }
    
    public function setSyncServer($syncServer)
    {
        $this->syncServer = $syncServer;
        
        return $this;
    }
    
    public function getDeleted()
    {
        return $this->deleted;
    }
    
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        
        return $this;
    }
    
    public function getDeleteChecks()
    {
        return $this->deleteChecks;
    }
    
    public function setDeleteChecks($deleteChecks)
    {
        $this->deleteChecks = $deleteChecks;
        
        return $this;
    }
}
