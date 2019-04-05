<?php

namespace App\Service\SSO;

class CsrfInvalidException extends \Exception
{
    const TEMPLATE  = 'account/error.csrf.twig';
    const ERROR     = 'Could not confirm the CSRF token from SSO Provider. Please try again.';
}
