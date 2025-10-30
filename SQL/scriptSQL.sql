DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'tipo_usuario_enum') THEN
        CREATE TYPE tipo_usuario_enum AS ENUM ('Usuário', 'Administrador', 'Atleta', 'Instrutor', 'Academia');
    END IF;

    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'esporte_enum') THEN
        CREATE TYPE esporte_enum AS ENUM ('Aikidô', 'Boxe', 'Capoeira', 'Jiu-Jitsu Brasileiro', 'Judô', 'Karatê', 'Kung Fu', 'MMA', 'Muay Thai', 'Taekwondo');
    END IF;
END $$;

CREATE TABLE IF NOT EXISTS usuario(
    id_usuario SERIAL PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(100) NOT NULL,
    tipo_usuario tipo_usuario_enum NOT NULL,
    foto TEXT NOT NULL
);
select * from usuario;

CREATE TABLE IF NOT EXISTS atleta(
	id_atleta SERIAL PRIMARY KEY,
    nome_atleta VARCHAR(150) NOT NULL,
	data_nascimento DATE NOT NULL,
    biografia TEXT NOT NULL,
    sexo VARCHAR(1) NOT NULL,
    esporte esporte_enum NOT NULL,
    peso DECIMAL(3,2) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
	fk_id_usuario INTEGER NOT NULL,
    FOREIGN KEY (fk_id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
);
select * from atleta;

CREATE TABLE IF NOT EXISTS academia(
    id_academia SERIAL PRIMARY KEY,
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
	fk_id_usuario INTEGER NOT NULL,
    FOREIGN KEY (fk_id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
);
select * from academia;

CREATE TABLE IF NOT EXISTS instrutor(
    id_instrutor SERIAL PRIMARY KEY,
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
select * from instrutor;

CREATE TABLE IF NOT EXISTS instituicao_apoiadora(
    id_instituicao_apoiadora SERIAL PRIMARY KEY,
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
select * from instituicao_apoiadora;

CREATE TABLE IF NOT EXISTS pedido_ajuda(
    id_pedido_ajuda SERIAL PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    valor_necessario DECIMAL(10,2) NOT NULL,
    valor_atingido DECIMAL(10,2) NOT NULL,
    pix VARCHAR(150) NOT NULL,
    imagem TEXT NOT NULL,
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
select * from pedido_ajuda;

CREATE TABLE IF NOT EXISTS campeonato(
    id_campeonato SERIAL PRIMARY KEY,
    nome_campeonato VARCHAR(250) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    local VARCHAR(100) NOT NULL,
    pais VARCHAR(60) NOT NULL,
    cidade VARCHAR(85) NOT NULL,
    esporte esporte_enum NOT NULL
);
select * from campeonato;