FROM dmstr/php-yii2:7.3-fpm-6.0-rc3-nginx

#change id for www-data user(run by php-fpm) to be the same as on the host
#also it will solve current dev problem:
#shared windows folder can be edited only by user initiated sharing => writing processes in container must run from this user only
RUN usermod -u 1000 www-data \
    && groupmod -g 1000 www-data

RUN docker-php-ext-install pcntl

# Send configuration
COPY files/ /

WORKDIR /app
COPY ./crontab app/config/crontab
COPY cron.conf /etc/supervisor/conf.d/cron.conf
RUN crontab app/config/crontab
# export container environment for cronjobs on container start
CMD supervisord -c /etc/supervisor/supervisord.conf

