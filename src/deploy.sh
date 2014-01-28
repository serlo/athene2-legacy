#!/bin/sh
sh classmap_generator.sh
sh templatemap_generator.sh
(cd module/Ui/assets/;pm2 stop server.js)
(cd module/Ui/assets/;npm cache clean)
(cd module/Ui/assets/;npm install)
(cd module/Ui/assets/;npm update)
(cd module/Ui/assets/;pm2 start node_modules/athene2-editor/server/server.js)
(cd module/Ui/assets/;bower cache clean)
(cd module/Ui/assets/;bower install)
(cd module/Ui/assets/;bower update)
(sh hyperdrive.sh)
(cd module/Ui/assets/;grunt build)
(cd ../;php composer.phar self-update)
(cd ../;php composer.phar update)