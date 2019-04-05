<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="ms_dev_apps")
 * @ORM\Entity(repositoryClass="App\Repository\AppRepository")
 */
class App extends Base
{
    /**
     * @ORM\Column(type="string")
     */
    protected $userId;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;
    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $icon;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $url;
    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    protected $language;
    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    protected $device;
    /**
     * @ORM\OneToMany(targetEntity="AppNote", mappedBy="app")
     */
    protected $notes;
    /**
     * @ORM\Column(type="array")
     */
    protected $keys = [];

    public function __construct()
    {
        parent::__construct();
        
        $this->notes = new ArrayCollection();
    }
    
    public function data()
    {
        return [
            'title'         => $this->title,
            'icon'          => $this->icon,
            'description'   => $this->description,
            'url'           => $this->url,
            'language'      => $this->language,
            'device'        => $this->device,
            'keys'          => $this->keys
        ];
    }
    
    public function getUserId()
    {
        return $this->userId;
    }
    
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    public function getIcon()
    {
        return $this->icon;
    }
    
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
    
    public function getLanguage()
    {
        return $this->language;
    }
    
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }
    
    public function getDevice()
    {
        return $this->device;
    }
    
    public function setDevice($device)
    {
        $this->device = $device;
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

    public function getKeys()
    {
        return $this->keys;
    }

    public function setKeys($keys)
    {
        $this->keys = $keys;
        return $this;
    }

    public function addKey(array $key)
    {
        $this->keys[] = $key;
        return $this;
    }
}
