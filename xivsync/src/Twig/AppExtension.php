<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('array_sum', array($this, 'filterArraySum')),
        );
    }

    public function filterArraySum($arr)
    {
        return array_sum($arr);
    }
}