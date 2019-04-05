<?php

namespace App\Controller;

use App\Service\Analytics\Counter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use XIVCommon\Language\LanguageList;
use XIVCommon\Redis\RedisCache;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard/stats", name="dashboard_stats")
     */
    public function stats()
    {
        $redis = new RedisCache();
        
        $sec  = [];
        $min  = [];
        $hrs  = [];
        $day  = [];
        $lang = [];
        
        foreach (range(0,59) as $i) {
            $sec[$i] = $redis->getCount(Counter::KEY . "sec_{$i}") ?: 0;
            $min[$i] = $redis->getCount(Counter::KEY . "min_{$i}") ?: 0;
        }
    
        foreach (range(0,23) as $i) {
            $hrs[$i] = $redis->getCount(Counter::KEY . "hrs_{$i}") ?: 0;
        }
    
        foreach (range(0,365) as $i) {
            $day[$i] = $redis->getCount(Counter::KEY . "day_{$i}") ?: 0;
            
            foreach(LanguageList::LANGUAGES as $locale) {
                if (!isset($lang[$locale])) {
                    $lang[$locale] = [];
                }
    
                $lang[$locale][$i] = $redis->getCount(Counter::KEY . "day_{$i}_{$locale}") ?: 0;
            }
        }
        
        return $this->render('dashboard/stats.twig', [
            'Stats' => [
                'sec'  => $sec,
                'min'  => $min,
                'hrs'  => $hrs,
                'day'  => $day,
                'lang' => $lang
            ]
        ]);
    }
}
