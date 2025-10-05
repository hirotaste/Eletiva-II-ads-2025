-- ============================================
-- SCHEMA COMPLETO - SISTEMA DE GESTÃO ACADÊMICA
-- MySQL / MariaDB (XAMPP Compatible)
-- Com suporte a Otimização de Horários, Métricas e Relatórios
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- ============================================
-- TABELAS BASE - ESTRUTURA ACADÊMICA
-- ============================================

-- Cursos oferecidos pela instituição
CREATE TABLE cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    duracao_semestres INT NOT NULL,
    carga_horaria_total INT NOT NULL,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Disciplinas do catálogo
CREATE TABLE disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(150) NOT NULL,
    carga_horaria INT NOT NULL,
    creditos INT NOT NULL,
    ementa TEXT,
    tipo ENUM('obrigatoria', 'optativa', 'eletiva') NOT NULL,
    semestre_ideal INT,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Relação disciplina-curso (matriz curricular)
CREATE TABLE curso_disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    curso_id INT NOT NULL,
    disciplina_id INT NOT NULL,
    semestre_recomendado INT NOT NULL,
    obrigatoria TINYINT(1) DEFAULT 1,
    UNIQUE KEY unique_curso_disciplina (curso_id, disciplina_id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pré-requisitos entre disciplinas
CREATE TABLE prerequisitos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    disciplina_id INT NOT NULL,
    prerequisito_id INT NOT NULL,
    tipo ENUM('obrigatorio', 'recomendado') NOT NULL,
    UNIQUE KEY unique_prerequisito (disciplina_id, prerequisito_id),
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE,
    FOREIGN KEY (prerequisito_id) REFERENCES disciplinas(id) ON DELETE CASCADE,
    CHECK (disciplina_id != prerequisito_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Professores
CREATE TABLE professores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    telefone VARCHAR(20),
    titulacao VARCHAR(50),
    carga_horaria_maxima INT DEFAULT 40,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Disponibilidade de horários dos professores
CREATE TABLE professor_disponibilidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT NOT NULL,
    dia_semana TINYINT NOT NULL CHECK (dia_semana BETWEEN 1 AND 7),
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    preferencia TINYINT CHECK (preferencia BETWEEN 1 AND 5),
    UNIQUE KEY unique_disponibilidade (professor_id, dia_semana, hora_inicio),
    FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Disciplinas que cada professor pode lecionar
CREATE TABLE professor_disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT NOT NULL,
    disciplina_id INT NOT NULL,
    preferencia TINYINT CHECK (preferencia BETWEEN 1 AND 5),
    UNIQUE KEY unique_prof_disciplina (professor_id, disciplina_id),
    FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE CASCADE,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Salas de aula
CREATE TABLE salas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    capacidade INT NOT NULL,
    tipo ENUM('sala_aula', 'laboratorio', 'auditorio') NOT NULL,
    possui_projetor TINYINT(1) DEFAULT 0,
    possui_ar_condicionado TINYINT(1) DEFAULT 0,
    possui_computadores TINYINT(1) DEFAULT 0,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Alunos
CREATE TABLE alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    data_nascimento DATE,
    telefone VARCHAR(20),
    curso_id INT,
    semestre_atual INT NOT NULL,
    ano_ingresso INT NOT NULL,
    semestre_ingresso TINYINT CHECK (semestre_ingresso IN (1, 2)),
    status ENUM('ativo', 'trancado', 'formado', 'desistente') DEFAULT 'ativo',
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (curso_id) REFERENCES cursos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Histórico acadêmico do aluno
CREATE TABLE historico_academico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    disciplina_id INT NOT NULL,
    ano INT NOT NULL,
    semestre TINYINT CHECK (semestre IN (1, 2)),
    nota DECIMAL(4,2),
    frequencia DECIMAL(5,2),
    status ENUM('cursando', 'aprovado', 'reprovado', 'trancado') NOT NULL,
    is_dependencia TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_historico (aluno_id, disciplina_id, ano, semestre),
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELAS DE GRADE HORÁRIA
-- ============================================

-- Períodos letivos
CREATE TABLE periodos_letivos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ano INT NOT NULL,
    semestre TINYINT CHECK (semestre IN (1, 2)),
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    status ENUM('planejamento', 'ativo', 'finalizado') DEFAULT 'planejamento',
    UNIQUE KEY unique_periodo (ano, semestre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Turmas criadas para o período
CREATE TABLE turmas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo_letivo_id INT NOT NULL,
    disciplina_id INT NOT NULL,
    professor_id INT NOT NULL,
    codigo VARCHAR(20) NOT NULL,
    vagas_total INT NOT NULL,
    vagas_ocupadas INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_turma_codigo (periodo_letivo_id, codigo),
    FOREIGN KEY (periodo_letivo_id) REFERENCES periodos_letivos(id) ON DELETE CASCADE,
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id),
    FOREIGN KEY (professor_id) REFERENCES professores(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Horários das turmas (uma turma pode ter múltiplos horários)
CREATE TABLE turma_horarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    turma_id INT NOT NULL,
    sala_id INT NOT NULL,
    dia_semana TINYINT CHECK (dia_semana BETWEEN 1 AND 7),
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE,
    FOREIGN KEY (sala_id) REFERENCES salas(id),
    CHECK (hora_fim > hora_inicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Matrículas dos alunos nas turmas
CREATE TABLE matriculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    turma_id INT NOT NULL,
    status ENUM('matriculado', 'trancado', 'cancelado') DEFAULT 'matriculado',
    data_matricula TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_dependencia TINYINT(1) DEFAULT 0,
    UNIQUE KEY unique_matricula (aluno_id, turma_id),
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELAS DE OTIMIZAÇÃO
-- ============================================

-- Execuções do algoritmo genético
CREATE TABLE otimizacao_execucoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo_letivo_id INT,
    algoritmo VARCHAR(50) NOT NULL,
    parametros JSON,
    fitness_inicial DECIMAL(10,4),
    fitness_final DECIMAL(10,4),
    geracoes_executadas INT,
    tempo_execucao_segundos INT,
    status ENUM('executando', 'concluido', 'erro') DEFAULT 'executando',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    finished_at TIMESTAMP NULL,
    FOREIGN KEY (periodo_letivo_id) REFERENCES periodos_letivos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Soluções geradas (diferentes versões de grade)
CREATE TABLE otimizacao_solucoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    execucao_id INT NOT NULL,
    periodo_letivo_id INT NOT NULL,
    versao INT NOT NULL,
    fitness_score DECIMAL(10,4) NOT NULL,
    num_conflitos INT DEFAULT 0,
    num_janelas_total INT DEFAULT 0,
    media_janelas_aluno DECIMAL(6,2),
    aprovada TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_solucao_versao (periodo_letivo_id, versao),
    FOREIGN KEY (execucao_id) REFERENCES otimizacao_execucoes(id) ON DELETE CASCADE,
    FOREIGN KEY (periodo_letivo_id) REFERENCES periodos_letivos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Detalhes das alocações em cada solução
CREATE TABLE solucao_alocacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    solucao_id INT NOT NULL,
    turma_id INT NOT NULL,
    sala_id INT NOT NULL,
    dia_semana TINYINT CHECK (dia_semana BETWEEN 1 AND 7),
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    FOREIGN KEY (solucao_id) REFERENCES otimizacao_solucoes(id) ON DELETE CASCADE,
    FOREIGN KEY (turma_id) REFERENCES turmas(id),
    FOREIGN KEY (sala_id) REFERENCES salas(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELAS DE MÉTRICAS E PONTUAÇÃO
-- ============================================

-- Configuração de pesos para o sistema de pontuação
CREATE TABLE configuracao_pontuacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    peso_janela_1h INT DEFAULT -5,
    peso_janela_2h INT DEFAULT -15,
    peso_janela_3h_mais INT DEFAULT -25,
    bonus_dia_sem_janela INT DEFAULT 10,
    bonus_aulas_sequenciais INT DEFAULT 5,
    peso_conflito_horario INT DEFAULT -50,
    peso_preferencia_professor INT DEFAULT 3,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Métricas calculadas por aluno
CREATE TABLE metricas_aluno (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    periodo_letivo_id INT NOT NULL,
    solucao_id INT,
    num_disciplinas INT DEFAULT 0,
    carga_horaria_semanal INT DEFAULT 0,
    num_janelas_total INT DEFAULT 0,
    num_janelas_1h INT DEFAULT 0,
    num_janelas_2h INT DEFAULT 0,
    num_janelas_3h_mais INT DEFAULT 0,
    dias_com_aula INT DEFAULT 0,
    dias_sem_janela INT DEFAULT 0,
    pontuacao_janelas INT DEFAULT 0,
    pontuacao_total INT DEFAULT 0,
    percentual_aproveitamento DECIMAL(5,2),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_metrica_aluno (aluno_id, periodo_letivo_id, solucao_id),
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (periodo_letivo_id) REFERENCES periodos_letivos(id),
    FOREIGN KEY (solucao_id) REFERENCES otimizacao_solucoes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Métricas agregadas por turma
CREATE TABLE metricas_turma (
    id INT AUTO_INCREMENT PRIMARY KEY,
    turma_id INT NOT NULL,
    periodo_letivo_id INT NOT NULL,
    taxa_ocupacao DECIMAL(5,2),
    media_frequencia DECIMAL(5,2),
    taxa_aprovacao DECIMAL(5,2),
    num_alunos_dependencia INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_metrica_turma (turma_id, periodo_letivo_id),
    FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE,
    FOREIGN KEY (periodo_letivo_id) REFERENCES periodos_letivos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Métricas agregadas por professor
CREATE TABLE metricas_professor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    professor_id INT NOT NULL,
    periodo_letivo_id INT NOT NULL,
    num_turmas INT DEFAULT 0,
    carga_horaria_total INT DEFAULT 0,
    percentual_preferencias_atendidas DECIMAL(5,2),
    pontuacao_satisfacao INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_metrica_professor (professor_id, periodo_letivo_id),
    FOREIGN KEY (professor_id) REFERENCES professores(id) ON DELETE CASCADE,
    FOREIGN KEY (periodo_letivo_id) REFERENCES periodos_letivos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Métricas globais do sistema
CREATE TABLE metricas_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periodo_letivo_id INT NOT NULL,
    solucao_id INT,
    total_turmas INT DEFAULT 0,
    total_alunos INT DEFAULT 0,
    total_professores INT DEFAULT 0,
    taxa_aproveitamento_salas DECIMAL(5,2),
    media_janelas_geral DECIMAL(6,2),
    num_conflitos_total INT DEFAULT 0,
    percentual_alunos_sem_janela DECIMAL(5,2),
    percentual_otimizacao DECIMAL(5,2),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_metrica_sistema (periodo_letivo_id, solucao_id),
    FOREIGN KEY (periodo_letivo_id) REFERENCES periodos_letivos(id),
    FOREIGN KEY (solucao_id) REFERENCES otimizacao_solucoes(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELAS DE RECOMENDAÇÃO
-- ============================================

-- Recomendações de disciplinas para preencher janelas
CREATE TABLE recomendacoes_disciplinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    periodo_letivo_id INT NOT NULL,
    disciplina_id INT NOT NULL,
    turma_id INT,
    score_compatibilidade DECIMAL(5,2),
    reduz_janelas INT,
    motivo_recomendacao TEXT,
    prerequisitos_ok TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (periodo_letivo_id) REFERENCES periodos_letivos(id),
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id),
    FOREIGN KEY (turma_id) REFERENCES turmas(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Simulações feitas pelos alunos
CREATE TABLE simulacoes_matricula (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    periodo_letivo_id INT NOT NULL,
    nome_simulacao VARCHAR(100),
    turmas_selecionadas JSON,
    num_janelas_resultante INT,
    pontuacao_resultante INT,
    conflitos JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (periodo_letivo_id) REFERENCES periodos_letivos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABELAS DE RELATÓRIOS
-- ============================================

-- Relatórios gerados automaticamente
CREATE TABLE relatorios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    periodo_letivo_id INT,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    dados JSON,
    formato ENUM('json', 'pdf', 'csv') DEFAULT 'json',
    caminho_arquivo VARCHAR(300),
    gerado_por INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (periodo_letivo_id) REFERENCES periodos_letivos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Alertas automáticos do sistema
CREATE TABLE alertas_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    severidade ENUM('info', 'warning', 'error', 'critical') DEFAULT 'info',
    titulo VARCHAR(200) NOT NULL,
    mensagem TEXT NOT NULL,
    entidade_tipo VARCHAR(50),
    entidade_id INT,
    periodo_letivo_id INT,
    lido TINYINT(1) DEFAULT 0,
    resolvido TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (periodo_letivo_id) REFERENCES periodos_letivos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ÍNDICES PARA PERFORMANCE
-- ============================================

CREATE INDEX idx_alunos_curso ON alunos(curso_id);
CREATE INDEX idx_alunos_status ON alunos(status);
CREATE INDEX idx_historico_aluno ON historico_academico(aluno_id);
CREATE INDEX idx_historico_disciplina ON historico_academico(disciplina_id);
CREATE INDEX idx_turmas_periodo ON turmas(periodo_letivo_id);
CREATE INDEX idx_turmas_disciplina ON turmas(disciplina_id);
CREATE INDEX idx_turma_horarios_dia ON turma_horarios(dia_semana);
CREATE INDEX idx_matriculas_aluno ON matriculas(aluno_id);
CREATE INDEX idx_matriculas_turma ON matriculas(turma_id);
CREATE INDEX idx_metricas_aluno_periodo ON metricas_aluno(aluno_id, periodo_letivo_id);
CREATE INDEX idx_professor_disponibilidade ON professor_disponibilidade(professor_id, dia_semana);
CREATE INDEX idx_recomendacoes_aluno ON recomendacoes_disciplinas(aluno_id, periodo_letivo_id);
CREATE INDEX idx_alertas_nao_lidos ON alertas_sistema(lido, severidade);

-- ============================================
-- TRIGGERS PARA VALIDAÇÕES E ATUALIZAÇÕES
-- ============================================

-- Trigger para atualizar vagas ocupadas ao inserir matrícula
DELIMITER $$

CREATE TRIGGER trg_matricula_insert 
AFTER INSERT ON matriculas
FOR EACH ROW
BEGIN
    IF NEW.status = 'matriculado' THEN
        UPDATE turmas 
        SET vagas_ocupadas = vagas_ocupadas + 1
        WHERE id = NEW.turma_id;
    END IF;
END$$

-- Trigger para atualizar vagas ao excluir matrícula
CREATE TRIGGER trg_matricula_delete 
AFTER DELETE ON matriculas
FOR EACH ROW
BEGIN
    IF OLD.status = 'matriculado' THEN
        UPDATE turmas 
        SET vagas_ocupadas = vagas_ocupadas - 1
        WHERE id = OLD.turma_id;
    END IF;
END$$

-- Trigger para atualizar vagas ao mudar status da matrícula
CREATE TRIGGER trg_matricula_update 
AFTER UPDATE ON matriculas
FOR EACH ROW
BEGIN
    IF OLD.status = 'matriculado' AND NEW.status != 'matriculado' THEN
        UPDATE turmas 
        SET vagas_ocupadas = vagas_ocupadas - 1
        WHERE id = NEW.turma_id;
    ELSEIF OLD.status != 'matriculado' AND NEW.status = 'matriculado' THEN
        UPDATE turmas 
        SET vagas_ocupadas = vagas_ocupadas + 1
        WHERE id = NEW.turma_id;
    END IF;
END$$

DELIMITER ;

-- ============================================
-- VIEWS ÚTEIS PARA CONSULTAS
-- ============================================

-- View: Grade horária completa de um aluno
CREATE OR REPLACE VIEW vw_grade_aluno AS
SELECT 
    a.id as aluno_id,
    a.nome as aluno_nome,
    a.matricula,
    d.nome as disciplina,
    d.codigo as disciplina_codigo,
    t.codigo as turma_codigo,
    p.nome as professor,
    s.codigo as sala,
    th.dia_semana,
    th.hora_inicio,
    th.hora_fim,
    pl.ano,
    pl.semestre
FROM alunos a
JOIN matriculas m ON a.id = m.aluno_id
JOIN turmas t ON m.turma_id = t.id
JOIN turma_horarios th ON t.id = th.turma_id
JOIN disciplinas d ON t.disciplina_id = d.id
JOIN professores p ON t.professor_id = p.id
JOIN salas s ON th.sala_id = s.id
JOIN periodos_letivos pl ON t.periodo_letivo_id = pl.id
WHERE m.status = 'matriculado';

-- View: Disciplinas disponíveis para um aluno (simplificada para MySQL)
CREATE OR REPLACE VIEW vw_disciplinas_disponiveis AS
SELECT 
    a.id as aluno_id,
    d.id as disciplina_id,
    d.codigo,
    d.nome,
    d.creditos,
    cd.semestre_recomendado
FROM alunos a
JOIN curso_disciplinas cd ON a.curso_id = cd.curso_id
JOIN disciplinas d ON cd.disciplina_id = d.id
WHERE d.id NOT IN (
    SELECT disciplina_id FROM historico_academico
    WHERE aluno_id = a.id AND status IN ('aprovado', 'cursando')
);

-- View: Estatísticas de janelas por aluno
CREATE OR REPLACE VIEW vw_estatisticas_janelas AS
SELECT 
    a.id as aluno_id,
    a.nome,
    a.matricula,
    COALESCE(ma.num_janelas_total, 0) as num_janelas_total,
    COALESCE(ma.num_janelas_1h, 0) as num_janelas_1h,
    COALESCE(ma.num_janelas_2h, 0) as num_janelas_2h,
    COALESCE(ma.num_janelas_3h_mais, 0) as num_janelas_3h_mais,
    COALESCE(ma.dias_sem_janela, 0) as dias_sem_janela,
    COALESCE(ma.pontuacao_janelas, 0) as pontuacao_janelas,
    COALESCE(ma.percentual_aproveitamento, 0) as percentual_aproveitamento,
    pl.ano,
    pl.semestre
FROM alunos a
LEFT JOIN metricas_aluno ma ON a.id = ma.aluno_id
LEFT JOIN periodos_letivos pl ON ma.periodo_letivo_id = pl.id
WHERE pl.status = 'ativo' OR pl.id IS NULL;

-- ============================================
-- STORED PROCEDURES ÚTEIS
-- ============================================

DELIMITER $$

-- Procedure para calcular janelas de um aluno
CREATE PROCEDURE sp_calcular_janelas_aluno(IN p_aluno_id INT, IN p_periodo_id INT)
BEGIN
    DECLARE v_num_janelas_1h INT DEFAULT 0;
    DECLARE v_num_janelas_2h INT DEFAULT 0;
    DECLARE v_num_janelas_3h INT DEFAULT 0;
    DECLARE v_pontuacao INT DEFAULT 0;
    
    -- Aqui você implementaria a lógica de cálculo
    -- Este é um exemplo simplificado
    
    SELECT 
        COUNT(*) INTO v_num_janelas_1h
    FROM (
        SELECT dia_semana, hora_fim, 
               LEAD(hora_inicio) OVER (PARTITION BY dia_semana ORDER BY hora_inicio) as prox_inicio
        FROM vw_grade_aluno
        WHERE aluno_id = p_aluno_id
    ) gaps
    WHERE TIMESTAMPDIFF(MINUTE, hora_fim, prox_inicio) = 60;
    
    -- Calcular pontuação
    SELECT peso_janela_1h INTO v_pontuacao FROM configuracao_pontuacao WHERE ativo = 1 LIMIT 1;
    SET v_pontuacao = v_pontuacao * v_num_janelas_1h;
    
    -- Inserir ou atualizar métricas
    INSERT INTO metricas_aluno (aluno_id, periodo_letivo_id, num_janelas_1h, pontuacao_janelas)
    VALUES (p_aluno_id, p_periodo_id, v_num_janelas_1h, v_pontuacao)
    ON DUPLICATE KEY UPDATE 
        num_janelas_1h = v_num_janelas_1h,
        pontuacao_janelas = v_pontuacao;
END$$

DELIMITER ;

-- ============================================
-- DADOS INICIAIS
-- ============================================

-- Configuração padrão de pontuação
INSERT INTO configuracao_pontuacao (
    nome, descricao, 
    peso_janela_1h, peso_janela_2h, peso_janela_3h_mais,
    bonus_dia_sem_janela, bonus_aulas_sequenciais,
    peso_conflito_horario, peso_preferencia_professor
) VALUES (
    'Configuração Padrão',
    'Configuração inicial do sistema de pontuação',
    -5, -15, -25,
    10, 5,
    -50, 3
);