# Dockerfile (PHP 8.3 FPM)
FROM php:8.3-fpm

# 1. Cài đặt hệ thống
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip curl git nano \
    libssl-dev

# 2. Cài đặt PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 3. Cài đặt Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# 4. Thiết lập cấu hình PHP (upload, post, memory)
RUN { \
    echo 'upload_max_filesize = 20M'; \
    echo 'post_max_size = 20M'; \
    echo 'memory_limit = 256M'; \
} > /usr/local/etc/php/conf.d/uploads.ini

# 5. Đặt thư mục làm việc
WORKDIR /var/www

# 6. Copy mã nguồn vào container
COPY src/. ./

# 7. Cài Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 8. Cài dependencies PHP
RUN composer install --no-dev --optimize-autoloader

# 9. Thiết lập quyền
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# 10. Expose cổng FastCGI và khởi chạy
EXPOSE 9000
CMD ["php-fpm"]
