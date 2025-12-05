-- Apaga e recria o schema
DROP SCHEMA IF EXISTS Alianca_Marcial;
CREATE SCHEMA IF NOT EXISTS Alianca_Marcial 
    DEFAULT CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

USE Alianca_Marcial;

-- ===============================
-- TABELA USUARIO
-- ===============================
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL UNIQUE,
    nome_usuario VARCHAR(150) NOT NULL,
    senha VARCHAR(100),
    tipo_usuario ENUM('Administrador', 'Atleta', 'Instrutor', 'Usuário') NOT NULL,
    foto TEXT NOT NULL,
    status_validacao ENUM('valido', 'nao_validado') DEFAULT 'nao_validado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===============================
-- RECUPERACAO DE SENHA
-- ===============================
CREATE TABLE RecuperacaoSenha (
    idRecuperacaoSenha INT AUTO_INCREMENT PRIMARY KEY,
    idUsuarioFK INT NOT NULL,
    tokenRecuperacaoSenha VARCHAR(200) NOT NULL UNIQUE,
    expiraRecuperacaoSenha DATETIME NOT NULL,
    usadoRecuperacaoSenha TINYINT(1) DEFAULT 0,
    criadoRecuperacaoSenha DATETIME DEFAULT CURRENT_TIMESTAMP,
    KEY idUsuarioFK (idUsuarioFK),
    CONSTRAINT fk_recuperacao_usuario FOREIGN KEY (idUsuarioFK)
        REFERENCES usuario(id_usuario)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===============================
-- TABELA ACADEMIA
-- ===============================
CREATE TABLE academia (
    id_academia INTEGER PRIMARY KEY AUTO_INCREMENT,
    nome_fantasia VARCHAR(200) NOT NULL,
    razao_social VARCHAR(200) NOT NULL,
    cnpj VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    link TEXT,
    logo TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===============================
-- TABELA INSTRUTOR (precisa vir antes do atleta)
-- ===============================
CREATE TABLE instrutor (
    id_instrutor INT AUTO_INCREMENT PRIMARY KEY,
    nome_instrutor VARCHAR(150) NOT NULL,
    data_nascimento DATE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    status_validacao ENUM('valido', 'nao_validado', 'invalido') DEFAULT 'nao_validado',
    token_validacao VARCHAR(255) NULL,
    expira_validacao DATETIME,
    motivo_reprovacao TEXT,
    fk_id_academia INT NOT NULL,
    fk_id_usuario INT NOT NULL,
    FOREIGN KEY (fk_id_academia) REFERENCES academia(id_academia)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (fk_id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===============================
-- TABELA ATLETA
-- ===============================
CREATE TABLE atleta (
    id_atleta INT AUTO_INCREMENT PRIMARY KEY,
    nome_atleta VARCHAR(150) NOT NULL,
    data_nascimento DATE NOT NULL,
    biografia TEXT NOT NULL,
    sexo VARCHAR(1) NOT NULL,
    esporte ENUM ('Aikidô', 'Boxe', 'Capoeira', 'Jiu-Jitsu Brasileiro', 'Judô', 'Karatê', 'Kung Fu', 'MMA', 'Muay Thai', 'Taekwondo') NOT NULL,
    peso DECIMAL(5,2),
    categoria VARCHAR(50) NOT NULL,
    status_validacao ENUM('valido', 'nao_validado', 'invalido') DEFAULT 'nao_validado',
    token_validacao VARCHAR(255) NULL,
    expira_validacao DATETIME,
    motivo_reprovacao TEXT,
    fk_id_academia INT NOT NULL,
    fk_id_usuario INT NOT NULL,
    fk_id_instrutor INT NOT NULL,
    FOREIGN KEY (fk_id_academia) REFERENCES academia(id_academia)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (fk_id_usuario) REFERENCES usuario(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (fk_id_instrutor) REFERENCES instrutor(id_instrutor)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===============================
-- INSTITUICAO APOIADORA
-- ===============================
CREATE TABLE instituicao_apoiadora (
    id_instituicao_apoiadora INT AUTO_INCREMENT PRIMARY KEY,
    nome_fantasia VARCHAR(200) NOT NULL,
    link TEXT NOT NULL,
    logo TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===============================
-- PEDIDO DE AJUDA
-- ===============================
CREATE TABLE pedido_ajuda (
    id_pedido_ajuda INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    valor_necessario DECIMAL(10,2) NOT NULL,
    valor_atingido DECIMAL(10,2) NOT NULL,
    pix VARCHAR(150) NOT NULL,
    imagem TEXT NOT NULL,
    status_validacao ENUM('pendente', 'aprovado', 'reprovado') DEFAULT 'pendente',
    token_validacao VARCHAR(255),
    expira_validacao DATETIME NOT NULL,
    motivo_reprovacao TEXT,
    meta ENUM('atingida', 'pendente') DEFAULT 'pendente',
    fk_id_atleta INT NOT NULL,
    FOREIGN KEY (fk_id_atleta) REFERENCES atleta(id_atleta)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===============================
-- CAMPEONATO
-- ===============================
CREATE TABLE campeonato (
    id_campeonato INT AUTO_INCREMENT PRIMARY KEY,
    nome_campeonato VARCHAR(250) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    local VARCHAR(100) NOT NULL,
    pais VARCHAR(60) NOT NULL,
    cidade VARCHAR(85) NOT NULL,
    esporte ENUM ('Aikidô', 'Boxe', 'Capoeira', 'Jiu-Jitsu Brasileiro', 'Judô', 'Karatê', 'Kung Fu', 'MMA', 'Muay Thai', 'Taekwondo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===============================
-- PROCEDURE DASHBOARD
-- ===============================
DELIMITER $$
CREATE PROCEDURE `dashboard_totais`()
BEGIN
    SELECT 
        -- Usuários
        (SELECT COUNT(*) FROM usuario WHERE tipo_usuario = "Usuário") AS totalUsuarios,

        -- Atletas
        (SELECT COUNT(*) FROM atleta) AS totalAtletas,

        -- Instrutores
        (SELECT COUNT(*) FROM instrutor) AS totalInstrutores,

        -- Academias
        (SELECT COUNT(*) FROM academia) AS totalAcademias,

        -- Instituições
        (SELECT COUNT(*) FROM instituicao_apoiadora) AS totalInstituicao,

        -- Campeonatos
        (SELECT COUNT(*) FROM campeonato) AS totalCampeonatos,

        -- Pedidos de Ajuda
        (SELECT COUNT(*) FROM pedido_ajuda WHERE meta = "pendente" AND status_validacao = "aprovado") AS totalPedidosAjuda;
END$$
DELIMITER ;