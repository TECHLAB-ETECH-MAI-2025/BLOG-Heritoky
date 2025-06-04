FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libonig-dev libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction

CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]
