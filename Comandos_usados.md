# Instalação e Configuração do Git em uma Instância EC2 Ubuntu

## 1. Conectar-se à Instância EC2
Antes de instalar o Git, conecte-se à sua instância EC2 usando SSH:

```sh
ssh -i "seu-arquivo.pem" ubuntu@seu-endereco-ip
```
- Substitua `seu-arquivo.pem` pelo caminho da sua chave privada.
- Substitua `seu-endereco-ip` pelo IP público da sua instância.

## 2. Atualizar Pacotes do Sistema
Recomenda-se atualizar a lista de pacotes antes da instalação:

```sh
sudo apt update && sudo apt upgrade -y
```

Isso garante que você tenha a versão mais recente dos pacotes disponíveis.

## 3. Instalar o Git
Para instalar o Git, execute:

```sh
sudo apt install git -y
```

Após a instalação, verifique a versão instalada:

```sh
git --version
```

Se a instalação foi bem-sucedida, você verá uma saída como:

```
git version 2.x.x
```

---

## 4. Configurar o Git
Após a instalação, configure seu nome de usuário e email:

```sh
git config --global user.name "Seu Nome"
```
```
git config --global user.email "seu-email@example.com"
```


## 5. Desativar Verificação SSL (Opcional)
Se precisar desativar a verificação SSL por conta de problemas com certificados, use:

```sh
git config --global http.sslVerify false
```

Para verificar a configuração:

```sh
git config --list
```

## 6. Clonar um Repositório Git
Agora, você pode clonar um repositório para sua instância EC2:

```sh
git clone https://github.com/seu-usuario/seu-repositorio.git
```

Se estiver usando SSH:

```sh
git clone git@github.com:seu-usuario/seu-repositorio.git
```

Após clonar seu projeto acesse o diretorio do projeto e faça `git pull` pela primeira vez,
para não ficar te pedindo `login e senha` todas as vezes você pode criar chave de acesso via ssh ou usar o comando abaixo:

Cancele o comando `git pull` e antes execute o comando abaixo, em seguida faça `git pull` insira seu `login e senha`, agora não precisará ficar colocando suas credênciais ao fazer os comando git.

```
git config --global credential.helper store
```

## 7. Configurar Chave SSH para o GitHub (Opcional)
Se você deseja autenticar via SSH em vez de HTTPS, siga estes passos:

1. Gerar uma chave SSH:
   ```sh
   ssh-keygen -t rsa -b 4096 -C "seu-email@example.com"
   ```
   Pressione `Enter` para aceitar o local padrão e deixe a senha em branco.

2. Adicionar a chave SSH ao agente SSH:
   ```sh
   eval "$(ssh-agent -s)"
   ssh-add ~/.ssh/id_rsa
   ```

3. Copiar a chave SSH para o GitHub:
   ```sh
   cat ~/.ssh/id_rsa.pub
   ```
   Copie o conteúdo e adicione no GitHub em **Settings** > **SSH and GPG keys** > **New SSH Key**.

4. Testar a conexão com o GitHub:
   ```sh
   ssh -T git@github.com
   ```
   Se tudo estiver correto, você verá uma mensagem de autenticação bem-sucedida.

---
---
---


# Tutorial Completo: Instalar Docker e Configurar Nginx na EC2 Ubuntu

## Passo 1: Instalar o Docker na EC2 Ubuntu

1. **Conecte-se à sua instância EC2 via SSH**:

   Se ainda não estiver conectado à sua instância EC2, faça isso com o comando:

   ```bash
   ssh -i /caminho/para/sua-chave.pem ubuntu@<IP-da-instancia>
   ```

2. **Atualizar o sistema**:

   Atualize os pacotes do sistema para garantir que você está usando as versões mais recentes:

   ```bash
   sudo apt update
   sudo apt upgrade -y
   ```

3. **Instalar dependências**:

   Instale os pacotes necessários para adicionar repositórios HTTPS:

   ```bash
   sudo apt install apt-transport-https ca-certificates curl software-properties-common -y
   ```

4. **Adicionar o repositório oficial do Docker**:

   Baixe a chave GPG para verificar a autenticidade dos pacotes:

   ```bash
   curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
   ```

   Adicione o repositório oficial do Docker ao seu sistema:

   ```bash
   sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
   ```

5. **Instalar o Docker**:

   Atualize o índice de pacotes e instale o Docker:

   ```bash
   sudo apt update
   ```
   ```
   sudo apt install docker-ce docker-ce-cli containerd.io -y
   ```

6. **Verificar o status do Docker**:

   Verifique se o Docker está instalado e em execução com o comando:

   ```bash
   sudo systemctl status docker
   ```

   Isso deve mostrar que o Docker está ativo. Se necessário, inicie o serviço com:

   ```bash
   sudo systemctl start docker
   ```

7. **Habilitar o Docker para iniciar automaticamente**:

   Configure o Docker para iniciar automaticamente após o reinício do sistema:

   ```bash
   sudo systemctl enable docker
   ```

