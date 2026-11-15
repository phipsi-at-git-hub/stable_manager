#!/usr/bin/env bash
set -Eeuo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT"

write_file() {
    local file="$1"
    mkdir -p "$(dirname "$file")"
    cat > "$file"
    echo "Erstellt: $file"
}

write_file composer.json <<'EOF'
{
    "$schema": "https://getcomposer.org/schema.json",
    "name": "stablemanager/application",
    "type": "project",
    "description": "StableManager - Modular stable and horse management platform",
    "license": "proprietary",
    "require": {
        "php": "^8.5",
        "inertiajs/inertia-laravel": "^2.0",
        "laravel/framework": "^13.0",
        "laravel/tinker": "^3.0"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pail": "^1.2",
        "laravel/pint": "^1.27",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.8",
        "phpunit/phpunit": "^12.5"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "scripts": {
        "test": [
            "@php artisan config:clear",
            "@php artisan test"
        ],
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi"
        ]
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true,
        "allow-plugins": {
            "php-http/discovery": true
        }
    },
    "minimum-stability": "stable",
    "prefer-stable": true
}
EOF

write_file package.json <<'EOF'
{
    "name": "stablemanager",
    "private": true,
    "type": "module",
    "scripts": {
        "dev": "vite",
        "build": "vite build"
    },
    "devDependencies": {
        "@vitejs/plugin-vue": "^6.0.0",
        "laravel-vite-plugin": "^2.0.0",
        "vite": "^7.0.0"
    },
    "dependencies": {
        "@inertiajs/vue3": "^2.0.0",
        "vue": "^3.5.0"
    }
}
EOF

write_file vite.config.ts <<'EOF'
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.ts',
            ],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
    },
});
EOF

write_file resources/css/app.css <<'EOF'
@import "tailwindcss";
EOF

write_file resources/js/app.ts <<'EOF'
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./Modules/**/*.vue', { eager: true });
        const page = pages[`./Modules/${name}.vue`];

        if (!page) {
            throw new Error(`Inertia page not found: ${name}`);
        }

        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
EOF

write_file resources/views/app.blade.php <<'EOF'
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>
EOF

write_file docker/php/entrypoint.sh <<'EOF'
#!/bin/sh
set -eu

cd /var/www/html

mkdir -p \
    storage/app/private \
    storage/app/public \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

exec "$@"
EOF

write_file docker/php/conf.d/app.ini <<'EOF'
expose_php=Off
memory_limit=512M
upload_max_filesize=32M
post_max_size=40M
max_execution_time=120
date.timezone=Europe/Berlin
EOF

write_file docker/php/conf.d/development.ini <<'EOF'
display_errors=On
display_startup_errors=On
error_reporting=E_ALL
opcache.enable=0
EOF

write_file docker/php/conf.d/production.ini <<'EOF'
display_errors=Off
display_startup_errors=Off
log_errors=On
error_reporting=E_ALL
opcache.enable=1
opcache.validate_timestamps=0
opcache.memory_consumption=192
opcache.max_accelerated_files=20000
EOF

write_file docker/php/www.conf <<'EOF'
[www]
user = www-data
group = www-data
listen = 9000
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 20
pm.start_servers = 2
pm.min_spare_servers = 2
pm.max_spare_servers = 5
clear_env = no
catch_workers_output = yes
EOF

write_file docker/nginx/default.conf <<'EOF'
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php;

    charset utf-8;
    client_max_body_size 40M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico {
        access_log off;
        log_not_found off;
    }

    location = /robots.txt {
        access_log off;
        log_not_found off;
    }

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $document_root;
        fastcgi_read_timeout 120;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

write_file docker/nginx/production.conf <<'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php;

    charset utf-8;
    client_max_body_size 40M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|webp|woff2?)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $document_root;
        fastcgi_read_timeout 120;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

write_file Dockerfile <<'EOF'
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
        opcache \
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
EOF

write_file compose.yaml <<'EOF'
name: stablemanager

