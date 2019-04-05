# XIVDB V3 VirtualMachine
This is a VM setup for XIVDB on Vagrant.

### Requirements

- Install Vagrant: https://www.vagrantup.com/
- Install Vagrant Host-Manager Plugin: https://github.com/devopsgroup-io/vagrant-hostmanager
- Install Vagrant Cachier: https://github.com/fgrehm/vagrant-cachier
- Install PHP locally: https://windows.php.net/download/
    - VC15 x64 Thread Safe (2018-Mar-28 20:47:30)
    - Example Path: C:\PHP 
- Install Composer locally: https://getcomposer.org/download/

> Note: Auto-installation of vagrant plugins

You can run VagrantInstall.bat and it will automatically install the required plugins as well as do the initial Vagrant Up!

### Starting

**Please run this via Cmder or Windows Bash as it uses Linux-esk commands.**

- `git clone http://xivdb-git.com/xivdb/xivdb-v3-vm.git`
- `vagrant up`
- `bash RepositorySetup` - *If you have already cloned the repositories this is optional*

You should be able to visit:

- http://xivdb.local/
- http://xivdb.adminer/


### Domains:


| Domain | Description | Public/Private |
| -------- | -------- | -------- |
| http://xivdb.local/ | Frontend | Public |
| http://fr.xivdb.local/ | Frontend (French) | Public |
| http://de.xivdb.local/ | Frontend (German) | Public |
| http://ja.xivdb.local/ | Frontend (Japanese) | Public |
| http://ru.xivdb.local/ | Frontend (Russian) | Public |
| http://cn.xivdb.local/ | Frontend (Chinese) | Public |
| http://kr.xivdb.local/ | Frontend (Korean) | Public |
| http://xivdb.adminer/ | Adminer DB Viewer | Public |
| http://mognet.xivdb.local/ | Mognet Discord Bot | Public |
| http://api.xivdb.local/ | API (Routes: Data, Search and Maps) | Public |
| http://tooltips.xivdb.local/ | Tooltips | Public |
| http://maps.xivdb.local/ | Maps | Public |
| http://data.ms.xivdb.local/ | Game Content Data Service | Private |
| http://search.ms.xivdb.local/ | Search Service | Private |
| http://pages.ms.xivdb.local/ | Content Pages Service | Private |
| http://email.ms.xivdb.local/ | Email Service | Private |
| http://devapps.ms.xivdb.local/ | User Developer Apps Service | Private |
| http://comments.ms.xivdb.local/ | Comments Service | Private |
| http://screenshots.ms.xivdb.local/ | Screenshots Service | Private |
| http://translations.ms.xivdb.local/ | Translations Service | Private |
| http://feedback.ms.xivdb.local/ | Feedback Service | Private |
| http://monitor.ms.xivdb.local/ | Monitor Service | Private |


### Site Configurations:

All sites contain a `.env.dist` file, if composer does not copy this to `.env` then you must do it manually. The `.env.dist` will always contain the latest environment configuration for a local environment.

#### MySQL Login:

- user: `xivdb`
- pass: `xivdb`
- table: `xivdb`
