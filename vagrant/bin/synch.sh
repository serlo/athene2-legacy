sudo rsync -au --delete --stats --exclude=.*/ --exclude=/src/assets/source/ --exclude=/phpmyadmin --exclude=/vagrant /var/athene/ /var/www/
sudo chown www-data:www-data /var/www* -R
sudo chmod 777 /var/www -R