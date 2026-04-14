# 1. Base Image - PHP 8.2 FPM
FROM php:8.2-fpm

# 2. Working Directory
WORKDIR /var/www

# 3. System Dependencies (PostgreSQL માટે libpq-dev ખાસ ઉમેર્યું છે)
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev

# 4. PHP Extensions install (Postgres અને બીજી જરૂરી લાઈબ્રેરીઓ)
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring zip exif pcntl bcmath gd

# 5. Composer Install (Official image માંથી સીધું કોપી)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Project Files Copy કરો
COPY . /var/www

# 7. Composer Dependencies Install (vendor ફોલ્ડરની એરર સોલ્વ કરવા માટે)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 8. Permissions set કરો (Storage અને Cache માટે)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 9. Port Expose (Render નો dynamic port)
EXPOSE 8000

# 10. Startup Command
# આ કમાન્ડ પહેલા માઈગ્રેશન રન કરશે અને પછી સર્વર ચાલુ કરશે
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
