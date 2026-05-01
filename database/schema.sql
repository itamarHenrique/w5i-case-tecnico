CREATE DATABASE IF NOT EXISTS w5i_chamados CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE w5i_chamados;

CREATE TABLE setores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE prioridades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(50) NOT NULL,
    tempo_estimado_horas INT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE chamados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    setor_id INT NOT NULL,
    prioridade_id INT NOT NULL,
    status ENUM('Aberto', 'Em Atendimento', 'Finalizado', 'Cancelado') DEFAULT 'Aberto',
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_inicio DATETIME NULL,
    data_fim DATETIME NULL,
    solucao TEXT NULL,
    
    CONSTRAINT fk_setor FOREIGN KEY (setor_id) REFERENCES setores(id) ON DELETE CASCADE,
    CONSTRAINT fk_prioridade FOREIGN KEY (prioridade_id) REFERENCES prioridades(id) ON DELETE CASCADE
) ENGINE=InnoDB;