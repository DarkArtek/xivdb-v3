# xivdb-v3-screenshots

XIVDB's "Screenshots" microservice.

## Wipe DB (should only be done during test)

```
php bin/console doctrine:database:drop --force
php bin/console doctrine:schema:update --force --dump-sql
```

