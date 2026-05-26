CREATE DATABASE IF NOT EXISTS RC_database
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE RC_database;

CREATE TABLE IF NOT EXISTS utilizadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nif VARCHAR(20) NOT NULL,
    email VARCHAR(120),
    telefone VARCHAR(30),
    morada VARCHAR(255)
);

INSERT INTO utilizadores (nome, email, password)
VALUES ('Aluno Teste', 'aluno@escola.pt', '1234')
ON DUPLICATE KEY UPDATE nome = VALUES(nome);
