#!/bin/bash
set -e

cd /var/www
env >> /var/www/.env
php artisan clear-compiled
php artisan config:clear
php artisan cache:clear
php artisan storage:link
php-fpm8.1 -D


# Add cron job into cronfile
* * * * * cd /var/www && php artisan schedule:run >> /dev/null 2>&1

# Install cron job
crontab cronfile

# Remove temporary file
rm cronfile

env >> /var/www/.env
php-fpm8.0 -D
# Start cron
cron

nginx -g "daemon off;"
