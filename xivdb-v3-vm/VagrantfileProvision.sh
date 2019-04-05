#!/usr/bin/env bash

#
# Colouring
#

reset='\e[39m'
blue='\e[96m'
green='\e[92m'
magenta='\e[95m'
yellow='\e[93m'

# Define your function here
Heading () {
    echo -e "$blue ---------------------------------------------- $reset"
    echo -e "$blue $1 $reset"
    echo -e "$blue ---------------------------------------------- $reset"
}

Title() {
    echo -e "$yellow $1 $reset"
}

Text() {
    echo -e "$blue - $1 $reset"
}

Info() {
    echo -e "$magenta -- $1 $reset"
}

Complete() {
    echo -e "$reset -- COMPLETE --------------------------------------------- $reset"
}

Gap() {
    echo -e "   "
}

#
# Start setting up local environment
#

Gap
Gap

Heading "Creating XIVDB Development Environment"
Title "Set variables"
cd /vagrant &> /dev/null
USER=vagrant &> /dev/null
sudo locale-gen en_GB.UTF-8 &> /dev/null
Title "Updating Ubuntu packages and running upgrades ..."
sudo apt-get update -y -qq &> /dev/null
sudo apt-get upgrade -y -qq &> /dev/null
Title "Installing python software properties and common packages ..."
sudo apt-get install -y python-software-properties &> /dev/null
sudo apt-get install -y software-properties-common &> /dev/null
Complete

#
# Custom Apps: htop, unzip, curl and git
#

Title "Installing: Htop, Unzip, Curl and Git"
sudo apt-get install -y -qq acl htop unzip curl git &> /dev/null

#
# NGINX
#

Title "Install NGINX"
Text "Set nginx repository"
sudo add-apt-repository ppa:nginx/stable -y &> /dev/null
Text "Updating ..."
sudo apt-get update &> /dev/null
Text "Installing nginx stable"
sudo apt-get install -y nginx &> /dev/nul
Text "Moving nginx configs"
rm /etc/nginx/sites-available/default &> /dev/null
sudo cp /vagrant/VagrantfileNginxCommon /etc/nginx/sites-available/common &> /dev/null
sudo cp /vagrant/VagrantfileNginxDefault /etc/nginx/sites-available/default &> /dev/null
sudo cp /vagrant/VagrantfileNginx.conf /etc/nginx/nginx.conf &> /dev/null
sed -i "s|user www-data|user $USER|" /etc/nginx/nginx.conf
Complete

#
# PHP + Composer
#

Title "Installing: PHP 7 + Modules ..."
Text "Set php repository"
sudo add-apt-repository ppa:ondrej/php -y &> /dev/null
Text "Updating"
sudo apt-get update -y &> /dev/null
Text "Installing PHP"
sudo apt-get install -y -qq php7.2-fpm &> /dev/null
Text "Installing PHP modules"
sudo apt-get install -y -qq php-apcu php7.2-dev php7.2-cli php7.2-tidy php7.2-json php7.2-fpm php7.2-intl php7.2-mysql php7.2-sqlite php7.2-curl php7.2-gd php7.2-mbstring php7.2-dom php7.2-xml php7.2-zip php7.2-tidy php7.2-bcmath &> /dev/null

# change some settings
Title "Adjusting PHP settings"
sudo sed -i 's|display_errors = Off|display_errors = On|' /etc/php/7.2/fpm/php.ini
sudo sed -i 's|upload_max_filesize = 2M|upload_max_filesize = 512M|' /etc/php/7.2/fpm/php.ini
sudo sed -i 's|post_max_size = 8M|post_max_size = 512M|' /etc/php/7.2/fpm/php.ini
sudo sed -i 's|max_execution_time = 30|max_execution_time = 1500|' /etc/php/7.2/fpm/php.ini
sudo sed -i 's|memory_limit = 128M|memory_limit = 8G|' /etc/php/7.2/fpm/php.ini
sudo sed -i 's|;request_terminate_timeout = 0|request_terminate_timeout = 300|' /etc/php/7.2/fpm/pool.d/www.conf
sudo sed -i "s|www-data|$USER|" /etc/php/7.2/fpm/pool.d/www.conf

# composer
Title "Install: Composer ..."
curl -sS https://getcomposer.org/installer | php &> /dev/null
mv composer.phar /usr/local/bin/composer &> /dev/null
chmod +x /usr/local/bin/composer &> /dev/null
Info "Ready for composer install at anytime"
Complete

#
# MySQL
#

