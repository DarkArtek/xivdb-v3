<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="site_characters_settings")
 * @ORM\Entity(repositoryClass="App\Repository\CharacterSettingsRepository")
 */
class CharacterSettings
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="Character", mappedBy="settings")
     */
    private $character;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $hidden = false;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $hiddenClassJobs = false;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $hiddenGearsets = false;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $hiddenEvents = false;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $hiddenTracking = false;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $hiddenCollectables = false;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $hiddenAchievements = false;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $hiddenFriends = false;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $stopUpdating = false;
    
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
    
    public function getCharacter()
    {
        return $this->character;
    }
    
    public function setCharacter($character)
    {
        $this->character = $character;
        return $this;
    }
    
    public function isHidden()
    {
        return $this->hidden;
    }
    
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }
    
    public function getHiddenClassJobs()
    {
        return $this->hiddenClassJobs;
    }
    
    public function setHiddenClassJobs($hiddenClassJobs)
    {
        $this->hiddenClassJobs = $hiddenClassJobs;
        return $this;
    }
    
    public function getHiddenGearsets()
    {
        return $this->hiddenGearsets;
    }
    
    public function setHiddenGearsets($hiddenGearsets)
    {
        $this->hiddenGearsets = $hiddenGearsets;
        return $this;
    }
    
    public function getHiddenEvents()
    {
        return $this->hiddenEvents;
    }
    
    public function setHiddenEvents($hiddenEvents)
    {
        $this->hiddenEvents = $hiddenEvents;
        return $this;
    }
    
    public function getHiddenTracking()
    {
        return $this->hiddenTracking;
    }
    
    public function setHiddenTracking($hiddenTracking)
    {
        $this->hiddenTracking = $hiddenTracking;
        return $this;
    }
    
    public function getHiddenCollectables()
    {
        return $this->hiddenCollectables;
    }
    
    public function setHiddenCollectables($hiddenCollectables)
    {
        $this->hiddenCollectables = $hiddenCollectables;
        return $this;
    }
    
    public function getHiddenAchievements()
    {
        return $this->hiddenAchievements;
    }
    
    public function setHiddenAchievements($hiddenAchievements)
    {
        $this->hiddenAchievements = $hiddenAchievements;
        return $this;
    }
    
    public function getHiddenFriends()
    {
        return $this->hiddenFriends;
    }
    
    public function setHiddenFriends($hiddenFriends)
    {
        $this->hiddenFriends = $hiddenFriends;
        return $this;
    }
    
    public function getStopUpdating()
    {
        return $this->stopUpdating;
    }
    
    public function setStopUpdating($stopUpdating)
    {
        $this->stopUpdating = $stopUpdating;
        return $this;
    }
}
