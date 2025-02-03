#!/bin/bash

#Apaga os container se existir
docker rm -f mysql
docker rm -f apache-php

#Apaga as imagens se existir
docker rmi -f mysql-db
docker rmi -f apache-php

# Convertendo o MarkDown para HTML
MD_FILE="README.md"
HTML_FILE="www/readme.html"

# Build do container MySQL
docker build -t mysql-db ./Dockerfile-mysql

# Rodando o container MySQL
docker run -d --name mysql -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=db_sre -p 3306:3306 mysql-db

# Espera o MySQL iniciar antes de rodar o Apache com PHP
sleep 10

# Build do container Apache com PHP
docker build -t apache-php ./Dockerfile-apache-php

# Rodando o container Apache com PHP
docker run -d --name apache-php -p 80:80 --link mysql:mysql --mount type=bind,src=./www,dst=/var/www/html/ apache-php

#Exibe os container que estão em execução
docker ps

# Imprimir uma mensagem após a execução
echo "O script foi executado com sucesso!"