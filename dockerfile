FROM php:8.4-apache

RUN echo "Types: deb\n\
URIs: http://ftp.us.debian.org/debian\n\
Suites: trixie\n\
Components: main\n\
Signed-By: /usr/share/keyrings/debian-archive-keyring.gpg" > /etc/apt/sources.list.d/debian.sources

# Install mysql driver
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_mysql

# Enable Apache rewrite module (required for Laravel)
RUN a2enmod rewrite

# Change Apache document root to Laravel public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html