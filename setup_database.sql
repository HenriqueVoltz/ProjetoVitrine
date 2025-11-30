-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS projeto_vitrine CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE projeto_vitrine;

-- Criar tabela de usuários
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir usuário admin padrão (senha: 123456)
INSERT INTO users (username, email, password, role) 
VALUES ('P.E.corp', 'admin@projeto.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE username=username;

-- Criar tabela de jogos
CREATE TABLE IF NOT EXISTS jogos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    descricao TEXT NOT NULL,
    imagem LONGTEXT NOT NULL,
    link_steam VARCHAR(500) NULL,
    jogo_semelhante_1 INT NULL,
    jogo_semelhante_2 INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (jogo_semelhante_1) REFERENCES jogos(id) ON DELETE SET NULL,
    FOREIGN KEY (jogo_semelhante_2) REFERENCES jogos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Criar tabela de atualizações
CREATE TABLE IF NOT EXISTS atualizacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jogo_id INT NOT NULL,
    nota TEXT NOT NULL,
    data_atualizacao DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jogo_id) REFERENCES jogos(id) ON DELETE CASCADE,
    INDEX idx_jogo_id (jogo_id),
    INDEX idx_data (data_atualizacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Criar tabela de configuração do carrossel
CREATE TABLE IF NOT EXISTS carrossel_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jogo_1 INT NULL,
    jogo_2 INT NULL,
    jogo_3 INT NULL,
    jogo_4 INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (jogo_1) REFERENCES jogos(id) ON DELETE SET NULL,
    FOREIGN KEY (jogo_2) REFERENCES jogos(id) ON DELETE SET NULL,
    FOREIGN KEY (jogo_3) REFERENCES jogos(id) ON DELETE SET NULL,
    FOREIGN KEY (jogo_4) REFERENCES jogos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
