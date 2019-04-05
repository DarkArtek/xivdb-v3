# RedisCache Library

> connect(connection[])

Connect to a server, by default it connects to the localhost.

> initPipeline()

Start a RedisPipeline to do batch query

> execPipeline()

Execute any initialized pipeline.

> increment(key, amount = 1)

Increase a key by a specific amount

> decrease(key, amount = 1)

Decrease a key by a specific amount

> set(key, data, ttl = default)

Store some data in redis

> get(key)

Get some data from redis

> getTtl

Get the time to live for the key

> delete(key)

Delete a redis entry

> clear(prefix)

Delete keys based on a prefix, eg: xiv_Achievement_*

> flush()

Delete all keys

> stats()

Get stats of the redis database

> keys (prefix = '*')

Get a list of keys

> keysList(prefix = '*')

Get a list of keys with size and durations

> isCacheDisabled

States if the cache is disabled
