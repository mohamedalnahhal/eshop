# Switch to more stable tag (bookworm)
FROM php:8.4-apache-bookworm

# 2. Use German mirror (faster if you are near Europe, otherwise you can remove this)
RUN sed -i 's/deb.debian.org/ftp.de.debian.org/g' /etc/apt/sources.list.d/debian.sources

# Install dependencies and extensions
# Added 'zip' to docker-php-ext-install
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libicu-dev \
    && docker-php-ext-install pdo_mysql zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite module (required for Laravel)
RUN a2enmod rewrite

# Change Apache document root to Laravel public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Copy Composer from the official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Note: The chown command below is fine, but remember that docker-compose 
# volumes usually overwrite permissions when the container starts.
RUN chown -R www-data:www-data /var/www/html