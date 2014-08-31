#!/bin/sh
git status
git pull -ff
sh build.sh
pm2 kill
cd ../src/assets/
npm update
pm2 start node_modules/athene2-editor/server/server.js
bower update
grunt build
cd ../../
cd src
php public/index.php assetic build
rm data/twig data/zfc* data/*.php data/*.cache -Rf
pm2 status
cd ../
php composer.phar update -o
rm data/*.php -Rf
php src/public/index.php pagespeed build