services:
  bootstrap:
    image: stablemanager-app:development
    build:
      context: .
      dockerfile: Dockerfile
      target: development
    command:
      - sh
      - -c
      - |
        cp -an /opt/laravel/. /workspace/
        cp -n /opt/stablemanager/composer.lock /workspace/composer.lock || true
        mkdir -p /workspace/storage/framework/cache \
                 /workspace/storage/framework/sessions \
                 /workspace/storage/framework/views \
                 /workspace/storage/logs \
                 /workspace/bootstrap/cache
    volumes:
      - .:/workspace
    restart: "no"

  app:
    image: stablemanager-app:development
    build:
      context: .
      dockerfile: Dockerfile
      target: development
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
      - vendor_data:/var/www/html/vendor
      - storage_data:/var/www/html/storage
      - bootstrap_cache:/var/www/html/bootstrap/cache
    env_file:
      - .env
    environment:
      APP_ENV: local
      DB_CONNECTION: pgsql
      DB_HOST: db
      DB_PORT: 5432
      REDIS_HOST: redis
      REDIS_PORT: 6379
    command:
      - sh
      - -c
      - |
        composer install --no-interaction --prefer-dist
        if ! grep -q '^APP_KEY=base64:' .env; then
          php artisan key:generate --force
        fi
        exec php-fpm
    depends_on:
      bootstrap:
        condition: service_completed_successfully
      db:
        condition: service_healthy
      redis:
        condition: service_healthy
    healthcheck:
      test:
        - CMD-SHELL
        - php -r '$$s=@fsockopen("127.0.0.1",9000); exit($$s ? 0 : 1);'
      interval: 10s
      timeout: 5s
      retries: 10
      start_period: 40s
    networks:
      - backend

  web:
    image: nginx:1.28-alpine
    restart: unless-stopped
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html:ro
      - storage_data:/var/www/html/storage:ro
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf:ro
    depends_on:
      app:
        condition: service_healthy
    networks:
      - backend

  vite:
    image: node:26-alpine
    working_dir: /var/www/html
    restart: unless-stopped
    volumes:
      - .:/var/www/html
      - node_modules_data:/var/www/html/node_modules
    ports:
      - "5173:5173"
    command:
      - sh
      - -c
      - |
        npm install --no-audit --no-fund
        npm run dev -- --host 0.0.0.0
    networks:
      - backend

  db:
    image: postgres:18-alpine
    restart: unless-stopped
    environment:
      POSTGRES_DB: ${DB_DATABASE:-stablemanager}
      POSTGRES_USER: ${DB_USERNAME:-stablemanager}
      POSTGRES_PASSWORD: ${DB_PASSWORD:-stablemanager_dev_only}
    ports:
      - "127.0.0.1:5432:5432"
    volumes:
      - postgres_data:/var/lib/postgresql
    healthcheck:
      test:
        - CMD-SHELL
        - pg_isready -U "$$POSTGRES_USER" -d "$$POSTGRES_DB"
      interval: 5s
      timeout: 5s
      retries: 10
    networks:
      - backend

  redis:
    image: redis:8-alpine
    restart: unless-stopped
    command: redis-server --appendonly yes
    volumes:
      - redis_data:/data
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 5s
      timeout: 3s
      retries: 10
    networks:
      - backend

  queue:
    image: stablemanager-app:development
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
      - vendor_data:/var/www/html/vendor
      - storage_data:/var/www/html/storage
      - bootstrap_cache:/var/www/html/bootstrap/cache
    env_file:
      - .env
    environment:
      APP_ENV: local
      DB_CONNECTION: pgsql
      DB_HOST: db
      REDIS_HOST: redis
    command:
      - php
      - artisan
      - queue:work
      - --sleep=3
      - --tries=3
      - --timeout=90
    depends_on:
      app:
        condition: service_healthy
    networks:
      - backend

  scheduler:
    image: stablemanager-app:development
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
      - vendor_data:/var/www/html/vendor
      - storage_data:/var/www/html/storage
      - bootstrap_cache:/var/www/html/bootstrap/cache
    env_file:
      - .env
    environment:
      APP_ENV: local
      DB_CONNECTION: pgsql
      DB_HOST: db
      REDIS_HOST: redis
    command:
      - php
      - artisan
      - schedule:work
    depends_on:
      app:
        condition: service_healthy
    networks:
      - backend

  mailpit:
    image: axllent/mailpit:v1.27
    restart: unless-stopped
    ports:
      - "127.0.0.1:8025:8025"
    networks:
      - backend

