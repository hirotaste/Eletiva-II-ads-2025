-- ============================================
-- SCHEMA COMPLETO - SISTEMA DE GESTÃO ACADÊMICA
-- Com suporte a Otimização de Horários, Métricas e Relatórios
-- ============================================

-- ============================================
-- TABELAS BASE - ESTRUTURA ACADÊMICA
-- ============================================

-- Cursos oferecidos pela instituição
CREATE TABLE cursos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    duracao_semestres INTEGER NOT NULL,
    carga_horaria_total INTEGER NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Disciplinas do catálogo
CREATE TABLE disciplinas (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(150) NOT NULL,
    carga_horaria INTEGER NOT NULL,
    creditos INTEGER NOT NULL,
    ementa TEXT,
    tipo VARCHAR(20) CHECK (tipo IN ('obrigatoria', 'optativa', 'eletiva')),
    semestre_ideal INTEGER, -- semestre recomendado no fluxo
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Relação disciplina-curso (matriz curricular)
CREATE TABLE curso_disciplinas (
    id SERIAL PRIMARY KEY,
    curso_id INTEGER REFERENCES cursos(id) ON DELETE CASCADE,
    disciplina_id INTEGER REFERENCES disciplinas(id) ON DELETE CASCADE,
    semestre_recomendado INTEGER NOT NULL,
    obrigatoria BOOLEAN DEFAULT TRUE,
    UNIQUE(curso_id, disciplina_id)
);

-- Pré-requisitos entre disciplinas
CREATE TABLE prerequisitos (
    id SERIAL PRIMARY KEY,
    disciplina_id INTEGER REFERENCES disciplinas(id) ON DELETE CASCADE,
    prerequisito_id INTEGER REFERENCES disciplinas(id) ON DELETE CASCADE,
    tipo VARCHAR(20) CHECK (tipo IN ('obrigatorio', 'recomendado')),
    UNIQUE(disciplina_id, prerequisito_id),
    CHECK (disciplina_id != prerequisito_id)
);

-- Professores
CREATE TABLE professores (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    telefone VARCHAR(20),
    titulacao VARCHAR(50),
    carga_horaria_maxima INTEGER DEFAULT 40,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Disponibilidade de horários dos professores
CREATE TABLE professor_disponibilidade (
    id SERIAL PRIMARY KEY,
    professor_id INTEGER REFERENCES professores(id) ON DELETE CASCADE,
    dia_semana INTEGER CHECK (dia_semana BETWEEN 1 AND 7), -- 1=Segunda, 7=Domingo
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    preferencia INTEGER CHECK (preferencia BETWEEN 1 AND 5), -- 1=baixa, 5=alta
    UNIQUE(professor_id, dia_semana, hora_inicio)
);

-- Disciplinas que cada professor pode lecionar
CREATE TABLE professor_disciplinas (
    id SERIAL PRIMARY KEY,
    professor_id INTEGER REFERENCES professores(id) ON DELETE CASCADE,
    disciplina_id INTEGER REFERENCES disciplinas(id) ON DELETE CASCADE,
    preferencia INTEGER CHECK (preferencia BETWEEN 1 AND 5),
    UNIQUE(professor_id, disciplina_id)
);

-- Salas de aula
CREATE TABLE salas (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    capacidade INTEGER NOT NULL,
    tipo VARCHAR(30) CHECK (tipo IN ('sala_aula', 'laboratorio', 'auditorio')),
    possui_projetor BOOLEAN DEFAULT FALSE,
    possui_ar_condicionado BOOLEAN DEFAULT FALSE,
    possui_computadores BOOLEAN DEFAULT FALSE,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Alunos
CREATE TABLE alunos (
    id SERIAL PRIMARY KEY,
    matricula VARCHAR(20) UNIQUE NOT NULL,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    data_nascimento DATE,
    telefone VARCHAR(20),
    curso_id INTEGER REFERENCES cursos(id),
    semestre_atual INTEGER NOT NULL,
    ano_ingresso INTEGER NOT NULL,
    semestre_ingresso INTEGER CHECK (semestre_ingresso IN (1, 2)),
    status VARCHAR(20) CHECK (status IN ('ativo', 'trancado', 'formado', 'desistente')),
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Histórico acadêmico do aluno
CREATE TABLE historico_academico (
    id SERIAL PRIMARY KEY,
    aluno_id INTEGER REFERENCES alunos(id) ON DELETE CASCADE,
    disciplina_id INTEGER REFERENCES disciplinas(id),
    ano INTEGER NOT NULL,
    semestre INTEGER CHECK (semestre IN (1, 2)),
    nota DECIMAL(4,2),
    frequencia DECIMAL(5,2),
    status VARCHAR(20) CHECK (status IN ('cursando', 'aprovado', 'reprovado', 'trancado')),
    is_dependencia BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(aluno_id, disciplina_id, ano, semestre)
);

-- ============================================
-- TABELAS DE GRADE HORÁRIA
-- ============================================

-- Períodos letivos
CREATE TABLE periodos_letivos (
    id SERIAL PRIMARY KEY,
    ano INTEGER NOT NULL,
    semestre INTEGER CHECK (semestre IN (1, 2)),
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    status VARCHAR(20) CHECK (status IN ('planejamento', 'ativo', 'finalizado')),
    UNIQUE(ano, semestre)
);

-- Turmas criadas para o período
CREATE TABLE turmas (
    id SERIAL PRIMARY KEY,
    periodo_letivo_id INTEGER REFERENCES periodos_letivos(id) ON DELETE CASCADE,
    disciplina_id INTEGER REFERENCES disciplinas(id),
    professor_id INTEGER REFERENCES professores(id),
    codigo VARCHAR(20) NOT NULL,
    vagas_total INTEGER NOT NULL,
    vagas_ocupadas INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(periodo_letivo_id, codigo)
);

-- Horários das turmas (uma turma pode ter múltiplos horários)
CREATE TABLE turma_horarios (
    id SERIAL PRIMARY KEY,
    turma_id INTEGER REFERENCES turmas(id) ON DELETE CASCADE,
    sala_id INTEGER REFERENCES salas(id),
    dia_semana INTEGER CHECK (dia_semana BETWEEN 1 AND 7),
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CHECK (hora_fim > hora_inicio)
);

-- Matrículas dos alunos nas turmas
CREATE TABLE matriculas (
    id SERIAL PRIMARY KEY,
    aluno_id INTEGER REFERENCES alunos(id) ON DELETE CASCADE,
    turma_id INTEGER REFERENCES turmas(id) ON DELETE CASCADE,
    status VARCHAR(20) CHECK (status IN ('matriculado', 'trancado', 'cancelado')),
    data_matricula TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_dependencia BOOLEAN DEFAULT FALSE,
    UNIQUE(aluno_id, turma_id)
);

-- ============================================
-- TABELAS DE OTIMIZAÇÃO
-- ============================================

-- Execuções do algoritmo genético
CREATE TABLE otimizacao_execucoes (
    id SERIAL PRIMARY KEY,
    periodo_letivo_id INTEGER REFERENCES periodos_letivos(id),
    algoritmo VARCHAR(50) NOT NULL,
    parametros JSONB, -- armazena configurações do AG
    fitness_inicial DECIMAL(10,4),
    fitness_final DECIMAL(10,4),
    geracoes_executadas INTEGER,
    tempo_execucao_segundos INTEGER,
    status VARCHAR(20) CHECK (status IN ('executando', 'concluido', 'erro')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    finished_at TIMESTAMP
);

-- Soluções geradas (diferentes versões de grade)
CREATE TABLE otimizacao_solucoes (
    id SERIAL PRIMARY KEY,
    execucao_id INTEGER REFERENCES otimizacao_execucoes(id) ON DELETE CASCADE,
    periodo_letivo_id INTEGER REFERENCES periodos_letivos(id),
    versao INTEGER NOT NULL,
    fitness_score DECIMAL(10,4) NOT NULL,
    num_conflitos INTEGER DEFAULT 0,
    num_janelas_total INTEGER DEFAULT 0,
    media_janelas_aluno DECIMAL(6,2),
    aprovada BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(periodo_letivo_id, versao)
);

-- Detalhes das alocações em cada solução
CREATE TABLE solucao_alocacoes (
    id SERIAL PRIMARY KEY,
    solucao_id INTEGER REFERENCES otimizacao_solucoes(id) ON DELETE CASCADE,
    turma_id INTEGER REFERENCES turmas(id),
    sala_id INTEGER REFERENCES salas(id),
    dia_semana INTEGER CHECK (dia_semana BETWEEN 1 AND 7),
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL
);

-- ============================================
-- TABELAS DE MÉTRICAS E PONTUAÇÃO
-- ============================================

-- Configuração de pesos para o sistema de pontuação
CREATE TABLE configuracao_pontuacao (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    peso_janela_1h INTEGER DEFAULT -5,
    peso_janela_2h INTEGER DEFAULT -15,
    peso_janela_3h_mais INTEGER DEFAULT -25,
    bonus_dia_sem_janela INTEGER DEFAULT 10,
    bonus_aulas_sequenciais INTEGER DEFAULT 5,
    peso_conflito_horario INTEGER DEFAULT -50,
    peso_preferencia_professor INTEGER DEFAULT 3,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Métricas calculadas por aluno
CREATE TABLE metricas_aluno (
    id SERIAL PRIMARY KEY,
    aluno_id INTEGER REFERENCES alunos(id) ON DELETE CASCADE,
    periodo_letivo_id INTEGER REFERENCES periodos_letivos(id),
    solucao_id INTEGER REFERENCES otimizacao_solucoes(id),
    num_disciplinas INTEGER DEFAULT 0,
    carga_horaria_semanal INTEGER DEFAULT 0,
    num_janelas_total INTEGER DEFAULT 0,
    num_janelas_1h INTEGER DEFAULT 0,
    num_janelas_2h INTEGER DEFAULT 0,
    num_janelas_3h_mais INTEGER DEFAULT 0,
    dias_com_aula INTEGER DEFAULT 0,
    dias_sem_janela INTEGER DEFAULT 0,
    pontuacao_janelas INTEGER DEFAULT 0,
    pontuacao_total INTEGER DEFAULT 0,
    percentual_aproveitamento DECIMAL(5,2),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(aluno_id, periodo_letivo_id, solucao_id)
);

-- Métricas agregadas por turma
CREATE TABLE metricas_turma (
    id SERIAL PRIMARY KEY,
    turma_id INTEGER REFERENCES turmas(id) ON DELETE CASCADE,
    periodo_letivo_id INTEGER REFERENCES periodos_letivos(id),
    taxa_ocupacao DECIMAL(5,2), -- vagas_ocupadas/vagas_total
    media_frequencia DECIMAL(5,2),
    taxa_aprovacao DECIMAL(5,2),
    num_alunos_dependencia INTEGER DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(turma_id, periodo_letivo_id)
);

-- Métricas agregadas por professor
CREATE TABLE metricas_professor (
    id SERIAL PRIMARY KEY,
    professor_id INTEGER REFERENCES professores(id) ON DELETE CASCADE,
    periodo_letivo_id INTEGER REFERENCES periodos_letivos(id),
    num_turmas INTEGER DEFAULT 0,
    carga_horaria_total INTEGER DEFAULT 0,
    percentual_preferencias_atendidas DECIMAL(5,2),
    pontuacao_satisfacao INTEGER DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(professor_id, periodo_letivo_id)
);

-- Métricas globais do sistema
CREATE TABLE metricas_sistema (
    id SERIAL PRIMARY KEY,
    periodo_letivo_id INTEGER REFERENCES periodos_letivos(id),
    solucao_id INTEGER REFERENCES otimizacao_solucoes(id),
    total_turmas INTEGER DEFAULT 0,
    total_alunos INTEGER DEFAULT 0,
    total_professores INTEGER DEFAULT 0,
    taxa_aproveitamento_salas DECIMAL(5,2),
    media_janelas_geral DECIMAL(6,2),
    num_conflitos_total INTEGER DEFAULT 0,
    percentual_alunos_sem_janela DECIMAL(5,2),
    percentual_otimizacao DECIMAL(5,2),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(periodo_letivo_id, solucao_id)
);

-- ============================================
-- TABELAS DE RECOMENDAÇÃO
-- ============================================

-- Recomendações de disciplinas para preencher janelas
CREATE TABLE recomendacoes_disciplinas (
    id SERIAL PRIMARY KEY,
    aluno_id INTEGER REFERENCES alunos(id) ON DELETE CASCADE,
    periodo_letivo_id INTEGER REFERENCES periodos_letivos(id),
    disciplina_id INTEGER REFERENCES disciplinas(id),
    turma_id INTEGER REFERENCES turmas(id),
    score_compatibilidade DECIMAL(5,2), -- quão bem encaixa nas janelas
    reduz_janelas INTEGER, -- quantas janelas elimina
    motivo_recomendacao TEXT,
    prerequisitos_ok BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Simulações feitas pelos alunos
CREATE TABLE simulacoes_matricula (
    id SERIAL PRIMARY KEY,
    aluno_id INTEGER REFERENCES alunos(id) ON DELETE CASCADE,
    periodo_letivo_id INTEGER REFERENCES periodos_letivos(id),
    nome_simulacao VARCHAR(100),
    turmas_selecionadas JSONB, -- array de IDs de turmas
    num_janelas_resultante INTEGER,
    pontuacao_resultante INTEGER,
    conflitos JSONB, -- array de conflitos detectados
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABELAS DE RELATÓRIOS
-- ============================================

-- Relatórios gerados automaticamente
CREATE TABLE relatorios (
    id SERIAL PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL, -- 'janelas', 'ocupacao', 'professores', etc
    periodo_letivo_id INTEGER REFERENCES periodos_letivos(id),
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    dados JSONB, -- dados do relatório em formato estruturado
    formato VARCHAR(20) CHECK (formato IN ('json', 'pdf', 'csv')),
    caminho_arquivo VARCHAR(300),
    gerado_por INTEGER, -- ID do usuário que gerou (se aplicável)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Alertas automáticos do sistema
CREATE TABLE alertas_sistema (
    id SERIAL PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    severidade VARCHAR(20) CHECK (severidade IN ('info', 'warning', 'error', 'critical')),
    titulo VARCHAR(200) NOT NULL,
    mensagem TEXT NOT NULL,
    entidade_tipo VARCHAR(50), -- 'aluno', 'professor', 'turma', etc
    entidade_id INTEGER,
    periodo_letivo_id INTEGER REFERENCES periodos_letivos(id),
    lido BOOLEAN DEFAULT FALSE,
    resolvido BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

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
-- TRIGGERS E FUNÇÕES
-- ============================================

-- Função para atualizar timestamp
CREATE OR REPLACE FUNCTION update_timestamp()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Triggers para atualização automática
CREATE TRIGGER trg_cursos_update BEFORE UPDATE ON cursos
    FOR EACH ROW EXECUTE FUNCTION update_timestamp();

CREATE TRIGGER trg_disciplinas_update BEFORE UPDATE ON disciplinas
    FOR EACH ROW EXECUTE FUNCTION update_timestamp();

CREATE TRIGGER trg_professores_update BEFORE UPDATE ON professores
    FOR EACH ROW EXECUTE FUNCTION update_timestamp();

CREATE TRIGGER trg_alunos_update BEFORE UPDATE ON alunos
    FOR EACH ROW EXECUTE FUNCTION update_timestamp();

-- Função para validar conflitos de horário
CREATE OR REPLACE FUNCTION validar_conflito_horario()
RETURNS TRIGGER AS $$
BEGIN
    -- Verifica se professor já tem aula no mesmo horário
    IF EXISTS (
        SELECT 1 FROM turma_horarios th
        JOIN turmas t ON th.turma_id = t.id
        WHERE t.professor_id = (SELECT professor_id FROM turmas WHERE id = NEW.turma_id)
        AND th.dia_semana = NEW.dia_semana
        AND th.id != NEW.id
        AND (
            (NEW.hora_inicio >= th.hora_inicio AND NEW.hora_inicio < th.hora_fim)
            OR (NEW.hora_fim > th.hora_inicio AND NEW.hora_fim <= th.hora_fim)
            OR (NEW.hora_inicio <= th.hora_inicio AND NEW.hora_fim >= th.hora_fim)
        )
    ) THEN
        RAISE EXCEPTION 'Conflito de horário: Professor já possui aula neste horário';
    END IF;

    -- Verifica se sala já está ocupada
    IF EXISTS (
        SELECT 1 FROM turma_horarios th
        WHERE th.sala_id = NEW.sala_id
        AND th.dia_semana = NEW.dia_semana
        AND th.id != NEW.id
        AND (
            (NEW.hora_inicio >= th.hora_inicio AND NEW.hora_inicio < th.hora_fim)
            OR (NEW.hora_fim > th.hora_inicio AND NEW.hora_fim <= th.hora_fim)
            OR (NEW.hora_inicio <= th.hora_inicio AND NEW.hora_fim >= th.hora_fim)
        )
    ) THEN
        RAISE EXCEPTION 'Conflito de horário: Sala já está ocupada neste horário';
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_validar_conflito_horario 
    BEFORE INSERT OR UPDATE ON turma_horarios
    FOR EACH ROW EXECUTE FUNCTION validar_conflito_horario();

-- Função para atualizar vagas ocupadas
CREATE OR REPLACE FUNCTION atualizar_vagas_turma()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' AND NEW.status = 'matriculado' THEN
        UPDATE turmas SET vagas_ocupadas = vagas_ocupadas + 1
        WHERE id = NEW.turma_id;
    ELSIF TG_OP = 'DELETE' AND OLD.status = 'matriculado' THEN
        UPDATE turmas SET vagas_ocupadas = vagas_ocupadas - 1
        WHERE id = OLD.turma_id;
    ELSIF TG_OP = 'UPDATE' THEN
        IF OLD.status = 'matriculado' AND NEW.status != 'matriculado' THEN
            UPDATE turmas SET vagas_ocupadas = vagas_ocupadas - 1
            WHERE id = NEW.turma_id;
        ELSIF OLD.status != 'matriculado' AND NEW.status = 'matriculado' THEN
            UPDATE turmas SET vagas_ocupadas = vagas_ocupadas + 1
            WHERE id = NEW.turma_id;
        END IF;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_atualizar_vagas 
    AFTER INSERT OR UPDATE OR DELETE ON matriculas
    FOR EACH ROW EXECUTE FUNCTION atualizar_vagas_turma();

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

-- View: Disciplinas disponíveis para um aluno
CREATE OR REPLACE VIEW vw_disciplinas_disponiveis AS
SELECT 
    a.id as aluno_id,
    d.id as disciplina_id,
    d.codigo,
    d.nome,
    d.creditos,
    cd.semestre_recomendado,
    CASE 
        WHEN EXISTS (
            SELECT 1 FROM prerequisitos pr
            WHERE pr.disciplina_id = d.id
            AND pr.prerequisito_id NOT IN (
                SELECT disciplina_id FROM historico_academico
                WHERE aluno_id = a.id AND status = 'aprovado'
            )
        ) THEN FALSE
        ELSE TRUE
    END as prerequisitos_ok
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
    ma.num_janelas_total,
    ma.num_janelas_1h,
    ma.num_janelas_2h,
    ma.num_janelas_3h_mais,
    ma.dias_sem_janela,
    ma.pontuacao_janelas,
    ma.percentual_aproveitamento,
    pl.ano,
    pl.semestre
FROM alunos a
LEFT JOIN metricas_aluno ma ON a.id = ma.aluno_id
LEFT JOIN periodos_letivos pl ON ma.periodo_letivo_id = pl.id
WHERE pl.status = 'ativo' OR pl.id IS NULL;

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

-- ============================================
-- COMENTÁRIOS DAS TABELAS
-- ============================================

COMMENT ON TABLE cursos IS 'Cursos oferecidos pela instituição';
COMMENT ON TABLE disciplinas IS 'Catálogo de disciplinas disponíveis';
COMMENT ON TABLE turmas IS 'Turmas abertas em cada período letivo';
COMMENT ON TABLE metricas_aluno IS 'Métricas individualizadas de cada aluno incluindo contagem de janelas';
COMMENT ON TABLE otimizacao_execucoes IS 'Registro de execuções do algoritmo de otimização';
COMMENT ON TABLE recomendacoes_disciplinas IS 'Sistema de recomendação para preenchimento de janelas';
COMMENT ON TABLE alertas_sistema IS 'Alertas automáticos gerados pelo sistema';