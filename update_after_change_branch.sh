#!/bin/bash
rm composer.phar
curl -sS https://getcomposer.org/installer | php
php composer.phar install
php artisan command:cacheCommand clear_all
php artisan generate:config_file_reports
