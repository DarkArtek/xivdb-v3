<?php

namespace App\Entity;

class GrandCompanyEvent extends AbstractEntity
{
    private $gc;
    private $old;
    private $new;

    function __construct($gc, $old, $new)
    {
        $this->gc = $gc;
        $this->old = $old;
        $this->new = $new;
    }

    public function __toString()
    {
        return EventCategory::GRANDCOMPANY . "|{$this->gc}|{$this->old}|{$this->new}";
    }
}