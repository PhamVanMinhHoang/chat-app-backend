# 1. Base image với PHP 8.2 (tương thích Laravel 11)
FROM php:8.2-fpm

# 2. Cài extension cần thiết
RUN apt-get update && apt-get install -y \
    build-essential \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        locales \
        zip \
        jpegoptim optipng pngquant gifsicle \
        vim unzip git curl \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        libcurl4-openssl-dev \
        && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 3. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

## 4. Tạo user www-data, set working dir
#WORKDIR /var/www
#RUN chown -R www-data:www-data /var/www
#
## 5. Copy mã nguồn và cài dependencies
#COPY src/ /var/www
#RUN composer install --optimize-autoloader --no-dev
#
# 6. Chuyển quyền
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www
#
## 7. Expose và khởi động PHP-FPM
#EXPOSE 9000
#CMD ["php-fpm"]

WORKDIR /var/www
