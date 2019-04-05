<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="site_characters",
 *     indexes={
 *          @ORM\Index(name="name", columns={"name"}),
 *          @ORM\Index(name="server", columns={"server"}),
 *          @ORM\Index(name="main", columns={"main"}),
 *          @ORM\Index(name="updated", columns={"updated"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CharacterRepository")
 */
class Character
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="characters")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @ORM\OneToOne(targetEntity="CharacterSettings", inversedBy="character")
     * @ORM\JoinColumn(name="settings_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $settings;
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $main = false;
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
    private $avatar;
    /**
     * @ORM\Column(type="integer")
     */
    private $updated;
    
    public function url()
    {
        return '/character/'. $this->id .'/'. str_replace(' ', '+', $this->name);
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
    
    public function getUser(): ?User
    {
        return $this->user;
    }
    
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
    
    public function getSettings()
    {
        return $this->settings ?: new CharacterSettings();
    }
    
    public function setSettings($settings)
    {
        $this->settings = $settings;
        return $this;
    }
    
    public function getMain()
    {
        return $this->main;
    }
    
    public function setMain($main)
    {
        $this->main = $main;
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
    
    public function getAvatar(): string
    {
        return $this->avatar;
    }
    
    public function setAvatar(string $avatar)
    {
        $this->avatar = $avatar;
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
