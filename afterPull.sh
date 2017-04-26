#!/bin/bash
sudo php artisan command:cacheCommand clear_views
php composer.phar dump-autoload
php artisan generate:config_file_reports
php artisan generate:view_file_menu_reports
