<?php

namespace XIVCommon\SEO;

class SEO
{
    const CONTENT = [
        // old
        'item'                      => 'Item',
        'achievement'               => 'Achievement',
        'action'                    => 'Action',
        # 'gathering'               => 'x',
        'instance'                  => 'InstanceContent',
        'leve'                      => 'Leve',
        'fate'                      => 'Fate',
        'enemy'                     => 'BNpcName',
        'emote'                     => 'Emote',
        'placename'                 => 'PlaceName',
        'status'                    => 'Status',
        'title'                     => 'Title',
        'recipe'                    => 'Recipe',
        'quest'                     => 'Quest',
        'shop'                    => 'Shop',
        'special-shop'            => 'SpecialShop',
        'npc'                       => 'ENpcResident',
        'minion'                    => 'Companion',
        'mount'                     => 'Mount',
        'weather'                   => 'Weather',
        
        // new v3 links!
    
    ];
    
    public static function handle($name)
    {
        return self::CONTENT[$name] ?? $name;
    }
}
