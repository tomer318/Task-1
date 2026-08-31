FROM php:8.3-cli-alpine

# Cài đặt các thư viện hệ thống cần thiết và extension PostgreSQL / MySQL / GD
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

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy mã nguồn vào container
COPY . .

# Cài đặt dependencies PHP và build Vite JS/CSS
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Phân quyền thư mục lưu trữ
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 10000

# Khởi chạy server Laravel trên cổng PORT do Render cấp
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}