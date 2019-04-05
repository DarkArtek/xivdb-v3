<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="ms_images")
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    public $id;
    /**
     * @ORM\Column(type="integer")
     */
    public $added;
    /**
     * @ORM\Column(type="integer")
     */
    public $updated;
    /**
     * @ORM\Column(type="string")
     */
    public $idUnique;
    /**
     * @ORM\Column(type="guid")
     */
    public $userId;
    /**
     * @ORM\Column(type="string")
     */
    public $filename;
    /**
     * @ORM\Column(type="string")
     */
    public $filenameThumbnail;
    /**
     * @ORM\Column(type="string")
     */
    public $filenameOriginal;
    /**
     * @ORM\Column(type="string")
     */
    public $directory;
    /**
     * @ORM\Column(type="string")
     */
    public $hash;
    /**
     * @ORM\Column(type="array")
     */
    public $meta = [];
    /**
     * @ORM\Column(type="integer")
     */
    public $views = 0;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->added = time();
        $this->updated = time();
    }

    public function hit()
    {
        $this->views += 1;
        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAdded(): int
    {
        return $this->added;
    }

    public function getUpdated(): int
    {
        return $this->updated;
    }

    public function setUpdated()
    {
        $this->updated = time();
        return $this;
    }

    public function getIdUnique()
    {
        return $this->idUnique;
    }

    public function setIdUnique(string $idUnique)
    {
        $this->idUnique = $idUnique;
        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId(string $userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename(string $filename)
    {
        $this->filename = $filename;
        return $this;
    }

    public function getFilenameThumbnail()
    {
        return $this->filenameThumbnail;
    }

    public function setFilenameThumbnail($filenameThumbnail)
    {
        $this->filenameThumbnail = $filenameThumbnail;
        return $this;
    }

    public function getFilenameOriginal()
    {
        return $this->filenameOriginal;
    }

    public function setFilenameOriginal($filenameOriginal)
    {
        $this->filenameOriginal = $filenameOriginal;
        return $this;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function setDirectory($directory)
    {
        $this->directory = $directory;
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

    public function getMeta()
    {
        return $this->meta;
    }

    public function setMeta($meta)
    {
        $this->meta = $meta;
        return $this;
    }

    public function addMeta($meta)
    {
        $this->meta[] = $meta;
        return $this;
    }
}
