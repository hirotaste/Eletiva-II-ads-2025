-- ============================================
-- TABELAS DE AUTENTICAÇÃO E CONTROLE DE ACESSO
-- ============================================

USE academico;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    level ENUM('admin', 'professor', 'estudante') NOT NULL DEFAULT 'estudante',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de reset de senha
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de logs de acesso
CREATE TABLE IF NOT EXISTS access_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    url TEXT NULL,
    method VARCHAR(10) NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir usuários padrão
INSERT INTO users (name, email, password, level, email_verified_at, created_at, updated_at) VALUES
('Admin User', 'stefano@admin.com', '$2y$12$CBu7nD.56T7VWhGX8C.qSuTkw8a7z3Wp3seqCMTAA71d47RhFeKTq', 'admin', NOW(), NOW(), NOW()),
('João Silva', 'joao.silva@instituicao.edu.br', '$2y$12$CBu7nD.56T7VWhGX8C.qSuTkw8a7z3Wp3seqCMTAA71d47RhFeKTq', 'professor', NOW(), NOW(), NOW()),
('Carlos Souza', 'carlos.souza@aluno.edu.br', '$2y$12$CBu7nD.56T7VWhGX8C.qSuTkw8a7z3Wp3seqCMTAA71d47RhFeKTq', 'estudante', NOW(), NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Mensagem de sucesso
SELECT 'Tabelas de autenticação criadas e usuários inseridos com sucesso!' AS status;
SELECT 'Email: stefano@admin.com | Senha: password | Nível: admin' AS credenciais_admin;