networks:
  backend:
    driver: bridge

volumes:
  postgres_data:
  redis_data:
  vendor_data:
  node_modules_data:
  storage_data:
  bootstrap_cache:
EOF

write_file compose.production.yaml <<'EOF'
name: stablemanager

services:
  app:
    image: stablemanager-app:production
    build:
      context: .
      dockerfile: Dockerfile
      target: production
    restart: unless-stopped
    env_file:
      - .env.production.yaml
    environment:
      APP_ENV: production
      APP_DEBUG: "false"
      DB_CONNECTION: pgsql
      DB_HOST: db
      DB_PORT: 5432
      REDIS_HOST: redis
      REDIS_PORT: 6379
    volumes:
      - storage_data:/var/www/html/storage
    depends_on:
      db:
        condition: service_healthy
      redis:
        condition: service_healthy
    healthcheck:
      test:
        - CMD-SHELL
        - php -r '$$s=@fsockopen("127.0.0.1",9000); exit($$s ? 0 : 1);'
      interval: 10s
      timeout: 5s
      retries: 10
      start_period: 30s
    networks:
      - backend

  web:
    image: stablemanager-web:production
    build:
      context: .
      dockerfile: Dockerfile
      target: web-production
    restart: unless-stopped
    ports:
      - "80:80"
    volumes:
      - storage_data:/var/www/html/storage:ro
    depends_on:
      app:
        condition: service_healthy
    networks:
      - backend

  db:
    image: postgres:18-alpine
    restart: unless-stopped
    env_file:
      - .env.production.yaml
    environment:
      POSTGRES_DB: ${DB_DATABASE:?Set DB_DATABASE using --env-file}
      POSTGRES_USER: ${DB_USERNAME:?Set DB_USERNAME using --env-file}
      POSTGRES_PASSWORD: ${DB_PASSWORD:?Set DB_PASSWORD using --env-file}
    volumes:
      - postgres_data:/var/lib/postgresql
    healthcheck:
      test:
        - CMD-SHELL
        - pg_isready -U "$$POSTGRES_USER" -d "$$POSTGRES_DB"
      interval: 5s
      timeout: 5s
      retries: 10
    networks:
      - backend

  redis:
    image: redis:8-alpine
    restart: unless-stopped
    command: redis-server --appendonly yes
    volumes:
      - redis_data:/data
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 5s
      timeout: 3s
      retries: 10
    networks:
      - backend

  queue:
    image: stablemanager-app:production
    restart: unless-stopped
    env_file:
      - .env.production.yaml
    environment:
      APP_ENV: production
      APP_DEBUG: "false"
      DB_CONNECTION: pgsql
      DB_HOST: db
      REDIS_HOST: redis
    volumes:
      - storage_data:/var/www/html/storage
    command:
      - php
      - artisan
      - queue:work
      - --sleep=3
      - --tries=3
      - --timeout=90
    depends_on:
      app:
        condition: service_healthy
    networks:
      - backend

  scheduler:
    image: stablemanager-app:production
    restart: unless-stopped
    env_file:
      - .env.production.yaml
    environment:
      APP_ENV: production
      APP_DEBUG: "false"
      DB_CONNECTION: pgsql
      DB_HOST: db
      REDIS_HOST: redis
    volumes:
      - storage_data:/var/www/html/storage
    command:
      - php
      - artisan
      - schedule:work
    depends_on:
      app:
        condition: service_healthy
    networks:
      - backend

networks:
  backend:
    driver: bridge

volumes:
  postgres_data:
  redis_data:
  storage_data:
EOF

write_file .dockerignore <<'EOF'
.git
.gitignore
.github
.env
.env.*
!.env.example
!.env.docker.example
!.env.production.example

/vendor
/node_modules
/public/build
/public/hot

/storage/logs/*
/storage/framework/cache/*
/storage/framework/sessions/*
/storage/framework/views/*

.phpunit.cache
.phpunit.result.cache
.DS_Store
.idea
.vscode
docker-compose.override.yml
EOF

chmod +x docker/php/entrypoint.sh

echo
echo "Docker-Konfigurationsdateien wurden geschrieben."
echo "Bestehende Modulverzeichnisse wurden nicht gelöscht."
