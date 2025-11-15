-- ============================
-- TABELA USUARIO
-- ============================
CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(150) NOT NULL UNIQUE,
    nome_usuario VARCHAR(150) NOT NULL,
    senha VARCHAR(100),
    tipo_usuario ENUM('Administrador', 'Atleta', 'Instrutor', 'Usuário') NOT NULL,
    foto TEXT NOT NULL
) ENGINE=InnoDB;

-- ============================
-- TABELA RECUPERACAO DE SENHA
-- ============================
CREATE TABLE IF NOT EXISTS RecuperacaoSenha (
    idRecuperacaoSenha INT AUTO_INCREMENT PRIMARY KEY,
    idUsuarioFK INT NOT NULL,
    tokenRecuperacaoSenha VARCHAR(200) NOT NULL UNIQUE,
    expiraRecuperacaoSenha DATETIME NOT NULL,
    usadoRecuperacaoSenha TINYINT(1) DEFAULT 0,
    criadoRecuperacaoSenha DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX (idUsuarioFK),
    CONSTRAINT recuperacao_senha_usuario
        FOREIGN KEY (idUsuarioFK) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================
-- TABELA ACADEMIA
-- ============================
CREATE TABLE IF NOT EXISTS academia (
    id_academia INT PRIMARY KEY AUTO_INCREMENT,
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
    logo TEXT
) ENGINE=InnoDB;

-- ============================
-- TABELA INSTRUTOR
-- ============================
CREATE TABLE IF NOT EXISTS instrutor (
    id_instrutor INT PRIMARY KEY AUTO_INCREMENT,
    nome_instrutor VARCHAR(150) NOT NULL,
    data_nascimento DATE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    fk_id_academia INT NOT NULL,
    fk_id_usuario INT NOT NULL,
    FOREIGN KEY (fk_id_academia) REFERENCES academia(id_academia)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (fk_id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================
-- TABELA ATLETA
-- ============================
CREATE TABLE IF NOT EXISTS atleta (
    id_atleta INT PRIMARY KEY AUTO_INCREMENT,
    nome_atleta VARCHAR(150) NOT NULL,
    data_nascimento DATE NOT NULL,
    biografia TEXT NOT NULL,
    sexo VARCHAR(1) NOT NULL,
    esporte ENUM('Aikidô', 'Boxe', 'Capoeira', 'Jiu-Jitsu Brasileiro', 'Judô', 'Karatê', 'Kung Fu', 'MMA', 'Muay Thai', 'Taekwondo') NOT NULL,
    peso DECIMAL(5,2),
    categoria VARCHAR(50) NOT NULL,
    fk_id_academia INT NOT NULL,
    fk_id_usuario INT NOT NULL,
    fk_id_instrutor INT NOT NULL,
    FOREIGN KEY (fk_id_academia) REFERENCES academia(id_academia)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (fk_id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (fk_id_instrutor) REFERENCES instrutor(id_instrutor)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================
-- TABELA INSTITUIÇÃO APOIADORA
-- ============================
CREATE TABLE IF NOT EXISTS instituicao_apoiadora (
    id_instituicao_apoiadora INT PRIMARY KEY AUTO_INCREMENT,
    nome_fantasia VARCHAR(200) NOT NULL,
    razao_social VARCHAR(200) NOT NULL,
    cnpj VARCHAR(20) UNIQUE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    endereco VARCHAR(100),
    bairro VARCHAR(50),
    cidade VARCHAR(50),
    cep VARCHAR(9),
    estado VARCHAR(2),
    instagram VARCHAR(100),
    descricao TEXT NOT NULL,
    logo TEXT NOT NULL
) ENGINE=InnoDB;

-- ============================
-- TABELA PEDIDO DE AJUDA
-- ============================
CREATE TABLE IF NOT EXISTS pedido_ajuda (
    id_pedido_ajuda INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    valor_necessario DECIMAL(10,2) NOT NULL,
    valor_atingido DECIMAL(10,2) NOT NULL DEFAULT 0,
    pix VARCHAR(150) NOT NULL,
    imagem TEXT NOT NULL,
    fk_id_atleta INT NOT NULL,
    FOREIGN KEY (fk_id_atleta) REFERENCES atleta(id_atleta)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ============================
-- TABELA CAMPEONATO
-- ============================
CREATE TABLE IF NOT EXISTS campeonato (
    id_campeonato INT PRIMARY KEY AUTO_INCREMENT,
    nome_campeonato VARCHAR(250) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    local VARCHAR(100) NOT NULL,
    pais VARCHAR(60) NOT NULL,
    cidade VARCHAR(85) NOT NULL,
    esporte ENUM('Aikidô', 'Boxe', 'Capoeira', 'Jiu-Jitsu Brasileiro', 'Judô', 'Karatê', 'Kung Fu', 'MMA', 'Muay Thai', 'Taekwondo') NOT NULL
) ENGINE=InnoDB;
