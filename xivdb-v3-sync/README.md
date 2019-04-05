# XIVDB V3 - Sync Processor

This project manages taking data from the XIVSync RabbitMQ, processing it (generate events, save data, comparison, etc) and storing it. XIVDB can ping it via REST to get character information.

## Commands

- `php bin/console app:auto <action> <queue>`
    - `php bin/console app:auto characters characters_add_1`
    - `php bin/console app:auto achievements`
    - `php bin/console app:auto linkshells`
    - `php bin/console app:auto freecompanies`

## Storage

- `/mnt/volume-sfo2-01/xivsync`

## API

- http://ms.sync.xivdb-staging.com/character/730968