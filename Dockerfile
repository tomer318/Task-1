FROM php:8.3-cli-alpine

RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    postgresql-dev \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Cấp quyền thực thi cho file entrypoint.sh
RUN chmod +x /var/www/entrypoint.sh

EXPOSE 10000

CMD ["/var/www/entrypoint.sh"]