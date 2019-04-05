<?php

namespace App\Entity;

class CollectableEvent extends AbstractEntity
{
    private $type;
    private $id;

    function __construct($type, $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    public function __toString()
    {
        return EventCategory::COLLECTABLE . "|{$this->type}|{$this->id}";
    }
}