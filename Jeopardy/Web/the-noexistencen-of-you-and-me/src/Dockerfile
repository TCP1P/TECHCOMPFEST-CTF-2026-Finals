FROM php:8-apache

COPY ./index.php /var/www/html/index.php
COPY ./php.ini /usr/local/etc/php/conf.d/zzz-jail.ini
COPY ./run.sh ./run.sh

EXPOSE 80

CMD ["sh", "./run.sh"]