DROP SCHEMA IF EXISTS Alianca_Marcial;

CREATE SCHEMA IF NOT EXISTS Alianca_Marcial DEFAULT CHARACTER SET utf8mb4;
USE Alianca_Marcial;

CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INTEGER PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(100) NOT NULL,
    tipo_usuario ENUM('Usuário', 'Administrador', 'Atleta', 'Instrutor', 'Academia') NOT NULL,
    foto TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS atleta(
	id_atleta INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome_atleta VARCHAR(150) NOT NULL,
	data_nascimento DATE NOT NULL,
    biografia TEXT NOT NULL,
    sexo VARCHAR(1) NOT NULL,
    esporte ENUM ('Aikidô', 'Boxe', 'Capoeira', 'Jiu-Jitsu Brasileiro', 'Judô', 'Karatê', 'Kung Fu', 'MMA', 'Muay Thai', 'Taekwondo') NOT NULL,
    peso DECIMAL(3,2),
    categoria VARCHAR(50) NOT NULL,
	fk_id_usuario INTEGER NOT NULL,
    FOREIGN KEY (fk_id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS academia(
    id_academia INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome_fantasia VARCHAR(200) NOT NULL,
    razao_social VARCHAR(200) NOT NULL,
    cnpj VARCHAR(20) UNIQUE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    endereco VARCHAR(100),
    bairro VARCHAR(50),
    cidade VARCHAR(50),
    cep VARCHAR(9),
    estado VARCHAR(2),
    instagram VARCHAR(100),
    logo TEXT,
	fk_id_usuario INTEGER NOT NULL,
    FOREIGN KEY (fk_id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS instrutor(
    id_instrutor INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome_instrutor VARCHAR(150) NOT NULL,
    data_nascimento DATE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    fk_id_academia INTEGER NOT NULL,
	fk_id_usuario INTEGER NOT NULL,
    FOREIGN KEY (fk_id_academia) REFERENCES academia(id_academia)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (fk_id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS instituicao_apoiadora(
    id_instituicao_apoiadora INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome_fantasia VARCHAR(200) NOT NULL,
    razao_social VARCHAR(200) NOT NULL,
    cnpj VARCHAR(20) UNIQUE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    endereco VARCHAR(100),
    bairro VARCHAR(50),
    cidade VARCHAR(50),
    cep VARCHAR(9),
    estado VARCHAR(2),
    instagram VARCHAR(100),
    descricao TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS pedido_ajuda(
    id_pedido_ajuda INTEGER PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    valor_necessario DECIMAL(10,2) NOT NULL,
    valor_atingido DECIMAL(10,2) NOT NULL,
    pix VARCHAR(150) NOT NULL,
    imagem TEXT NOT NULL,
	-- contato
    fk_id_academia INTEGER NOT NULL,
    fk_id_instrutor INTEGER NOT NULL,
    fk_id_atleta INTEGER NOT NULL,
    FOREIGN KEY (fk_id_academia) REFERENCES academia(id_academia)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (fk_id_instrutor) REFERENCES instrutor(id_instrutor)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (fk_id_atleta) REFERENCES atleta(id_atleta)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS campeonato(
    id_campeonato INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome_campeonato VARCHAR(250) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    local VARCHAR(100) NOT NULL,
    pais VARCHAR(60) NOT NULL,
    cidade VARCHAR(85) NOT NULL,
    esporte ENUM ('Aikidô', 'Boxe', 'Capoeira', 'Jiu-Jitsu Brasileiro', 'Judô', 'Karatê', 'Kung Fu', 'MMA', 'Muay Thai', 'Taekwondo') NOT NULL
);