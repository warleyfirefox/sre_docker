# noinspection SqlNoDataSourceInspectionForFile

-- Criação do banco de dados e da tabela
CREATE DATABASE IF NOT EXISTS db_sre;
USE db_sre;

CREATE TABLE IF NOT EXISTS data_sre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email CHAR(100),
    password CHAR(255),
    dev CHAR(100)
);

-- Inserção de dados iniciais
INSERT INTO data_sre (email, password, dev)
VALUES ('warleyfirefox@gmail.com', 'wma12345', 'Warley M. Araujo'),
       ('war', '12345', 'Warley M. Araujo');
