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
apt-get install -y language-pack-de-base

# Add repositories with current versions
sudo add-apt-repository -y ppa:chris-lea/node.js
sudo add-apt-repository -y ppa:ondrej/php5
sudo add-apt-repository -y ppa:muffinresearch/sass-3.2
sudo add-apt-repository -y ppa:muffinresearch/compass
apt-get -y update

# Install php

apt-get install -y libapache2-mod-php5 php5 php5-intl php5-mysql php5-curl php-pear phpmyadmin
apt-get install -y php5-xdebug php5-cli php-apc php-xml-parser

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

echo '
# Listen and start after the vagrant-mounted event
start on vagrant-mounted
stop on runlevel [!2345]

exec /home/vagrant/bin/boot.sh
' > /etc/init/athene2startup.conf

# Xdebug fix
sed -i '$ a\xdebug.max_nesting_level = 500' /etc/php5/apache2/php.ini

ln -s /vagrant/bin/ /home/vagrant/bin

# Enable apache mods
a2enmod rewrite
a2ensite athene2

# Restart apache
service apache2 restart

# Mysql
sudo sed -i "s/bind-address.*=.*/bind-address=0.0.0.0/" /etc/mysql/my.cnf
mysql -u root -proot mysql -e "GRANT ALL ON *.* to root@'%' IDENTIFIED BY 'root'; FLUSH PRIVILEGES;"

# Install sphinxsearch
echo START=yes > /etc/default/sphinxsearch
mkdir /var/lib/sphinxsearch/log

# npm hack
rm /var/www/src/assets/node_modules
sudo su - vagrant -c "mkdir /home/vagrant/athene2-assets/node_modules -p"
sudo su - vagrant -c "ln -s /var/www/src/assets/package.json /home/vagrant/athene2-assets/"
sudo chown vagrant:vagrant /home/vagrant/athene2-assets * -R
ln -s /home/vagrant/athene2-assets/node_modules /var/www/src/assets/node_modules


# Install crontab
echo "* * * * * indexer --all --rotate" > cron
echo "* * * * * cd /var/www/src && php public/index.php notification worker" >> cron
crontab cron
rm cron

chmod +x /home/vagrant/bin/*
sudo /home/vagrant/bin/clean-ui.sh
sudo /home/vagrant/bin/boot.sh
sudo /home/vagrant/bin/update-mysql.sh