Title "Install: MariaDB ..."
echo "mysql-server mysql-server/root_password password xivdb" | debconf-set-selections &> /dev/null
echo "mysql-server mysql-server/root_password_again password xivdb" | debconf-set-selections &> /dev/null
sudo apt-get install software-properties-common &> /dev/null
sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8
sudo add-apt-repository 'deb [arch=amd64] http://mirrors.coreix.net/mariadb/repo/10.3/ubuntu bionic main'
sudo apt-get update &> /dev/null
sudo apt-get install mariadb-server -y -qq  &> /dev/null

# settings
Text "Setup mysql configuration ..."
sed -i 's|max_connections         = 100|max_connections         = 300|' /etc/mysql/my.cnf &> /dev/null
sed -i 's|slow_query_log_file     =|#slow_query_log_file     =|' /etc/mysql/my.cnf &> /dev/null
sed -i 's|long_query_time =|#long_query_time =|' /etc/mysql/my.cnf &> /dev/null
sed -i 's|log_bin                 =|#log_bin                 =|' /etc/mysql/my.cnf &> /dev/null
sed -i 's|log_bin_index           =|#log_bin_index           =|' /etc/mysql/my.cnf &> /dev/null
sed -i 's|expire_logs_days        =|#expire_logs_days        =|' /etc/mysql/my.cnf &> /dev/null

# default database
Text "Create default database and user ..."
mysql -uroot -pxivdb < /vagrant/VagrantfileMysqlSetup.sql
Info "user xivdb, pass: xivdb"
Complete

#
# Redis
#

Title "Installing: Redis"
sudo apt-get install redis-server -y -qq  &> /dev/null

Title "Installing PHP Redis"
git clone https://github.com/phpredis/phpredis.git &> /dev/null
cd phpredis &> /dev/null
phpize &> /dev/null
./configure &> /dev/null
make &> /dev/null
sudo make install &> /dev/null
cd ..
rm -rf phpredis &> /dev/null
sudo echo "extension=redis.so" > /etc/php/7.2/mods-available/redis.ini
sudo ln -sf /etc/php/7.2/mods-available/redis.ini /etc/php/7.2/fpm/conf.d/20-redis.ini &> /dev/null
sudo ln -sf /etc/php/7.2/mods-available/redis.ini /etc/php/7.2/cli/conf.d/20-redis.ini &> /dev/null
sudo service php7.2-fpm restart &> /dev/null
Complete

#
# Install JAVA + ElasticSearch
#

Title "Installing: Java 8"
sudo add-apt-repository -y ppa:webupd8team/java &> /dev/null
sudo apt-get update &> /dev/null
echo debconf shared/accepted-oracle-license-v1-1 select true | \sudo debconf-set-selections
echo debconf shared/accepted-oracle-license-v1-1 seen true | \sudo debconf-set-selections
sudo sudo apt-get -y install oracle-java8-installer &> /dev/null
export _JAVA_OPTIONS="-Xmx1024m -Xms1024m" &> /dev/null

Title "Install: Elastic Search 5"
sudo apt install openjdk-8-jre apt-transport-https
sudo wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | sudo apt-key add -
sudo echo /etc/apt/sources.list.d/elastic.list > deb https://artifacts.elastic.co/packages/6.x/apt stable main
sudo apt update
sudo apt install elasticsearch kibana
sudo systemctl restart kibana
sudo systemctl start elasticsearch
echo "admin:`openssl passwd -apr1 MaNlmfOzwAPIzxGH7KSV`" | sudo tee -a /etc/nginx/htpasswd.kibana
sudo apt install logstash
Complete

#
# Finish
#

Title "Setting up symfony cache and log directories"

sudo mkdir -p /xivdb/log /xivdb/cache /xivdb/session
sudo chown vagrant:vagrant /xivdb/log /xivdb/cache /xivdb/session
sudo chmod -R 777 /xivdb/log /xivdb/cache /xivdb/session

Title "Restart services ..."
sudo service nginx restart &> /dev/null
sudo service php7.2-fpm restart &> /dev/null

Title "Cleaning up"
sudo apt-get autoremove -y -qq &> /dev/null
sudo apt-get update -y -qq &> /dev/null
sudo apt-get upgrade -y -qq &> /dev/null

#
# Information
#

Complete
Title "Finished"
Info "If not already done, run: bash RepositorySetup"
Info "This will clone all XIVDB repositories and put them into the sites folder for you"
Info "Once complete, you should be able to view: http://xivdb.local/"
Gap
