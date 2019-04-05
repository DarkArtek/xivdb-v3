<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="ms_sync_characters",
 *     indexes={
 *          @ORM\Index(name="autoUpdateCharacter", columns={"sync_server", "last_updated"}),
 *          @ORM\Index(name="autoUpdateFriends", columns={"sync_server", "last_updated_friends"}),
 *          @ORM\Index(name="autoUpdateAchievements", columns={"sync_server_achievements", "last_updated_achievements", "achievements_private"}),
 *          @ORM\Index(name="lastUpdated", columns={"last_updated"}),
 *          @ORM\Index(name="lastSynced", columns={"last_synced"}),
 *          @ORM\Index(name="lastChanged", columns={"last_changed"}),
 *          @ORM\Index(name="syncServer", columns={"sync_server"}),
 *          @ORM\Index(name="syncServerAchievements", columns={"sync_server_achievements"}),
 *          @ORM\Index(name="suspend", columns={"suspend"}),
 *          @ORM\Index(name="deleted", columns={"deleted"}),
 *          @ORM\Index(name="deleteChecks", columns={"delete_checks"}),
 *          @ORM\Index(name="achievementsPrivate", columns={"achievements_private"}),
 *          @ORM\Index(name="achievementsLegacy", columns={"achievements_legacy"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Entity\Repository\CharacterRepository")
 */
class Character
{
    use EntityTrait;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=16, unique=true)
     */
    public $lodestoneId;
    /**
     * @ORM\Column(type="integer")
     */
    public $lastUpdated = 0;
    /**
     * @ORM\Column(type="integer")
     */
    public $lastUpdatedAchievements = 0;
    /**
     * @ORM\Column(type="integer")
     */
    public $lastUpdatedFriends = 0;
    /**
     * @ORM\Column(type="integer")
     */
    public $lastSynced = 0;
    /**
     * @ORM\Column(type="integer")
     */
    public $lastChanged = 0;
    /**
     * @ORM\Column(type="string", length=64)
     */
    public $syncHash;
    /**
     * @ORM\Column(type="string", length=32)
     */
    public $syncServer;
    /**
     * @ORM\Column(type="string", length=32)
     */
    public $syncServerAchievements;
    /**
     * If suspended, it will no longer update
     *
     * @ORM\Column(type="boolean")
     */
    public $suspend = false;
    /**
     * @ORM\Column(type="boolean")
     */
    public $deleted = false;
    /**
     * @ORM\Column(type="smallint")
     */
    public $deleteChecks = 0;
    /**
     * @ORM\Column(type="boolean")
     */
    public $achievementsPrivate = false;
    /**
     * @ORM\Column(type="boolean")
     */
    public $achievementsLegacy = true;
    
    public function __construct($lodestoneId, $syncHash, $syncServer, $syncServerAchievements)
    {
        $this->lodestoneId = $lodestoneId;
        $this->syncHash = $syncHash;
        $this->syncServer = $syncServer;
        $this->syncServerAchievements = $syncServerAchievements;
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
    
    public function getLastUpdatedAchievements()
    {
        return $this->lastUpdatedAchievements;
    }
    
    public function setLastUpdatedAchievements($lastUpdatedAchievements)
    {
        $this->lastUpdatedAchievements = $lastUpdatedAchievements;
        
        return $this;
    }
    
    public function getLastUpdatedFriends()
    {
        return $this->lastUpdatedFriends;
    }
    
    public function setLastUpdatedFriends($lastUpdatedFriends)
    {
        $this->lastUpdatedFriends = $lastUpdatedFriends;
        
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
    
    public function getLastChanged()
    {
        return $this->lastChanged;
    }
    
    public function setLastChanged($lastChanged)
    {
        $this->lastChanged = $lastChanged;
        
        return $this;
    }
    
    public function getSyncHash()
    {
        return $this->syncHash;
    }
    
    public function setSyncHash($syncHash)
    {
        $this->syncHash = $syncHash;
        
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
    
    public function getSyncServerAchievements()
    {
        return $this->syncServerAchievements;
    }
    
    public function setSyncServerAchievements($syncServerAchievements)
    {
        $this->syncServerAchievements = $syncServerAchievements;
        
        return $this;
    }
    
    public function getSuspend()
    {
        return $this->suspend;
    }
    
    public function setSuspend($suspend)
    {
        $this->suspend = $suspend;
        
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
    
    public function getAchievementsPrivate()
    {
        return $this->achievementsPrivate;
    }
    
    public function setAchievementsPrivate($achievementsPrivate)
    {
        $this->achievementsPrivate = $achievementsPrivate;
        
        return $this;
    }
    
    public function getAchievementsLegacy()
    {
        return $this->achievementsLegacy;
    }
    
    public function setAchievementsLegacy($achievementsLegacy)
    {
        $this->achievementsLegacy = $achievementsLegacy;
        
        return $this;
    }
}
