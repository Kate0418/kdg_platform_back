FROM php:8.3-fpm

WORKDIR /back
COPY ./back /back
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME "/opt/composer"
ENV PATH "$PATH:/opt/composer/vendor/bin"

RUN apt-get update && \
    apt-get -y install git unzip libzip-dev default-mysql-client && \
    docker-php-ext-install zip pdo pdo_mysql && \
    docker-php-ext-enable pdo_mysql

RUN composer install

RUN chown -R www-data:www-data /back \
    && chmod -R 755 /back \
    && chmod -R 775 /back/storage \
    && chmod -R 775 /back/bootstrap/cache \
    && chmod -R 775 /back/public \
    && chmod 640 /back/.env

# 特定のディレクトリの所有者をwww-dataに変更
RUN chown -R www-data:www-data \
    /back/storage \
    /back/bootstrap/cache

EXPOSE 8000
