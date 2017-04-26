#!/bin/bash
sudo service supervisor force-stop
sudo service beanstalkd force-stop
sudo php artisan command:cacheCommand clear_report
sudo php artisan command:cacheCommand clear_views
sudo php artisan command:cacheCommand clear_files
sudo php artisan command:cacheCommand clear_events
php composer.phar install
php composer.phar dump-autoload
sudo service supervisor start
sudo service beanstalkd start
