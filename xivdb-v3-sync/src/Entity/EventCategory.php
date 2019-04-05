<?php

namespace App\Entity;

/**
 * Categories for the various events
 * - If updated, also needs updating on XIVDB
 */
class EventCategory
{
    public const COLLECTABLE = 'C';
    public const PROFILE = 'P';
    public const GRANDCOMPANY = 'GC';
    public const EXPERIENCE = 'XP';
    public const LEVEL = 'LV';
}