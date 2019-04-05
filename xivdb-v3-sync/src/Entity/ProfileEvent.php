<?php

namespace App\Entity;

class ProfileEvent extends AbstractEntity
{
    private $type;
    private $old;
    private $new;

    function __construct($type, $old, $new)
    {
        $this->type = 'profile_'. $type;
        $this->old = $old;
        $this->new = $new;
    }

    public function __toString()
    {
        return EventCategory::PROFILE . "|{$this->type}|{$this->old}|{$this->new}";
    }
}