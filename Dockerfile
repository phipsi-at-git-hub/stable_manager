# syntax=docker/dockerfile:1.7

# --------------------------------------------------
# PHP base
# --------------------------------------------------
FROM php:8.5-fpm-bookworm AS php-base

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    zip \
    curl \
    libicu-dev \
    libonig-dev \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        gd \
        intl \
        mbstring \
        pcntl \
        pdo_pgsql \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/conf.d/app.ini /usr/local/etc/php/conf.d/99-app.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# --------------------------------------------------
# Official Laravel application skeleton
# --------------------------------------------------
FROM composer:2 AS laravel-scaffold

RUN composer create-project laravel/laravel:^13.0 /opt/laravel \
    --no-interaction \
    --prefer-dist

# --------------------------------------------------
# Resolve PHP dependencies once
# --------------------------------------------------
FROM php-base AS composer-dependencies

WORKDIR /opt/dependencies

COPY composer.json ./

RUN composer update \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --no-progress

# --------------------------------------------------
# Frontend dependencies and production assets
# --------------------------------------------------
FROM node:26-alpine AS frontend-build

WORKDIR /var/www/html

COPY package.json ./

RUN npm install --no-audit --no-fund

COPY . .

RUN npm run build

# --------------------------------------------------
# Development image
# --------------------------------------------------
FROM php-base AS development

ENV APP_ENV=local

COPY --from=laravel-scaffold /opt/laravel /opt/laravel
COPY --from=laravel-scaffold /opt/laravel /var/www/html

COPY . /var/www/html

COPY --from=composer-dependencies /opt/dependencies/vendor /var/www/html/vendor
COPY --from=composer-dependencies /opt/dependencies/composer.lock /opt/stablemanager/composer.lock

COPY docker/php/conf.d/development.ini /usr/local/etc/php/conf.d/zz-development.ini
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint

RUN chmod +x /usr/local/bin/entrypoint \
    && mkdir -p storage/framework/cache storage/framework/sessions \
       storage/framework/views storage/logs bootstrap/cache

ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]

# --------------------------------------------------
# Production PHP image
# --------------------------------------------------
FROM php-base AS production

ENV APP_ENV=production
ENV APP_DEBUG=false

COPY --from=laravel-scaffold /opt/laravel /var/www/html

COPY . /var/www/html

COPY --from=composer-dependencies /opt/dependencies/vendor /var/www/html/vendor

COPY docker/php/conf.d/production.ini /usr/local/etc/php/conf.d/zz-production.ini
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint

RUN chmod +x /usr/local/bin/entrypoint \
    && mkdir -p \
        storage/app/private \
        storage/app/public \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache

ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]

# --------------------------------------------------
# Production web image
# --------------------------------------------------
FROM nginx:1.28-alpine AS web-production

COPY docker/nginx/production.conf /etc/nginx/conf.d/default.conf
COPY --from=production /var/www/html/public /var/www/html/public
COPY --from=frontend-build /var/www/html/public/build /var/www/html/public/build

EXPOSE 80
