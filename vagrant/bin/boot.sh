#!/bin/sh

sudo cp /var/www/sphinxql/sphinx.conf.dist /etc/sphinxsearch/sphinx.conf
sudo indexer --all
sudo searchd
sudo su - root -c "(cd /var/www/src/assets;npm cache clean)"
sudo su - root -c "cd /home/vagrant/athene2-assets/;npm update"
sudo su - www-data -c "(cd /var/www/src/assets;bower cache clean)"
sudo su - www-data -c "(cd /var/www/src/assets;bower update)"
sudo su - root -c "(cd /var/www/src/assets;grunt build)"
sudo su - www-data -c "(pm2 start /var/www/src/assets/node_modules/athene2-editor/server/server.js)"
sudo su - www-data -c "(cd /var/www/;COMPOSER_PROCESS_TIMEOUT=2400 php composer.phar install)"
sudo su - www-data -c "(cd /var/www/;COMPOSER_PROCESS_TIMEOUT=2400 php composer.phar update)"
