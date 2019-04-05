<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ms_email")
 * @ORM\Entity(repositoryClass="App\Repository\EmailRepository")
 */
class Email extends Base
{
    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected $email;
    /**
     * @ORM\Column(type="integer")
     */
    protected $totalEmailsSent = 0;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $subscribed = true;
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    protected $hash;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getTotalEmailsSent()
    {
        return $this->totalEmailsSent;
    }

    public function setTotalEmailsSent($totalEmailsSent)
    {
        $this->totalEmailsSent = $totalEmailsSent;

        return $this;
    }

    public function getSubscribed()
    {
        return $this->subscribed;
    }

    public function setSubscribed($subscribed)
    {
        $this->subscribed = $subscribed;

        return $this;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }
}
