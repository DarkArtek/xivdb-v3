<?php

namespace App\Resources;

/**
 * This is where various JS and CSS resources live, to access them in
 * twig you do:
 *
 *      {{ constant('App\\Resources\\Assets::BOOTSTRAP_CSS') }}
 *
 */
class Assets
{
    // Bootstrap v4 - https://cdnjs.com/libraries/twitter-bootstrap
    const BOOTSTRAP_CSS     = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css';
    const BOOTSTRAP_JS      = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js';
    const BOOTSTRAP_POPPER  = 'https://unpkg.com/popper.js@1.14.3/dist/umd/popper.min.js';
    
    // JQuery
    const JQUERY_JS         = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js';
}
