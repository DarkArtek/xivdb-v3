<?php

namespace App\Entity;

use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="site_user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;
    /**
     * The name of the SSO provider
     * @ORM\Column(type="string", length=32)
     */
    private $sso;
    /**
     * A random hash saved to cookie to retrieve the token
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $session;
    /**
     * The token provided by the SSO provider
     * @ORM\Column(type="text", length=512, nullable=true)
     */
    private $token;
    /**
     * Username provided by the SSO provider (updates on token refresh)
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $username;
    /**
     * Email provided by the SSO token, this is considered "unique", if someone changes their
     * email then this would in-affect create a new account.
     * @ORM\Column(type="string", length=128)
     */
    private $email;
    /**
     * Either provided by SSO provider or default
     *
     *  DISCORD: https://cdn.discordapp.com/avatars/<USER ID>/<AVATAR ID>.png?size=256
     *
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $avatar;
    /**
     * @ORM\Column(name="is_moderator", type="boolean")
     */
    private $moderator = false;
    /**
     * @ORM\Column(name="is_admin", type="boolean")
     */
    private $admin = false;
    /**
     * @ORM\Column(name="is_star", type="boolean")
     */
    private $star = false;
    /**
     * @ORM\Column(type="integer")
     */
    private $starCharacters = 0;
    /**
     * @ORM\Column(name="is_developer", type="boolean")
     */
    private $dev = false;
    /**
     * @ORM\Column(type="integer")
     */
    private $banned = 0;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;
    /**
     * @ORM\OneToMany(targetEntity="Character", mappedBy="user")
     */
    private $characters;
    
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->session = Uuid::uuid4()->toString() . Uuid::uuid4()->toString() . Uuid::uuid4()->toString();
    
        $this->characters = new ArrayCollection();
    }
    
    public function getCharacterHash()
    {
        return '14DB'. substr(sha1($this->id), 0, 6);
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getSso()
    {
        return $this->sso;
    }
    
    public function getSession()
    {
        return $this->session;
    }
    
    public function getToken()
    {
        return json_decode($this->token);
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function getAvatar()
    {
        $token = $this->getToken();
        
        switch($this->sso) {
            // basic avatar
            default:
                $this->avatar = $this->getAvatarDefault();
                break;

            // Discord Avatar
            case 'discord':
                if (!$token->avatar) {
                    $this->avatar = $this->getAvatarDefault();
                    break;
                }
                
                $this->avatar = sprintf("https://cdn.discordapp.com/avatars/%s/%s.png?size=256",
                    $token->id,
                    $token->avatar
                );
                break;
        }
        
        return $this->avatar;
    }
    
    public function getAvatarDefault()
    {
        $number = preg_replace("/[^0-9]/", null, md5($this->email));
        return "/img/av/avatar_{$number[0]}.png";
    }
    
    public function getNotes()
    {
        return $this->notes;
    }
    
    public function getCharacters()
    {
        return $this->characters;
    }
    
    public function getBanned()
    {
        return $this->banned;
    }
    
    public function getStarCharacters()
    {
        return $this->starCharacters;
    }
    
    public function isBanned()
    {
        return $this->banned > time();
    }
    
    public function isModerator()
    {
        return $this->moderator;
    }
    
    public function isAdmin()
    {
        return $this->admin;
    }
    
    public function isStar()
    {
        return $this->star;
    }
    
    public function isDev()
    {
        return $this->dev;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function setSso($sso)
    {
        $this->sso = $sso;
        return $this;
    }
    
    public function setSession($session)
    {
        $this->session = $session;
        return $this;
    }
    
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
    
    public function setUsername($username)
    {
        $this->username = strtolower($username);
        return $this;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }
    
    public function setModerator($moderator)
    {
        $this->moderator = $moderator;
        return $this;
    }
    
    public function setAdmin($admin)
    {
        $this->admin = $admin;
        return $this;
    }
    
    public function setStar($star)
    {
        $this->star = $star;
        return $this;
    }
    
    public function setStarCharacters($starCharacters)
    {
        $this->starCharacters = $starCharacters;
        return $this;
    }
    
    public function setDev($dev)
    {
        $this->dev = $dev;
        return $this;
    }
    
    public function setBanned($banned)
    {
        $this->banned = $banned;
        return $this;
    }
    
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }
    
    public function setCharacters($characters)
    {
        $this->characters = $characters;
        return $this;
    }
    
    public function addCharacter(Character $character)
    {
        $this->characters[] = $character;
    }
    
    public function mainCharacter()
    {
        /** @var Character $character */
        foreach ($this->characters as $character) {
            if ($character->getMain()) {
                return $character;
            }
        }
        
        return false;
    }
    
    public function subCharacters()
    {
        $temp = [];
        
        /** @var Character $character */
        foreach ($this->characters as $character) {
            if (!$character->getMain()) {
                $temp[] = $character;
            }
        }
        
        return $temp;
    }
}
