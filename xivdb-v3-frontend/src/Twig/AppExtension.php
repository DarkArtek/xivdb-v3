<?php

namespace App\Twig;

use Carbon\Carbon;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('dateRelative', [$this, 'getDateRelative']),
            new TwigFilter('fileHash', [$this, 'getFileHash']),
        ];
    }
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('gitVersion', [$this, 'getGitVersion']),
            new \Twig_SimpleFunction('gitHash', [$this, 'getGitHash'])
        ];
    }
    
    public function getDateRelative($unix)
    {
        $difference = time() - $unix;
        
        // if over 72hrs, show date
        if ($difference > (60 * 60 * 72)) {
            return date('M jS', $unix);
        }
        
        return Carbon::now()->subSeconds($difference)->diffForHumans();
    }
    
    public function getGitVersion()
    {
        return (int)trim(file_get_contents(__DIR__.'/../../git_version.txt'));
    }
    
    public function getGitHash()
    {
        return substr(trim(file_get_contents(__DIR__.'/../../git_hash.txt')), 0, 6);
    }
}
