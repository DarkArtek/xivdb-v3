# xivdb-v3-skeleton

A skeleton for V3 micro-services

Includes:

- Symfony 4
- Symfony Server
- Controller Annotation Reader
- Doctrine + Maker
- Configured for sqlite in `storage/data.db`
- ramsey/uuid


### Start a server

- `php bin/console server:run 127.0.0.1:8444`

#### Entity template

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="ms_page")
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    protected $id;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $added;
    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $updated;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $title;
    
    public function __construct(string $title)
    {
        $this->id = Uuid::uuid4();
        $this->added = time();
        $this->updated = time();
        
        $this->title = $title;
    }
}

```
