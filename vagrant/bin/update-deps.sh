#!/bin/sh

sudo cp /var/athene/dist/solr/data-config.xml /etc/solr/config/data-config.xml
sudo cp /var/athene/dist/solr/data-config.xml /etc/solr/config/data-config.xml
sudo su - root -c "(cd /var/athene/src/assets;npm cache clean)"
sudo su - root -c "cd /home/vagrant/athene2-assets/;npm update"
sudo su - www-data -c "(cd /var/athene/src/assets;bower cache clean)"
sudo su - www-data -c "(cd /var/athene/src/assets;bower update)"
sudo su - root -c "(cd /var/athene/src/assets;grunt build)"
sudo su - www-data -c "(pm2 start /var/athene/src/assets/node_modules/athene2-editor/server/server.js)"
sudo su - www-data -c "(cd /var/athene/;COMPOSER_PROCESS_TIMEOUT=5600 php composer.phar update -o)"