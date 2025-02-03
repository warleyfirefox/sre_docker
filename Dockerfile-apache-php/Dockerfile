# Usando a imagem oficial do Apache com PHP
FROM php:8.0-apache

# Habilitar o mod_rewrite
RUN a2enmod rewrite

# Instalar o driver PHP para MySQL
RUN docker-php-ext-install mysqli

# Expor a porta 80
EXPOSE 80