8. **Permitir o uso do Docker sem `sudo`** (opcional):

   Para permitir que seu usuário utilize o Docker sem precisar do `sudo`, execute:

   ```bash
   sudo usermod -aG docker $USER
   ```

   Depois de executar esse comando, faça logout e login novamente ou use:

   ```bash
   newgrp docker
   ```

9. **Verificar a instalação do Docker**:

   Execute o seguinte comando para verificar se o Docker foi instalado corretamente:

   ```bash
   docker --version
   ```

   Teste também a execução de um container com:

   ```bash
   docker run hello-world
   ```

## Passo 2: Configurar o Nginx com Docker

Agora que o Docker está instalado, vamos configurar o Nginx para rodar dentro de um container Docker.

1. **Estrutura do Projeto**:

   Dentro do seu projeto clonado temos a seguinte estrutura:

   ```bash
   Projeto_SRE/
   ├── mysql/
   │   ├── Dockerfile
   │   └── init.sql
   ├── nginx/
   │   ├── Dockerfile
   │   ├── nginx.conf
   │   └── html/
   │       └── index.html
  
   ```

   Aqui, `html` será onde você armazenará seu arquivo `index.html`, e `nginx.conf` será o arquivo de configuração personalizado do Nginx.

2. **Configuração do Nginx**:

   Crie ou edite o arquivo `nginx.conf` para incluir uma configuração básica do Nginx:

   ```nginx
   events {
       worker_connections 1024
   }
   
   http{
       server {
           listen       80;
           server_name  localhost;
    
           location / {
               root   /usr/share/nginx/html;
               index  index.html index.htm;
               try_files $uri $uri/ /index.html
           }
    
           # Você pode adicionar mais configurações, como logs, segurança, etc.
       }
   }
   ```

   Essa configuração serve para que o Nginx escute na porta 80 e procure o arquivo `index.html` dentro do diretório `/usr/share/nginx/html`.

3. **Criar o arquivo index.html**:

   Agora, crie o arquivo `index.html` dentro da pasta `html` que você criou. Adicione seu conteúdo HTML personalizado:

   ```html
   <!DOCTYPE html>
   <html lang="pt-br">
   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>Meu Nginx Personalizado</title>
   
       <style>
           body {
               margin: 0;
               height: 100vh;
               display: flex;
               justify-content: center;
               align-items: flex-start;
               padding-top: 100px;
               background-color: #f0f0f0;
           }
   
           .minha-classe {
               width: 500px;
               height: 250px;
               font-size: 15px;
               font-weight: bold;
               text-align: center;
               background-color: #fff;
               border: 1px solid #ccc;
               box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
           }
       </style>
   </head>
   <body>
   <div class="minha-classe">
       <h1>Bem-vindo ao meu servidor Nginx!</h1>
   </div>
   </body>
   </html>
   ```

4. **Criar o Dockerfile**:

   Crie o arquivo `Dockerfile` no diretório `meu-projeto` com o seguinte conteúdo:

   ```dockerfile
   # Use a imagem base do Nginx
   FROM nginx:latest
   
   # Garanta que o container use o entrypoint original do Nginx
   RUN rm /etc/nginx/conf.d/default.conf
   
   index.php
   COPY nginx/html/index.html /usr/share/nginx/html
   COPY nginx.conf /etc/nginx/nginx.conf
   
   # Exponha a porta 80 para acesso externo
   EXPOSE 80
   
   # Inicie o Nginx em primeiro plano
   CMD ["nginx", "-g", "daemon off;"]
   ```

   **Explicação**:
   - `FROM nginx:alpine`: Usa uma versão leve do Nginx.
   - `RUN rm /etc/nginx/conf.d/default.conf`: Remove a configuração padrão do Nginx.
   - `COPY ./nginx.conf /etc/nginx/nginx.conf`: Copia sua configuração personalizada do Nginx para o container.
   - `COPY ./html /usr/share/nginx/html`: Copia seus arquivos estáticos (como `index.html`).
   - `EXPOSE 80`: Exponha a porta 80 para que o Nginx possa ser acessado.
   - `CMD ["nginx", "-g", "daemon off;"]`: Inicia o Nginx em primeiro plano (necessário para o Docker).

5. **Construir a imagem Docker**:

   No diretório onde o `Dockerfile` está localizado, execute o comando para construir sua imagem Docker:

   ```bash
   docker build -t meu_nginx .
   ```

6. **Executar o container**:

   Após a construção da imagem, execute o container com o seguinte comando:

   ```bash
   docker run -d -p 80:80 meu_nginx
   ```

   Isso irá rodar o Nginx no container e mapear a porta 80 da sua EC2 para a porta 80 do container, permitindo que você acesse o servidor Nginx no navegador.

7. **Acessar o servidor**:

   Agora, você pode acessar o servidor Nginx pelo endereço público da sua EC2:

   ```
   http://<IP-da-instancia>
   ```

   Você deve ver a página com a mensagem "Bem-vindo ao meu servidor Nginx!".

---

## Resumo

