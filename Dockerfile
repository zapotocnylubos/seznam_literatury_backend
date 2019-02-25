FROM php:5.6-apache

# install pdo extension
RUN docker-php-ext-install pdo pdo_mysql

# enable mod_rewrite
RUN a2enmod rewrite
