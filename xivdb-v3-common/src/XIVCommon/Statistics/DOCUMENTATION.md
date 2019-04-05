# Statistics Library

```php
Stats::increment('some_key', $amount = 1);

Stats::decrement('some_key', $amount = 1);
```

Increment and Decrement a key, amount is optional


```php
Stats::startTimer('some_key');

$duration = Stats::endTimer('some_key');
```

Get the duration of a timer


```php
$data = Stats::getStats();
```

Get all stats data
