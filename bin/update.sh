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
php composer.phar update
cd  src && php public/index.php assetic build
pm2 status
rm data/* -R