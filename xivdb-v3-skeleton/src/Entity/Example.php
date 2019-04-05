<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Sdk\Entity\BaseEntityTrait;

/**
 * @ORM\Table(name="ms_model")
 * @ORM\Entity(repositoryClass="App\Repository\ModelRepository")
 */
class Example extends Base
{
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $title;
}
