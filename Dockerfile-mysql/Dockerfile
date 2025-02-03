# Usando a imagem oficial do MySQL
FROM mysql:8.0

# Variáveis de ambiente para configurar o banco de dados
ENV MYSQL_ROOT_PASSWORD=root

# Cria o DB ao iniciar o contâiner
ENV MYSQL_DATABASE=db_sre

# Copiar o script SQL para criar o banco de dados e tabela
COPY init.sql /docker-entrypoint-initdb.d/

# Expor a porta padrão do MySQL
EXPOSE 3306
