<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ms_dev_apps_note")
 * @ORM\Entity(repositoryClass="App\Repository\AppRepository")
 */
class AppNote extends Base
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;
    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    /**
     * @ORM\ManyToOne(targetEntity="App", inversedBy="notes")
     * @ORM\JoinColumn(name="app_id", referencedColumnName="id")
     */
    protected $app;
    
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
    
    public function getApp()
    {
        return $this->app;
    }
    
    public function setApp($app)
    {
        $this->app = $app;
        return $this;
    }
}
