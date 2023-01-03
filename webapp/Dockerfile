FROM ubuntu:22.04

# Install dependencies
env DEBIAN_FRONTEND=noninteractive
RUN apt-get update; apt-get install -y --no-install-recommends libpq-dev vim nginx php8.1-fpm php8.1-mbstring php8.1-xml php8.1-pgsql php8.1-curl ca-certificates cron
# Copy project code and install project dependencies
COPY --chown=www-data . /var/www/

# Copy project configurations
COPY ./etc/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./etc/nginx/default.conf /etc/nginx/sites-enabled/default
COPY .env /var/www/.env
COPY docker_run.sh /docker_run.sh

RUN crontab -l | { echo "* * * * * cd /var/www && php artisan schedule:run >> /dev/null"; } | crontab -

# Run the command on container startup
CMD cron

# Start command
CMD sh /docker_run.sh

