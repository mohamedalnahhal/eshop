# Switch to more stable tag (bookworm)
FROM php:8.4-apache-bookworm

# 2. Use German mirror (faster)
RUN sed -i 's/deb.debian.org/ftp.de.debian.org/g' /etc/apt/sources.list.d/debian.sources

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libicu-dev \
    && docker-php-ext-install pdo_mysql \
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

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html