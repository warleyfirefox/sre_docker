#!/bin/bash

# Apaga os containers se existirem
docker rm -f mysql
docker rm -f apache-php

# Apaga as imagens se existirem
docker rmi -f mysql-db
docker rmi -f apache-php

# Build do container MySQL
docker build -t mysql-db ./Dockerfile-mysql

# Cria um volume nomeado para persistir os dados
docker volume create mysql-data

# Rodando o container MySQL com volume nomeado persistente
docker run -d --name mysql -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=db_sre -v mysql-data:/var/lib/mysql -p 3306:3306 mysql-db

# Espera o MySQL iniciar antes de rodar o Apache com PHP
sleep 10

# Build do container Apache com PHP
docker build -t apache-php ./Dockerfile-apache-php

# Rodando o container Apache com PHP
docker run -d --name apache-php -p 80:80 --link mysql:mysql --mount type=bind,src=./www,dst=/var/www/html/ apache-php

# Exibe os containers que estão em execução
docker ps && docker volume ls

# Cria um container temporario apenas para salvar um backup dos dados do volume mysql-data o banco de dados
#docker run --rm -v mysql-data:/data -v ./backup-db:/backup alpine tar czf /backup/mysql_backup.tar.gz -C /data .

# Imprimir uma mensagem após a execução
echo "O script foi executado com sucesso!"
