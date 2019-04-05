<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ms_comments")
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment extends Base
{
    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $idUnique;
    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $idUser;
    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $idReply;
    /**
     * @ORM\Column(type="string", length=2048)
     */
    protected $message;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $deleted = false;
    
    public function getIdUnique()
    {
        return $this->idUnique;
    }
    
    public function setIdUnique($idUnique)
    {
        $this->idUnique = $idUnique;
        return $this;
    }
    
    public function getIdUser()
    {
        return $this->idUser;
    }
    
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
        return $this;
    }
    
    public function getIdReply()
    {
        return $this->idReply;
    }
    
    public function setIdReply($idReply)
    {
        $this->idReply = $idReply;
        return $this;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
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
}
