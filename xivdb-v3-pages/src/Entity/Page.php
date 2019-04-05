<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ms_page")
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page extends Base
{
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;
    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected $url;
    /**
     * @ORM\Column(type="text")
     */
    protected $html;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $js;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $css;
    /**
     * @ORM\Column(type="integer")
     */
    protected $views = 0;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $published = false;
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $series;
    
    public function incrementView()
    {
        $this->views++;
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
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function setUrl()
    {
        $this->url = strtolower(trim(str_ireplace(' ', '+', $this->title)));
        return $this;
    }
    
    public function getHtml()
    {
        return $this->html;
    }
    
    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }
    
    public function getJs()
    {
        return $this->js;
    }
    
    public function setJs($js)
    {
        $this->js = $js;
        return $this;
    }
    
    public function getCss()
    {
        return $this->css;
    }
    
    public function setCss($css)
    {
        $this->css = $css;
        return $this;
    }
    
    public function getViews()
    {
        return $this->views;
    }
    
    public function setViews($views)
    {
        $this->views = $views;
        return $this;
    }
    
    public function isPublished()
    {
        return $this->published;
    }
    
    public function setPublished($published)
    {
        $this->published = $published;
        return $this;
    }
    
    public function getSeries()
    {
        return $this->series;
    }
    
    public function setSeries($series)
    {
        $this->series = $series;
        return $this;
    }
}
