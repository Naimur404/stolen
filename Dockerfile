# syntax=docker/dockerfile:1

##########################################################################
# Stage 1: Build frontend assets with Vite
##########################################################################
FROM node:20-alpine AS assets

WORKDIR /app

# Install JS dependencies using the lockfile for reproducible builds
COPY package.json package-lock.json ./
RUN npm ci

# Copy the files Vite needs, then build production assets into public/build
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build


##########################################################################
# Stage 2: Install PHP dependencies with Composer
#
# We run Composer under PHP 8.2 (not the rolling `composer:2` image, which
# now bundles PHP 8.5 and is incompatible with the locked dependencies).
# Platform requirements are ignored here because the real PHP extensions are
# installed in the runtime stage below.
##########################################################################
FROM php:8.2-cli AS vendor

# Composer needs git + unzip to fetch/extract packages
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
    && rm -rf /var/lib/apt/lists/*

# Pull the Composer binary from the official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install dependencies first (cached) using only the manifest + lock file.
# Scripts/autoload are skipped here because the full app isn't present yet.
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction \
        --no-progress \
        --ignore-platform-reqs

# Copy the rest of the application and finish the autoloader
COPY . .
RUN composer dump-autoload --optimize --no-dev --no-interaction --ignore-platform-reqs


##########################################################################
# Stage 3: Runtime image (PHP 8.2 + Apache)
##########################################################################
FROM php:8.2-apache AS app

# System packages needed by the PHP extensions below
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
        libonig-dev \
        libicu-dev \
        zip \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        zip \
        exif \
        pcntl \
        intl \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Point Apache at Laravel's public directory and enable URL rewriting
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite

# Production-friendly PHP settings
RUN { \
        echo "memory_limit=512M"; \
        echo "upload_max_filesize=64M"; \
        echo "post_max_size=64M"; \
        echo "max_execution_time=120"; \
    } > /usr/local/etc/php/conf.d/zz-app.ini \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html

# Application code + installed PHP dependencies
COPY --chown=www-data:www-data . .
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor

# Compiled frontend assets
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build

# Ensure Laravel's writable directories exist and are owned by the web user
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

EXPOSE 80

ENTRYPOINT ["entrypoint"]
CMD ["apache2-foreground"]
