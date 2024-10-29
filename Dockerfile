FROM php:8.1-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer
COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application code to the container
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 and start PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]
