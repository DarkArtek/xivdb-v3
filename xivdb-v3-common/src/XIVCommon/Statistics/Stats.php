<?php

namespace XIVCommon\Statistics;

use XIVCommon\Redis\RedisCache;

class Stats
{
    const REDIS_PREFIX      = 'stats';
    const REDIS_SAVE_KEY    = 'stats_keys';
    const DATE_FORMAT       = 'Y-m-d-h-m';

    private static $timers = [];

    // ---

    /**
     * Get saved stats
     */
    public static function getStats()
    {
        $redis = new RedisCache();
        $keys = $redis->get(self::REDIS_SAVE_KEY);

        $stats = [];
        foreach ($keys as $key => $updated) {
            $stats[$key] = $redis->getCount($key);
        }

        return $stats;
    }

    /**
     * Consistent format for keys
     */
    private static function key($key)
    {
        return self::REDIS_PREFIX . "_{$key}_". date(self::DATE_FORMAT);
    }

    /**
     * Increment the count of a key
     */
    public static function increment($key, $amount = 1)
    {
        $redis = new RedisCache();
        $redis->increment(self::key($key), $amount);
        $redis->append(self::REDIS_SAVE_KEY, self::key($key));
    }

    /**
     * Increment a key
     */
    public static function decrement($key, $amount = 1)
    {
        $redis = new RedisCache();
        $redis->decrement(self::key($key), $amount);
        $redis->append(self::REDIS_SAVE_KEY, self::key($key));
    }

    /**
     * Start a timer
     */
    public static function startTimer($key)
    {
        self::$timers[self::key($key)] = microtime(true);
    }

    /**
     * End a timer
     */
    public static function endTimer($key)
    {
        return microtime(true) - self::$timers[self::key($key)];
    }
}
