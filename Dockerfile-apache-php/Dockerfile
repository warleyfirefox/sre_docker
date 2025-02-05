# Usando a imagem oficial do Apache com PHP
FROM php:8.0-apache

# Habilitar o mod_rewrite
RUN a2enmod rewrite

# Instalar o driver PHP para MySQL
RUN docker-php-ext-install mysqli

# Copiar o arquivo index.html para o diretório correto
#COPY css/markdown.css /var/www/html/

# Expor a porta 80
EXPOSE 80
