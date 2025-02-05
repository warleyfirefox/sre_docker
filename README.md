key: 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

# Sistema de Login e Cadastro PHP

Este é um sistema simples de login e cadastro feito em PHP, utilizando um banco de dados MySQL.
 O projeto permite que os usuários façam login com um e-mail e senha ou se registrem com um novo e-mail e senha. Ao realizar o login, o nome do usuário registrado é exibido.

## Tecnologias Utilizadas

- PHP
- MySQL
- HTML/CSS
- JavaScript

## Como Rodar o Projeto

### Pré-requisitos

Certifique-se de ter os seguintes requisitos instalados em seu ambiente:

- docker instalado na sua máquina e funcionando

### Passos para iniciar os containers

 - Build o container MySQL
 
```
docker build -t mysql-db ./Dockerfile-mysql
```
- Rodando o container MySQL

```
docker run -d --name mysql -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=db_sre -p 3306:3306 mysql-db
```

- Espera o MySQL iniciar antes de rodar o Apache com PHP em uns 10 segundos

- Build do container Apache com PHP

```
docker build -t apache-php ./Dockerfile-apache-php
```
- Rodando o container Apache com PHP

```
docker run -d --name apache-php -p 80:80 --link mysql:mysql apache-php
```

## Alternativa 2

- Navege até a raíz desse projeto e dê permisão de execusão de script para o arquivo
run_containers.sh.

```
chmod +x run_conteiners.sh
```

- Em seguida execute o comando abaixo.

```
./run_containers.sh
```

- Após iniciar os containers, acesse:

```
http://localhost:80
```
- Ou se estiver na sua ec2, coloque seu IP público:

```
http:IP_PUBLICO_DA_EC2
```

### Obrigado por executar esse projeto :)