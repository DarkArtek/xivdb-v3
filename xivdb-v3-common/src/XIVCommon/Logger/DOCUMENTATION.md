# Logger Library

```php
Logger::info('group', 'message', $data = []);

Logger::debug('group', 'message', $data = []);

Logger::error('group', 'message', $data = []);
```

Write some messages


```php
$logs = Logger::read('info', 'group);

$logs = Logger::readAll();
```

Delete messages

```php
Stats::cleanup();
```

