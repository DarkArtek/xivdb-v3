<?php

namespace App\Service\User;

use Symfony\Component\HttpFoundation\Request;
use XIVCommon\Language\LanguageList;

class LanguageService
{
    /**
     * Initialize language
     */
    public static function init(?Request $request = null)
    {
        $host = $request ? explode('.', $request->getHost()) : [];
        
        if (defined('LANGUAGE')) {
            return;
        }

        // if lang set
        if (count($host) == 3) {
            define(
                'LANGUAGE',
                LanguageList::get($host[0])
            );
            return;
        }
    
        define('LANGUAGE', LanguageList::DEFAULT);
    }
}
