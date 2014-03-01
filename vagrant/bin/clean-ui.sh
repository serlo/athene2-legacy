#!/bin/sh

sudo su - www-data -c "pm2 kill"
sudo su - www-data -c "(cd /var/www/src/assets;npm cache clean)"
sudo su - www-data -c "(cd /var/www/src/assets;bower cache clean)"
sudo su - root -c "rm -R /home/vagrant/athene2-assets/node_modules/*"
sudo su - www-data -c "(rm -R /var/www/src/assets/source/bower_components)"
sudo su - root -c "cd /home/vagrant/athene2-assets/;npm install"
sudo su - www-data -c "(cd /var/www/src/assets;bower install)"
sudo su - root -c "(cd /var/www/src/assets;grunt build)"
sudo su - root -c "(pm2 start /var/www/src/assets/node_modules/athene2-editor/server/server.js)"