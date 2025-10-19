FROM mcr.microsoft.com/devcontainers/php:8.2-apache

# Enable useful PHP extensions
RUN install-php-extensions pdo pdo_mysql mysqli gd intl mbstring opcache

# Configure Apache docroot to /workspace
ARG APACHE_DOCUMENT_ROOT=/workspace
RUN sed -ri "s#DocumentRoot /var/www/html#DocumentRoot ${APACHE_DOCUMENT_ROOT}#g" /etc/apache2/sites-available/000-default.conf \
 && sed -ri "s#<Directory /var/www/>#<Directory ${APACHE_DOCUMENT_ROOT}>#g" /etc/apache2/apache2.conf \
 && a2enmod rewrite
