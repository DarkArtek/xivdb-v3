<?php

namespace App\Service\Analytics;

use Carbon\Carbon;
use XIVCommon\Redis\RedisCache;

class Counter
{
    const KEY     = '__stats__';
    const SECONDS = 's';
    const MINUTES = 'i';
    const HOURS   = 'G';
    const DAY     = 'z';
    
    /**
     * @throws \Exception
     */
    public static function track()
    {
        $sec = date(self::SECONDS);
        $min = date(self::MINUTES);
        $hrs = date(self::HOURS);
        $day = date(self::DAY);
        
        $redis = new RedisCache();
        
        $redis->increment(self::KEY . 'sec_'. $sec);
        $redis->increment(self::KEY . 'min_'. $min);
        $redis->increment(self::KEY . 'hrs_'. $hrs);
        $redis->increment(self::KEY . 'day_'. $day);
        
        // reset seconds
        $prev = Carbon::now()->subSeconds(59)->format(self::SECONDS);
        $redis->delete(self::KEY . 'sec_'. $prev);
        
        // reset minutes
        $prev = Carbon::now()->subMinutes(59)->format(self::MINUTES);
        $redis->delete(self::KEY . 'min_'. $prev);
    
        // reset hours
        $prev = Carbon::now()->subHours(23)->format(self::HOURS);
        $redis->delete(self::KEY . 'hrs_'. $prev);
    
        // reset days
        $prev = Carbon::now()->subDays(364)->format(self::DAY);
        $redis->delete(self::KEY . 'day_'. $prev);
        
        // record language (daily)
        $redis->increment(self::KEY . 'day_'. $day .'_'. LANGUAGE);
        $redis->delete(self::KEY . 'day_'. $prev .'_'. LANGUAGE);
    }
}
