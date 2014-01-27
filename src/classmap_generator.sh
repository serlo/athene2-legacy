#!/bin/sh
for D in "module/"*
do
    php vendor/zendframework/zendframework/bin/classmap_generator.php -l "${D}/src" -o "${D}/autoload_classmap.php"
done