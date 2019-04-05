# XIVDB v3 SysOps

This repository can be cloned onto new machines in order to manage and set them up. 

## Getting started

```
apt-get install git dos2unix -y
git clone https://viion@bitbucket.org/ffxiv/xivdb-v3-sysops.git && cd xivdb-v3-sysops
dos2unix bin/* build/* && bash bin/user
```

Login as the new xivdb user and continue with step 2:

```
git clone https://viion@bitbucket.org/ffxiv/xivdb-v3-sysops.git && cd xivdb-v3-sysops
dos2unix bin/* build/* && bash bin/git

bash build/Install<xx>
```

#### Install the different services:

- `bash build/InstallAtla`
- `bash build/InstallKupo`
- `bash build/InstallMojito`
- `bash build/InstallMonty`
- `bash build/InstallMosh`
- `bash build/InstallNoggy`
- `bash build/InstallSuzuna`

#### XIVSync

- `bash build/InstallXIVSyncHome`
- `bash build/InstallXIVSyncParser`

-----

# Servers
### Kupo (Central Database)

- IP: 159.65.107.60
- *Notes: May bump this up to a 2GB for the 50gb of juicy*
- Droplet: (x1) 1GB, 1vCPU, 25GB, 1TB
- Cost: $5
- No MicroSites, just a Database


### Monty

- IP: 159.89.152.241
- *Notes: May spawn multiple of these behind a load balancer*
- Droplet: (x1) 1GB, 1vCPU, 25GB, 1TB
- Cost: $5
- Block Storage: n/a
- MicroSites:
    - Frontend


### Noggy

- IP: 159.65.74.150
- Droplet: (x1) 1GB, 1vCPU, 25GB, 1TB
- Cost: $5
- MicroSites:
    - Mognet
    - Feedback
    - Translations
    - Screenshots
    - Comments
    - Pages
    - Email
    - DevApps


### Mosh

- IP: 138.68.23.104
- *Notes: May spawn multiple of these behind a lode balancer*
- Droplet: (x1) 1GB, 1vCPU, 25GB, 1TB
- Cost: $5
- Block Storage: n/a
- MicroSites:
    - API *(Proxy to: Data, Maps, Tooltips, Sync and Search)*
    

### Mojito

- IP: 138.68.238.182
- Droplet: (x1) 2GB, 1vCPU, 50GB, 2TB
- Cost: $10
- MicroSites:
    - Data *(uses a lot of Memory via Redis)*
    - Maps *(very lightweight)*
    - Tooltips *(Requires a fair amount of space to generate tooltips json)*

### Atla

- IP: 206.189.72.142
- Droplet: (x1) 2GB, 1vCPU, 50GB, 2TB + 100GB Block Storage
- Cost: $10 + (10$ BS)
- MicroSites:
    - Sync *(pulls character data and stores it, provies an REST layer to access it)*


### Suzuna

- IP: 165.227.5.254
- Droplet: (x1) 2GB, 1vCPU, 50GB, 2TB
- Cost: $10
- Block Storage: n/a
- MicroSites:
    - Search *(Stores game content in Elastic)*
    
### *Other*

- IP: 172.105.194.101 (SyncHome) - RabbitMQ + Database Server for XIVSync Servers
- IP: 172.105.197.83  (SyncCharacters1) - Parse Characters: 11, 12, 13, 14

## Microsites with DB Access:

| MicroSite    | Require Access |
| ------------ | -------------- |
| Mognet       | NO             | 
| Feedback     | YES            | 
| Frontend     | YES            | 
| Translations | YES            | 
| Sync         | YES            | 
| Search       | YES            | 
| Screenshots  | YES            | 
| Pages        | YES            | 
| Email        | YES            | 
| DevApps      | YES            | 
| Comments     | YES            | 
| API          | NO             | 
| Tooltips     | NO             | 
| Maps         | YES            | 
| Data         | YES            | 

Microsites not yet created:

- Character Achievement Ranking
- Frontend Homepage Content (Grabs lodestone info, dev posts, latest comments/uploads and other stuff)
- Social Integration (Twitter, Patreon, Paypal, Youtube and Twitch)


-----


##### Usable Names:

Mois
Gumo
Kumop
Mogki
Grimo
Nazna
Mochos
Mone
Mopli
Serino
Moodon
Moonte
Kuppo
Mogmatt
Suzuna
Mogryo
Mocchi
Mimoza
Mooel
Mogsam
Mogrika
Moolan
Mogtaka
Kumool
Moorock
Mozme


