# XIVSYNC

## Auto-Commands

- `php bin/console app:auto <action> <server>`
    - `php bin/console app:auto characters_add any`
    - `php bin/console app:auto characters_update [server]`
    - `php bin/console app:auto characters_delete any`
    - `php bin/console app:auto characters_achievements [server]`
    - `php bin/console app:auto characters_friends [server]`
    - `php bin/console app:auto linkshells_add any`
    - `php bin/console app:auto linkshells_update [server]`
    - `php bin/console app:auto linkshells_delete any`
    - `php bin/console app:auto freecompany_add any`
    - `php bin/console app:auto freecompany_update [server]`
    - `php bin/console app:auto freecompany_delete any`

## Verify working

- Character Adding: 24/4/2018
- Character Updating: 26/4/2018
- Character Achievements: 26/4/2018
- Character Friends + Followers: 26/4/2018
- Character Deleting: 26/4/2018
- Character Suspending (via Controller)
- Character Sync Server change (via Controller)
- Character Sync Premium upgrade (via Controller)
- FreeCompany Adding: 26/4/2018
- FreeCompany Updating: 26/4/2018
- FreeCompany Deleting - todo
- FreeCompany Sync Server change
- Linkshell Adding: 26/4/2018
- Linkshell Updating: 26/4/2018
- Linkshell Deleting - todo
- Linkshell Sync Server Change
- Stats

## Commands

LOCAL 
```sh
# Characters
php bin/console app:auto characters_add any > logs/test.txt
php bin/console app:auto characters_update 11-Characters -vvv > logs/test.txt
php bin/console app:auto characters_delete any > logs/test.txt
php bin/console app:auto characters_friends 11-Characters > logs/test.txt
php bin/console app:auto characters_achievements 11-Achievements > logs/test.txt

# Free Company
php bin/console app:auto freecompany_add x > logs/test.txt
php bin/console app:auto freecompany_update 11-FreeCompany > logs/test.txt

# Linkshell
php bin/console app:auto linkshells_add x > logs/test.txt
php bin/console app:auto linkshells_update 11-Linkshell > logs/test.txt
```

CRONJOB

```sh
# Characters
* * * * * /usr/bin/php /home/xivdb/xivsync/bin/console app:auto characters_update 11-Characters -vvv > /home/xivdb/xivsync/logs/characters_update_11-Characters.txt
```