- **Passo 1**: Instalamos o Docker em uma instância EC2 Ubuntu.
- **Passo 2**: Configuramos um `Dockerfile` para rodar o Nginx com uma configuração personalizada, apontando para um `index.html` criado por você.

Agora seu Nginx está configurado para rodar em um container Docker na sua EC2!

___
___
___

# Configurando MySQL com Docker

Este tutorial descreve como configurar o MySQL em containers Docker em uma instância EC2 Ubuntu, sem usar Docker Compose.

## 1. Criando o `Dockerfile` para o MySQL

Crie o arquivo `Dockerfile` para configurar o container do MySQL. Esse arquivo define a imagem base, as variáveis de ambiente e o script SQL para inicializar o banco de dados.

### `Dockerfile`

```Dockerfile
# Usando a imagem oficial do MySQL
FROM mysql:8.0

# Variáveis de ambiente para configurar o banco de dados
ENV MYSQL_ROOT_PASSWORD=root

# Cria o DB ao iniciar o contâiner 
ENV MYSQL_DATABASE=db_sre

# Copiar o script SQL para criar o banco de dados e tabela
# O MySQL executa automaticamente qualquer script presente nesse diretório ao iniciar o contêiner.
COPY init.sql /docker-entrypoint-initdb.d/

# Expor a porta padrão do MySQL
EXPOSE 3306
```

- **`FROM mysql:8.0`**: Estamos usando a imagem oficial do MySQL versão 8.0.
- **`ENV MYSQL_ROOT_PASSWORD=root`**: Define a senha do usuário root para o MySQL como `root`.
- **`ENV MYSQL_DATABASE=db_sre`**: Cria um banco de dados chamado `db_sre` assim que o container é iniciado.
- **`COPY init.sql /docker-entrypoint-initdb.d/`**: Copia o arquivo `init.sql` para o diretório de inicialização do MySQL. O MySQL irá executar automaticamente os scripts contidos nesse diretório ao iniciar.
- **`EXPOSE 3306`**: Exponha a porta 3306, que é a porta padrão do MySQL.

## 2. Criando o Arquivo `init.sql`

Crie o arquivo `init.sql` que contém as instruções SQL para criar o banco de dados, a tabela e inserir dados iniciais.

### `init.sql`

```sql
-- Criação do banco de dados e da tabela
CREATE DATABASE IF NOT EXISTS db_sre;
USE db_sre;

CREATE TABLE IF NOT EXISTS data_sre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome CHAR(100),
    mensagem CHAR(255),
    dev CHAR(100)
);

-- Inserção de dados iniciais
INSERT INTO data_sre (nome, mensagem, dev)
VALUES ('Curso SRE e AWS', 'Primeiro Desafio SRE e AWS', 'Warley M. Araujo'),
       ('Curso SRE e AWS', 'Primeiro Desafio SRE e AWS', 'Werley M. Araujo'),
       ('Curso SRE e AWS', 'Primeiro Desafio SRE e AWS', 'Maxwell M. Cavalcante');
```

Este script cria:
- O banco de dados `db_sre`.
- A tabela `data_sre` com os campos `id`, `nome`, `mensagem` e `dev`.
- Alguns dados iniciais para popular a tabela.

## 3. Construindo e Rodando o Container do MySQL

Agora que você tem os arquivos `Dockerfile` e `init.sql`, siga os seguintes passos para construir a imagem Docker e rodar o container:

### Construir a Imagem Docker

Na pasta onde os arquivos `Dockerfile` e `init.sql` estão localizados, execute o seguinte comando para construir a imagem:

```bash
docker build -t meu_mysql .
```

Esse comando criará a imagem com o nome `meu_mysql` baseado no `Dockerfile` que você criou.

### Rodar o Container

Após a construção da imagem, você pode rodar o container MySQL com o seguinte comando:

```bash
docker run -d -p 3306:3306 --name mysql-container meu_mysql
```

Explicação dos parâmetros:
- **`-d`**: Rodar o container em segundo plano (modo "detached").
- **`-p 3306:3306`**: Mapeia a porta 3306 do container para a porta 3306 do host, permitindo acesso ao banco de dados a partir do host.
- **`--name mysql-container`**: Dá o nome `mysql-container` ao container.
- **`mysql-db`**: A imagem que acabamos de construir.

### Verificando se o MySQL Está Funcionando

Você pode acessar o MySQL no container utilizando o seguinte comando:

```bash
docker exec -it mysql-container mysql -u root -p
```

Digite a senha `root` quando solicitado. Uma vez dentro, você pode verificar o banco de dados e a tabela com os seguintes comandos SQL:

```sql
USE db_sre;
SHOW TABLES;
SELECT * FROM data_sre;
```

Isso deve mostrar os dados que você inseriu no arquivo `init.sql`.

## 4. Conclusão

Com esses passos, você configurou um ambiente MySQL em um container Docker na sua instância EC2 Ubuntu, sem utilizar o Docker Compose. O MySQL está rodando e o banco de dados e a tabela foram criados automaticamente durante a inicialização do container.

## Acessar o container de MySQL
docker exec -it mysql mysql -uroot -p
password: root