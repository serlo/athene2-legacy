#!/usr/bin/env bash

# Hands off configuration of mysql-server
echo "mysql-server-5.5 mysql-server/root_password password athene2" | debconf-set-selections
echo "mysql-server-5.5 mysql-server/root_password_again password athene2" | debconf-set-selections

# Hands off configuration of phpmyadmin
echo 'phpmyadmin phpmyadmin/dbconfig-install boolean true' | debconf-set-selections
echo 'phpmyadmin phpmyadmin/app-password-confirm password athene2' | debconf-set-selections
echo 'phpmyadmin phpmyadmin/mysql/admin-pass password athene2' | debconf-set-selections
echo 'phpmyadmin phpmyadmin/mysql/app-pass password athene2' | debconf-set-selections
echo 'phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2' | debconf-set-selections

# Install basic stuff
apt-get -y update
apt-get install -y python-software-properties python g++ make python-software-properties
apt-get install -y apache2 mysql-server-5.5 git sphinxsearch

# Add repositories with current versions
sudo add-apt-repository -y ppa:chris-lea/node.js
sudo add-apt-repository -y ppa:ondrej/php5
sudo add-apt-repository -y ppa:muffinresearch/sass-3.2
sudo add-apt-repository -y ppa:muffinresearch/compass
apt-get -y update

# Install php

apt-get install -y libapache2-mod-php5 php5 php5-intl php5-mysql php5-curl php-pear phpmyadmin php5-xdebug php5-cli

# Install nodejs related stuff

apt-get install -y nodejs
apt-get install -y npm
apt-get install -y ruby-sass 
apt-get install -y ruby-compass
usermod -a -G vagrant www-data

# Install npm dependencies

npm -g install bower
npm -g install grunt
npm -g install grunt-cli
npm -g install pm2
npm -g install dnode

# VirtualHost setup
echo "<VirtualHost *:80>
	ServerAdmin webmaster@localhost
	ServerName  localhost
	SetEnv APPLICATION_ENV \"development\"

	DocumentRoot /var/www/src/public/
	<Directory />
		Options FollowSymLinks
		AllowOverride None
	</Directory>
	<Directory /var/www/src/public/>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</Directory>

	Alias /phpmyadmin /usr/share/phpmyadmin

	<Directory /usr/share/phpmyadmin>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</Directory>
</VirtualHost>" > /etc/apache2/sites-available/athene2.conf

# Xdebug fix
sed -i '$ a\xdebug.max_nesting_level = 500' /etc/php5/apache2/php.ini

# Enable apache mods
a2enmod rewrite
a2ensite athene2

# Restart apache
service apache2 restart

# Remove automatically generated index.html
rm /var/www/index.html

# Mysql stuff
mysql -u root --password="athene2" < /var/www/vagrant/dump.sql

# Install sphinxsearch
echo START=yes > /etc/default/sphinxsearch
cp /var/www/sphinxql/sphinx.conf.dist /etc/sphinxsearch/sphinx.conf
mkdir /var/lib/sphinxsearch/log
indexer --all
searchd

# Install crontab
#echo new cron into cron file
echo "* * * * * indexer --all --rotate" >> sphinxcron
#install new cron file
crontab sphinxcron
rm sphinxcron

# Permission stuff
#chown www-data:vagrant /var/www/src/data -R
#chown www-data:vagrant /var/www/src/public/assets -R
#chmod 775 /var/www/src/data -R
#chmod 775 /var/www/src/public/assets -R

# Run athene2 startup scripts
su - www-data -c "cd /var/www/src/module/Ui/assets && npm install --no-bin-links"
su - www-data -c "cd /var/www/src/module/Ui/assets && npm update --no-bin-links"
su - www-data -c "cd /var/www/src/module/Ui/assets && bower install"
su - www-data -c "pm2 start /var/www/src/module/Ui/assets/node_modules/athene2-editor/server/server.js"
su - www-data -c "cd /var/www/ && php composer.phar self-update"
su - www-data -c "cd /var/www/ && COMPOSER_PROCESS_TIMEOUT=900 php composer.phar install"
su - www-data -c "cd /var/www/ && COMPOSER_PROCESS_TIMEOUT=900 php composer.phar update"
su - www-data -c "cd /var/www/src/module/Ui/assets && grunt language-update"
su - www-data -c "cd /var/www/src/module/Ui/assets && grunt build"
