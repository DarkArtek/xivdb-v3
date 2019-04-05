<?php

namespace XIVCommon\Redis;

use Redis;

/**
 * Requires: https://github.com/phpredis/phpredis
 */
class RedisCache
{
    // ip, port, password
    const CONNECTION_LOCALHOST = [
        '', 6379, null
    ];
    const CONNECTION_PRODUCTION = [
        '', 6379, ''
    ];
    const CONNECTION_XIVSYNC = [
        '172.105.194.101', 6379, ''
    ];

    const TIMEOUT = 3;
    const COMPRESSION_LEVEL = 5;
    const DEFAULT_TIME = (60 * 60 * 24 * 30);

    /** @var Redis */
    private $instance;
    private $pipeline;

    private $options = [
        'useSerializer' => false,
    ];

    public function __construct()
    {
        if ($this->isCacheDisabled()) {
            return;
        }

        $this->options = (Object)$this->options;

        // automatically connect to localhost
        // todo - this needs handling better
        $provider = self::CONNECTION_LOCALHOST;
        if (getenv('IS_LOCAL') == 0) {
            $provider = self::CONNECTION_PRODUCTION;
        }
        if (getenv('IS_XIVSYNC') == 1) {
            $provider = self::CONNECTION_XIVSYNC;
        }

        $this->connect($provider);
    }

    /**
     * Set an option
     */
    public function setOption($option, $value)
    {
        $this->options->{$option} = $value;
    }

    /**
     * Connect to a server
     */
    public function connect(array $connection)
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        [$ip, $port, $password] = $connection;

        // init Redis
        $this->instance = new Redis();
        $this->instance->pconnect($ip, $port, self::TIMEOUT);
        $this->instance->auth($password);

        // options
        $this->instance->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);
        $this->instance->setOption(Redis::OPT_READ_TIMEOUT, -1);
        return $this;
    }

    /**
     * Start a pipeline
     */
    public function initPipeline()
    {
        if ($this->pipeline) {
            throw new \Exception('You already have a pipeline excuted, did you mean execPipeline??');
        }

        if ($this->isCacheDisabled()) {
            return null;
        }

        $this->pipeline = $this->instance->multi(Redis::PIPELINE);
        return $this;
    }

    /**
     * Execute a pipeline
     */
    public function execPipeline()
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        $this->pipeline->exec();
        $this->pipeline = false;

        return $this;
    }

    /**
     * Migrate a bunch of keys
     */
    public function migrate(array $connection, array $keys, int $destinationDb, int $timeout, ?bool $shouldCopy = true, ?bool $shouldReplace = true)
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        try {
            [$ip, $port, $password] = $connection;
            $this->instance->migrate($ip, $port, $keys, $destinationDb, $timeout, $shouldCopy, $shouldReplace);
        } catch (\Exception $ex) {
            throw $ex;
        }

        return $this;
    }

    /**
     * Increment an object
     */
    public function increment($key, $amount = 1)
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        try {
            $this->instance->incrBy($key, $amount);
        } catch (\Exception $ex) {
            throw $ex;
        }

        return $this;
    }

    /**
     * Decrement an object
     */
    public function decrement($key, $amount = 1)
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        try {
            $this->instance->decrBy($key, $amount);
        } catch (\Exception $ex) {
            throw $ex;
        }

        return $this;
    }

    /**
     * Set an object
     */
    public function set($key, $data, $ttl = self::DEFAULT_TIME)
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        try {
            $data = $this->options->useSerializer
                ? serialize($data)
                : gzcompress(json_encode($data), self::COMPRESSION_LEVEL);

            if (json_last_error()) {
                throw new \Exception("COULD NOT SAVE TO REDIS, JSON ERROR: ". json_last_error_msg());
            }

            if (!$data){
                throw new \Exception('GZCompress Data is empty');
            }

            $this->pipeline
                ? $this->pipeline->set($key, $data, $ttl)
                : $this->instance->set($key, $data, $ttl);

        } catch (\Exception $ex) {
            throw $ex;
        }

        return $this;
    }

    /**
     * Get object for key
     */
    public function get($key)
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        try {
            $data = $this->pipeline
                ? $this->pipeline->get($key)
                : $this->instance->get($key);

            if ($data) {
                $data = $this->options->useSerializer
                    ? unserialize($data)
                    : json_decode(gzuncompress($data));
            }

            return $data;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getCount($key)
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        try {
            return $this->pipeline
                ? $this->pipeline->get($key)
                : $this->instance->get($key);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Append onto an array
     */
    public function append($key, $value)
    {
        $existing = $this->get($key) ?: new \stdClass();
        $existing->{$value} = time();
        $this->set($key, $existing);
    }

    /**
     * Get object ttl for key
     */
    public function getTtl($key)
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        return $this->instance->ttl($key);
    }

    /**
     * Delete an object
     */
    public function delete($key)
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        $this->instance->delete($key);
        return $this;
    }

    /**
     * Remove all objects based on a prefix
     */
    public function clear($prefix)
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        $count = 0;
        $keys = $this->instance->keys($prefix);

        foreach($keys as $key) {
            $this->delete($key);
            $count++;
        }

        return $count;
    }

    /**
     * Flush all objects
     */
    public function flush()
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        $this->instance->flushAll();
        return $this;
    }

    /**
     * Get Redis stats
     */
    public function stats()
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        return [
            'dbSize' => $this->instance ? $this->instance->dbSize() : 0,
            'info' => $this->instance ? $this->instance->info() : 0,
            'lastSave' => $this->instance ? $this->instance->lastSave() : 0,
        ];
    }

    /**
     * Get all keys
     */
    public function keys($prefix = '*')
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        return $this->instance->keys($prefix);
    }

    /**
     * Get all keys with ttl + size
     */
    public function keysList($prefix = '*')
    {
        if ($this->isCacheDisabled()) {
            return null;
        }

        $keys = [];
        foreach($this->instance->keys($prefix) as $key) {
            $keys[$key] = [
                'ttl' => $this->instance->ttl($key),
                'size' => $this->instance->strlen($key),
            ];
        }

        return $keys;
    }

    /**
     * States if the redis cache is disabled
     */
    public function isCacheDisabled()
    {
        return getenv('REDIS_DISABLED');
    }
}
