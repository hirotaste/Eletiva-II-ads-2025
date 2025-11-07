-- ============================================
-- SCRIPT DE INSERÇÃO DE DADOS DE EXEMPLO
-- Sistema de Gestão Acadêmica
-- 5 tuplas por tabela com referências coerentes
-- ============================================

USE academico;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

-- ============================================
-- INSERÇÃO DE DADOS BASE
-- ============================================

-- Inserir Métricas do Sistema
INSERT INTO metricas_sistema (periodo_letivo_id, solucao_id, total_turmas, total_alunos, total_professores, taxa_aproveitamento_salas, media_janelas_geral, num_conflitos_total, percentual_alunos_sem_janela, percentual_otimizacao) VALUES
(1, 1, 7, 5, 5, 75.50, 0.20, 0, 60.00, 95.50),
(1, 2, 7, 5, 5, 78.20, 0.15, 0, 80.00, 97.80),
(3, 3, 8, 12, 5, 72.00, 0.40, 1, 50.00, 92.00),
(4, 4, 10, 15, 6, 70.00, 0.48, 0, 45.00, 90.00),
(1, 5, 7, 5, 5, 65.00, 1.80, 15, 20.00, 45.00);

-- ============================================
-- RECOMENDAÇÕES
-- ============================================

-- Inserir Recomendações de Disciplinas
-- Para Lucas Fernandes (já fez ALG101, ALG102 - pode fazer optativas)
INSERT INTO recomendacoes_disciplinas (aluno_id, periodo_letivo_id, disciplina_id, turma_id, score_compatibilidade, reduz_janelas, motivo_recomendacao, prerequisitos_ok) VALUES
(1, 1, 5, 5, 85.50, 0, 'Disciplina optativa que complementa POO. Horário: Sexta 08:00-12:00', 1),
(1, 1, 7, 6, 90.00, 2, 'Reduz janelas nas segundas e quartas. Horário compatível com sua grade', 1);

-- Para Julia Mendes
INSERT INTO recomendacoes_disciplinas (aluno_id, periodo_letivo_id, disciplina_id, turma_id, score_compatibilidade, reduz_janelas, motivo_recomendacao, prerequisitos_ok) VALUES
(2, 1, 5, 5, 88.00, 0, 'Desenvolva habilidades em front-end. Sexta 08:00-12:00', 1),
(2, 1, 7, 6, 92.50, 2, 'Elimina janelas e é fundamental para sua formação', 1);

-- Para Bruno Cardoso
INSERT INTO recomendacoes_disciplinas (aluno_id, periodo_letivo_id, disciplina_id, turma_id, score_compatibilidade, reduz_janelas, motivo_recomendacao, prerequisitos_ok) VALUES
(5, 1, 6, NULL, 75.00, 0, 'IA complementa suas habilidades. Aguardando abertura de turma', 1);

-- Para Amanda Souza
INSERT INTO recomendacoes_disciplinas (aluno_id, periodo_letivo_id, disciplina_id, turma_id, score_compatibilidade, reduz_janelas, motivo_recomendacao, prerequisitos_ok) VALUES
(4, 1, 1, 1, 95.00, 2, 'Preenche janelas nas segundas e quartas. Fundamento essencial', 1);

-- Para Rafael Lima
INSERT INTO recomendacoes_disciplinas (aluno_id, periodo_letivo_id, disciplina_id, turma_id, score_compatibilidade, reduz_janelas, motivo_recomendacao, prerequisitos_ok) VALUES
(3, 1, 5, 5, 80.00, 0, 'Complementa conhecimentos de engenharia com desenvolvimento prático', 1);

-- Inserir Simulações de Matrícula
-- Lucas simulando adicionar mais disciplinas
INSERT INTO simulacoes_matricula (aluno_id, periodo_letivo_id, nome_simulacao, turmas_selecionadas, num_janelas_resultante, pontuacao_resultante, conflitos) VALUES
(1, 1, 'Simulação 1 - Adicionar WEB101', '[3, 4, 5]', 0, 90, '[]'),
(1, 1, 'Simulação 2 - Adicionar ENG101', '[3, 4, 6]', 0, 95, '[]');

-- Julia simulando
INSERT INTO simulacoes_matricula (aluno_id, periodo_letivo_id, nome_simulacao, turmas_selecionadas, num_janelas_resultante, pontuacao_resultante, conflitos) VALUES
(2, 1, 'Grade completa com optativas', '[3, 4, 5, 6]', 0, 110, '[]');

-- Bruno verificando conflitos
INSERT INTO simulacoes_matricula (aluno_id, periodo_letivo_id, nome_simulacao, turmas_selecionadas, num_janelas_resultante, pontuacao_resultante, conflitos) VALUES
(5, 1, 'Tentar adicionar POO101', '[3, 4, 7]', 2, -5, '[{"tipo": "horario", "msg": "Conflito POO101 com REDES101 na Quinta"}]');

-- Amanda planejando próximo semestre
INSERT INTO simulacoes_matricula (aluno_id, periodo_letivo_id, nome_simulacao, turmas_selecionadas, num_janelas_resultante, pontuacao_resultante, conflitos) VALUES
(4, 1, 'Grade ideal semestre atual', '[1, 2]', 4, -20, '[{"tipo": "janela", "msg": "4 janelas de 2h detectadas"}]');

-- Rafael otimizando grade
INSERT INTO simulacoes_matricula (aluno_id, periodo_letivo_id, nome_simulacao, turmas_selecionadas, num_janelas_resultante, pontuacao_resultante, conflitos) VALUES
(3, 1, 'Adicionar WEB101', '[5, 6]', 1, 15, '[]');

-- ============================================
-- RELATÓRIOS
-- ============================================

-- Inserir Relatórios Gerados
INSERT INTO relatorios (tipo, periodo_letivo_id, titulo, descricao, dados, formato, caminho_arquivo, gerado_por) VALUES
(
    'janelas',
    1,
    'Relatório de Janelas - 2024.1',
    'Análise detalhada das janelas por aluno no período 2024.1',
    '{"total_alunos": 5, "alunos_sem_janela": 3, "media_janelas": 0.6, "maior_janela": "2h"}',
    'json',
    '/reports/janelas_2024_1.json',
    NULL
),
(
    'ocupacao',
    1,
    'Relatório de Ocupação de Salas - 2024.1',
    'Taxa de utilização das salas no período',
    '{"salas_analisadas": 5, "taxa_media": 75.5, "sala_mais_usada": "A101", "sala_menos_usada": "AUD01"}',
    'pdf',
    '/reports/ocupacao_2024_1.pdf',
    NULL
),
(
    'professores',
    1,
    'Relatório de Carga Horária Docente - 2024.1',
    'Distribuição de carga horária entre professores',
    '{"total_professores": 5, "carga_media": 12.8, "professor_maior_carga": "Dr. Carlos Silva"}',
    'csv',
    '/reports/professores_2024_1.csv',
    NULL
),
(
    'metricas',
    1,
    'Métricas Gerais do Sistema - 2024.1',
    'Dashboard executivo com indicadores principais',
    '{"satisfacao_geral": 92.5, "conflitos": 0, "otimizacao": 95.5}',
    'json',
    '/reports/metricas_2024_1.json',
    NULL
),
(
    'aproveitamento',
    1,
    'Análise de Aproveitamento Acadêmico',
    'Relação entre janelas e desempenho dos alunos',
    '{"correlacao_janelas_notas": -0.65, "alunos_analisados": 5}',
    'pdf',
    '/reports/aproveitamento_2024_1.pdf',
    NULL
);

-- ============================================
-- ALERTAS DO SISTEMA
-- ============================================

-- Inserir Alertas
INSERT INTO alertas_sistema (tipo, severidade, titulo, mensagem, entidade_tipo, entidade_id, periodo_letivo_id, lido, resolvido) VALUES
(
    'janela_excessiva',
    'warning',
    'Aluno com janelas excessivas',
    'O aluno Bruno Cardoso possui 1 janela de 2 horas em sua grade. Recomenda-se revisar possibilidades de otimização.',
    'aluno',
    5,
    1,
    0,
    0
),
(
    'dependencia',
    'info',
    'Aluno em dependência',
    'Bruno Cardoso está cursando BD101 como dependência. Atenção especial ao acompanhamento.',
    'aluno',
    5,
    1,
    1,
    0
),
(
    'sala_subutilizada',
    'info',
    'Sala com baixa ocupação',
    'O Auditório Principal (AUD01) está subutilizado no período 2024.1. Taxa de ocupação: 10%',
    'sala',
    5,
    1,
    0,
    0
),
(
    'vaga_disponivel',
    'info',
    'Vagas disponíveis em WEB101',
    'A turma WEB101-A possui 30 vagas disponíveis. Considere divulgação para os alunos.',
    'turma',
    5,
    1,
    1,
    1
),
(
    'otimizacao_concluida',
    'info',
    'Otimização de grade finalizada',
    'Algoritmo Genético executado com sucesso. Fitness final: 910.30. 0 conflitos detectados.',
    NULL,
    NULL,
    1,
    1,
    1
);

-- ============================================
-- DADOS ADICIONAIS DE SUPORTE
-- ============================================

-- Inserir mais 5 alertas para completar variedade
INSERT INTO alertas_sistema (tipo, severidade, titulo, mensagem, entidade_tipo, entidade_id, periodo_letivo_id, lido, resolvido) VALUES
(
    'conflito_horario',
    'critical',
    'Conflito detectado na grade',
    'Possível conflito de horário detectado na alocação inicial. Executar otimização.',
    NULL,
    NULL,
    2,
    0,
    0
),
(
    'carga_excessiva',
    'warning',
    'Professor com carga horária elevada',
    'Dr. Carlos Silva está próximo do limite de carga horária (16h de 40h máximas).',
    'professor',
    1,
    1,
    0,
    0
),
(
    'prerequisito_pendente',
    'error',
    'Tentativa de matrícula sem pré-requisito',
    'Aluno tentou se matricular em IA101 sem ter concluído POO101.',
    'aluno',
    4,
    1,
    1,
    1
),
(
    'sala_capacidade',
    'warning',
    'Sala próxima da capacidade máxima',
    'Turma BD101-A com 37 alunos de 40 vagas. Atenção para novas matrículas.',
    'turma',
    3,
    1,
    0,
    0
),
(
    'periodo_iniciado',
    'info',
    'Período letivo iniciado',
    'O período 2024.1 foi iniciado em 05/02/2024. Todas as turmas estão ativas.',
    NULL,
    NULL,
    1,
    1,
    1
);

-- ============================================
-- COMMIT E VERIFICAÇÕES FINAIS
-- ============================================

COMMIT;

-- Exibir resumo dos dados inseridos
SELECT 'Dados inseridos com sucesso!' AS status;

SELECT 
    'Cursos' AS tabela, 
    COUNT(*) AS total_registros 
FROM cursos
UNION ALL
SELECT 'Disciplinas', COUNT(*) FROM disciplinas
UNION ALL
SELECT 'Professores', COUNT(*) FROM professores
UNION ALL
SELECT 'Alunos', COUNT(*) FROM alunos
UNION ALL
SELECT 'Salas', COUNT(*) FROM salas
UNION ALL
SELECT 'Turmas', COUNT(*) FROM turmas
UNION ALL
SELECT 'Matrículas', COUNT(*) FROM matriculas
UNION ALL
SELECT 'Períodos Letivos', COUNT(*) FROM periodos_letivos
UNION ALL
SELECT 'Execuções Otimização', COUNT(*) FROM otimizacao_execucoes
UNION ALL
SELECT 'Soluções', COUNT(*) FROM otimizacao_solucoes
UNION ALL
SELECT 'Métricas Aluno', COUNT(*) FROM metricas_aluno
UNION ALL
SELECT 'Relatórios', COUNT(*) FROM relatorios
UNION ALL
SELECT 'Alertas Sistema', COUNT(*) FROM alertas_sistema;

-- Verificar integridade referencial
SELECT 'Verificando integridade referencial...' AS status;

SELECT 
    'Matrículas com alunos válidos' AS verificacao,
    COUNT(*) AS total
FROM matriculas m
JOIN alunos a ON m.aluno_id = a.id;

SELECT 
    'Turmas com professores válidos' AS verificacao,
    COUNT(*) AS total
FROM turmas t
JOIN professores p ON t.professor_id = p.id;

SELECT 
    'Horários com salas válidas' AS verificacao,
    COUNT(*) AS total
FROM turma_horarios th
JOIN salas s ON th.sala_id = s.id;

SELECT '=== Inserção de dados concluída com sucesso! ===' AS mensagem_final;ir Cursos
INSERT INTO cursos (nome, codigo, duracao_semestres, carga_horaria_total, ativo) VALUES
('Ciência da Computação', 'CC', 8, 3200, 1),
('Engenharia de Software', 'ES', 8, 3400, 1),
('Sistemas de Informação', 'SI', 8, 3000, 1),
('Análise e Desenvolvimento de Sistemas', 'ADS', 6, 2400, 1),
('Redes de Computadores', 'RC', 6, 2600, 1);

-- Inserir Disciplinas
INSERT INTO disciplinas (codigo, nome, carga_horaria, creditos, ementa, tipo, semestre_ideal, ativo) VALUES
('ALG101', 'Algoritmos e Programação I', 80, 4, 'Introdução à lógica de programação e algoritmos básicos', 'obrigatoria', 1, 1),
('ALG102', 'Algoritmos e Programação II', 80, 4, 'Estruturas de dados e algoritmos avançados', 'obrigatoria', 2, 1),
('BD101', 'Banco de Dados I', 80, 4, 'Fundamentos de banco de dados e SQL', 'obrigatoria', 3, 1),
('POO101', 'Programação Orientada a Objetos', 80, 4, 'Conceitos e práticas de POO', 'obrigatoria', 3, 1),
('WEB101', 'Desenvolvimento Web', 60, 3, 'HTML, CSS, JavaScript e frameworks modernos', 'optativa', 4, 1),
('IA101', 'Inteligência Artificial', 60, 3, 'Fundamentos de IA e aprendizado de máquina', 'optativa', 6, 1),
('ENG101', 'Engenharia de Software', 80, 4, 'Processos e metodologias de desenvolvimento', 'obrigatoria', 4, 1),
('REDES101', 'Redes de Computadores', 80, 4, 'Arquitetura e protocolos de redes', 'obrigatoria', 5, 1),
('SEG101', 'Segurança da Informação', 60, 3, 'Princípios e técnicas de segurança', 'optativa', 6, 1),
('MAT101', 'Matemática Discreta', 80, 4, 'Lógica matemática e teoria dos conjuntos', 'obrigatoria', 1, 1);

-- Inserir Matriz Curricular (curso_disciplinas)
-- Ciência da Computação
INSERT INTO curso_disciplinas (curso_id, disciplina_id, semestre_recomendado, obrigatoria) VALUES
(1, 1, 1, 1),  -- ALG101
(1, 10, 1, 1), -- MAT101
(1, 2, 2, 1),  -- ALG102
(1, 3, 3, 1),  -- BD101
(1, 4, 3, 1),  -- POO101
(1, 7, 4, 1),  -- ENG101
(1, 5, 4, 0),  -- WEB101 (optativa)
(1, 8, 5, 1),  -- REDES101
(1, 6, 6, 0),  -- IA101 (optativa)
(1, 9, 6, 0);  -- SEG101 (optativa)

-- Engenharia de Software
INSERT INTO curso_disciplinas (curso_id, disciplina_id, semestre_recomendado, obrigatoria) VALUES
(2, 1, 1, 1),
(2, 10, 1, 1),
(2, 2, 2, 1),
(2, 4, 3, 1),
(2, 7, 3, 1);

-- Sistemas de Informação
INSERT INTO curso_disciplinas (curso_id, disciplina_id, semestre_recomendado, obrigatoria) VALUES
(3, 1, 1, 1),
(3, 2, 2, 1),
(3, 3, 3, 1),
(3, 5, 4, 1),
(3, 8, 5, 1);

-- ADS
INSERT INTO curso_disciplinas (curso_id, disciplina_id, semestre_recomendado, obrigatoria) VALUES
(4, 1, 1, 1),
(4, 2, 2, 1),
(4, 3, 3, 1),
(4, 4, 3, 1),
(4, 5, 4, 1);

-- Redes de Computadores
INSERT INTO curso_disciplinas (curso_id, disciplina_id, semestre_recomendado, obrigatoria) VALUES
(5, 1, 1, 1),
(5, 2, 2, 1),
(5, 8, 3, 1),
(5, 9, 4, 1),
(5, 3, 4, 1);

-- Inserir Pré-requisitos
INSERT INTO prerequisitos (disciplina_id, prerequisito_id, tipo) VALUES
(2, 1, 'obrigatorio'),   -- ALG102 requer ALG101
(3, 2, 'obrigatorio'),   -- BD101 requer ALG102
(4, 2, 'obrigatorio'),   -- POO101 requer ALG102
(7, 4, 'recomendado'),   -- ENG101 recomenda POO101
(6, 4, 'obrigatorio');   -- IA101 requer POO101

-- Inserir Professores
INSERT INTO professores (nome, email, cpf, telefone, titulacao, carga_horaria_maxima, ativo) VALUES
('Dr. Carlos Silva', 'carlos.silva@universidade.edu.br', '123.456.789-01', '(18) 98765-4321', 'Doutorado', 40, 1),
('Dra. Maria Santos', 'maria.santos@universidade.edu.br', '234.567.890-12', '(18) 98765-4322', 'Doutorado', 40, 1),
('Me. João Oliveira', 'joao.oliveira@universidade.edu.br', '345.678.901-23', '(18) 98765-4323', 'Mestrado', 32, 1),
('Me. Ana Costa', 'ana.costa@universidade.edu.br', '456.789.012-34', '(18) 98765-4324', 'Mestrado', 32, 1),
('Dr. Pedro Almeida', 'pedro.almeida@universidade.edu.br', '567.890.123-45', '(18) 98765-4325', 'Doutorado', 40, 1);

-- Inserir Disponibilidade dos Professores
-- Dr. Carlos Silva (Segunda a Sexta, manhã e tarde)
INSERT INTO professor_disponibilidade (professor_id, dia_semana, hora_inicio, hora_fim, preferencia) VALUES
(1, 2, '08:00:00', '12:00:00', 5), -- Segunda manhã
(1, 2, '14:00:00', '18:00:00', 4), -- Segunda tarde
(1, 3, '08:00:00', '12:00:00', 5), -- Terça manhã
(1, 4, '08:00:00', '12:00:00', 4), -- Quarta manhã
(1, 5, '14:00:00', '18:00:00', 3); -- Quinta tarde

-- Dra. Maria Santos
INSERT INTO professor_disponibilidade (professor_id, dia_semana, hora_inicio, hora_fim, preferencia) VALUES
(2, 2, '08:00:00', '12:00:00', 4),
(2, 3, '08:00:00', '12:00:00', 5),
(2, 3, '14:00:00', '18:00:00', 5),
(2, 4, '08:00:00', '12:00:00', 4),
(2, 5, '08:00:00', '12:00:00', 3);

-- Me. João Oliveira
INSERT INTO professor_disponibilidade (professor_id, dia_semana, hora_inicio, hora_fim, preferencia) VALUES
(3, 2, '14:00:00', '18:00:00', 5),
(3, 3, '14:00:00', '18:00:00', 5),
(3, 4, '14:00:00', '18:00:00', 4),
(3, 5, '08:00:00', '12:00:00', 4),
(3, 6, '08:00:00', '12:00:00', 3);

-- Me. Ana Costa
INSERT INTO professor_disponibilidade (professor_id, dia_semana, hora_inicio, hora_fim, preferencia) VALUES
(4, 2, '08:00:00', '12:00:00', 5),
(4, 2, '14:00:00', '18:00:00', 4),
(4, 4, '08:00:00', '12:00:00', 5),
(4, 4, '14:00:00', '18:00:00', 4),
(4, 6, '08:00:00', '12:00:00', 3);

-- Dr. Pedro Almeida
INSERT INTO professor_disponibilidade (professor_id, dia_semana, hora_inicio, hora_fim, preferencia) VALUES
(5, 3, '08:00:00', '12:00:00', 5),
(5, 3, '14:00:00', '18:00:00', 5),
(5, 4, '08:00:00', '12:00:00', 4),
(5, 5, '08:00:00', '12:00:00', 4),
(5, 5, '14:00:00', '18:00:00', 3);

-- Inserir Disciplinas que Professores podem lecionar
INSERT INTO professor_disciplinas (professor_id, disciplina_id, preferencia) VALUES
-- Dr. Carlos Silva - Especialista em Algoritmos
(1, 1, 5), -- ALG101
(1, 2, 5), -- ALG102
(1, 10, 4), -- MAT101

-- Dra. Maria Santos - Especialista em BD e POO
(2, 3, 5), -- BD101
(2, 4, 5), -- POO101
(2, 7, 4), -- ENG101

-- Me. João Oliveira - Desenvolvimento Web e IA
(3, 5, 5), -- WEB101
(3, 6, 4), -- IA101
(3, 4, 3), -- POO101

-- Me. Ana Costa - Redes e Segurança
(4, 8, 5), -- REDES101
(4, 9, 5), -- SEG101
(4, 3, 3), -- BD101

-- Dr. Pedro Almeida - Engenharia e Algoritmos
(5, 7, 5), -- ENG101
(5, 2, 4), -- ALG102
(5, 4, 4); -- POO101

-- Inserir Salas
INSERT INTO salas (codigo, nome, capacidade, tipo, possui_projetor, possui_ar_condicionado, possui_computadores, ativo) VALUES
('A101', 'Sala de Aula A101', 40, 'sala_aula', 1, 1, 0, 1),
('A102', 'Sala de Aula A102', 40, 'sala_aula', 1, 1, 0, 1),
('LAB01', 'Laboratório de Informática 1', 30, 'laboratorio', 1, 1, 1, 1),
('LAB02', 'Laboratório de Informática 2', 30, 'laboratorio', 1, 1, 1, 1),
('AUD01', 'Auditório Principal', 100, 'auditorio', 1, 1, 0, 1);

-- Inserir Alunos
INSERT INTO alunos (matricula, nome, email, cpf, data_nascimento, telefone, curso_id, semestre_atual, ano_ingresso, semestre_ingresso, status, ativo) VALUES
('2024001', 'Lucas Fernandes', 'lucas.fernandes@aluno.edu.br', '111.222.333-44', '2003-05-15', '(18) 91234-5678', 1, 3, 2023, 1, 'ativo', 1),
('2024002', 'Julia Mendes', 'julia.mendes@aluno.edu.br', '222.333.444-55', '2004-08-20', '(18) 91234-5679', 1, 3, 2023, 1, 'ativo', 1),
('2024003', 'Rafael Lima', 'rafael.lima@aluno.edu.br', '333.444.555-66', '2002-03-10', '(18) 91234-5680', 2, 4, 2022, 2, 'ativo', 1),
('2024004', 'Amanda Souza', 'amanda.souza@aluno.edu.br', '444.555.666-77', '2003-11-25', '(18) 91234-5681', 3, 2, 2024, 1, 'ativo', 1),
('2024005', 'Bruno Cardoso', 'bruno.cardoso@aluno.edu.br', '555.666.777-88', '2001-07-30', '(18) 91234-5682', 1, 5, 2022, 1, 'ativo', 1);

-- Inserir Histórico Acadêmico
-- Aluno Lucas Fernandes (semestre 3)
INSERT INTO historico_academico (aluno_id, disciplina_id, ano, semestre, nota, frequencia, status, is_dependencia) VALUES
(1, 1, 2023, 1, 8.5, 95.0, 'aprovado', 0),  -- ALG101
(1, 10, 2023, 1, 7.0, 90.0, 'aprovado', 0), -- MAT101
(1, 2, 2023, 2, 9.0, 92.0, 'aprovado', 0),  -- ALG102
(1, 3, 2024, 1, NULL, NULL, 'cursando', 0), -- BD101
(1, 4, 2024, 1, NULL, NULL, 'cursando', 0); -- POO101

-- Aluna Julia Mendes (semestre 3)
INSERT INTO historico_academico (aluno_id, disciplina_id, ano, semestre, nota, frequencia, status, is_dependencia) VALUES
(2, 1, 2023, 1, 9.5, 98.0, 'aprovado', 0),
(2, 10, 2023, 1, 8.0, 95.0, 'aprovado', 0),
(2, 2, 2023, 2, 8.5, 93.0, 'aprovado', 0),
(2, 3, 2024, 1, NULL, NULL, 'cursando', 0),
(2, 4, 2024, 1, NULL, NULL, 'cursando', 0);

-- Aluno Rafael Lima (semestre 4)
INSERT INTO historico_academico (aluno_id, disciplina_id, ano, semestre, nota, frequencia, status, is_dependencia) VALUES
(3, 1, 2022, 2, 7.5, 88.0, 'aprovado', 0),
(3, 10, 2022, 2, 6.5, 85.0, 'aprovado', 0),
(3, 2, 2023, 1, 8.0, 90.0, 'aprovado', 0),
(3, 4, 2023, 2, 7.0, 87.0, 'aprovado', 0),
(3, 7, 2024, 1, NULL, NULL, 'cursando', 0);

-- Aluna Amanda Souza (semestre 2)
INSERT INTO historico_academico (aluno_id, disciplina_id, ano, semestre, nota, frequencia, status, is_dependencia) VALUES
(4, 1, 2024, 1, NULL, NULL, 'cursando', 0),
(4, 2, 2024, 1, NULL, NULL, 'cursando', 0);

-- Aluno Bruno Cardoso (semestre 5) - tem dependência
INSERT INTO historico_academico (aluno_id, disciplina_id, ano, semestre, nota, frequencia, status, is_dependencia) VALUES
(5, 1, 2022, 1, 8.0, 92.0, 'aprovado', 0),
(5, 10, 2022, 1, 7.5, 88.0, 'aprovado', 0),
(5, 2, 2022, 2, 8.5, 94.0, 'aprovado', 0),
(5, 3, 2023, 1, 5.5, 70.0, 'reprovado', 0), -- Reprovado em BD101
(5, 4, 2023, 1, 7.5, 89.0, 'aprovado', 0),
(5, 7, 2023, 2, 8.0, 91.0, 'aprovado', 0),
(5, 8, 2024, 1, NULL, NULL, 'cursando', 0),
(5, 3, 2024, 1, NULL, NULL, 'cursando', 1); -- Fazendo BD101 novamente (dependência)

-- ============================================
-- GRADE HORÁRIA
-- ============================================

-- Inserir Período Letivo
INSERT INTO periodos_letivos (ano, semestre, data_inicio, data_fim, status) VALUES
(2024, 1, '2024-02-05', '2024-06-28', 'ativo'),
(2024, 2, '2024-08-01', '2024-12-15', 'planejamento'),
(2023, 2, '2023-08-01', '2023-12-15', 'finalizado'),
(2023, 1, '2023-02-06', '2023-06-30', 'finalizado'),
(2025, 1, '2025-02-03', '2025-06-27', 'planejamento');

-- Inserir Turmas (período 2024.1 - ativo)
INSERT INTO turmas (periodo_letivo_id, disciplina_id, professor_id, codigo, vagas_total, vagas_ocupadas) VALUES
(1, 1, 1, 'ALG101-A', 40, 2),  -- ALG101 com Dr. Carlos
(1, 2, 1, 'ALG102-A', 40, 1),  -- ALG102 com Dr. Carlos
(1, 3, 2, 'BD101-A', 40, 3),   -- BD101 com Dra. Maria
(1, 4, 2, 'POO101-A', 40, 2),  -- POO101 com Dra. Maria
(1, 5, 3, 'WEB101-A', 30, 0);  -- WEB101 com Me. João

-- Inserir mais turmas para completar 5
INSERT INTO turmas (periodo_letivo_id, disciplina_id, professor_id, codigo, vagas_total, vagas_ocupadas) VALUES
(1, 7, 5, 'ENG101-A', 40, 1),  -- ENG101 com Dr. Pedro
(1, 8, 4, 'REDES101-A', 40, 1), -- REDES101 com Me. Ana
(2, 1, 1, 'ALG101-B', 40, 0),  -- Próximo semestre
(2, 2, 1, 'ALG102-B', 40, 0),
(3, 1, 1, 'ALG101-C', 40, 0);  -- Semestre passado

-- Inserir Horários das Turmas
-- ALG101-A: Segunda e Quarta 08:00-10:00
INSERT INTO turma_horarios (turma_id, sala_id, dia_semana, hora_inicio, hora_fim) VALUES
(1, 1, 2, '08:00:00', '10:00:00'), -- Segunda
(1, 1, 4, '08:00:00', '10:00:00'); -- Quarta

-- ALG102-A: Terça e Quinta 08:00-10:00
INSERT INTO turma_horarios (turma_id, sala_id, dia_semana, hora_inicio, hora_fim) VALUES
(2, 1, 3, '08:00:00', '10:00:00'), -- Terça
(2, 1, 5, '08:00:00', '10:00:00'); -- Quinta

-- BD101-A: Segunda e Quarta 10:00-12:00
INSERT INTO turma_horarios (turma_id, sala_id, dia_semana, hora_inicio, hora_fim) VALUES
(3, 2, 2, '10:00:00', '12:00:00'), -- Segunda
(3, 2, 4, '10:00:00', '12:00:00'); -- Quarta

-- POO101-A: Terça e Quinta 14:00-16:00
INSERT INTO turma_horarios (turma_id, sala_id, dia_semana, hora_inicio, hora_fim) VALUES
(4, 3, 3, '14:00:00', '16:00:00'), -- Terça
(4, 3, 5, '14:00:00', '16:00:00'); -- Quinta

-- WEB101-A: Sexta 08:00-12:00 (aula única semanal)
INSERT INTO turma_horarios (turma_id, sala_id, dia_semana, hora_inicio, hora_fim) VALUES
(5, 3, 6, '08:00:00', '12:00:00'); -- Sexta

-- ENG101-A: Segunda e Quarta 14:00-16:00
INSERT INTO turma_horarios (turma_id, sala_id, dia_semana, hora_inicio, hora_fim) VALUES
(6, 2, 2, '14:00:00', '16:00:00'),
(6, 2, 4, '14:00:00', '16:00:00');

-- REDES101-A: Terça e Quinta 10:00-12:00
INSERT INTO turma_horarios (turma_id, sala_id, dia_semana, hora_inicio, hora_fim) VALUES
(7, 1, 3, '10:00:00', '12:00:00'),
(7, 1, 5, '10:00:00', '12:00:00');

-- Inserir Matrículas
-- Lucas Fernandes
INSERT INTO matriculas (aluno_id, turma_id, status, is_dependencia) VALUES
(1, 3, 'matriculado', 0), -- BD101
(1, 4, 'matriculado', 0); -- POO101

-- Julia Mendes
INSERT INTO matriculas (aluno_id, turma_id, status, is_dependencia) VALUES
(2, 3, 'matriculado', 0), -- BD101
(2, 4, 'matriculado', 0); -- POO101

-- Rafael Lima
INSERT INTO matriculas (aluno_id, turma_id, status, is_dependencia) VALUES
(3, 6, 'matriculado', 0); -- ENG101

-- Amanda Souza
INSERT INTO matriculas (aluno_id, turma_id, status, is_dependencia) VALUES
(4, 2, 'matriculado', 0); -- ALG102

-- Bruno Cardoso
INSERT INTO matriculas (aluno_id, turma_id, status, is_dependencia) VALUES
(5, 3, 'matriculado', 1), -- BD101 (dependência)
(5, 7, 'matriculado', 0); -- REDES101

-- ============================================
-- OTIMIZAÇÃO
-- ============================================

-- Inserir Execuções de Otimização
INSERT INTO otimizacao_execucoes (periodo_letivo_id, algoritmo, parametros, fitness_inicial, fitness_final, geracoes_executadas, tempo_execucao_segundos, status, finished_at) VALUES
(1, 'Algoritmo Genético', '{"populacao": 100, "geracoes": 500, "taxa_mutacao": 0.1}', 245.50, 892.75, 500, 120, 'concluido', NOW()),
(1, 'Algoritmo Genético', '{"populacao": 150, "geracoes": 800, "taxa_mutacao": 0.15}', 250.00, 910.30, 800, 180, 'concluido', NOW()),
(2, 'Algoritmo Genético', '{"populacao": 100, "geracoes": 300, "taxa_mutacao": 0.1}', NULL, NULL, NULL, NULL, 'executando', NULL),
(3, 'Algoritmo Genético', '{"populacao": 120, "geracoes": 600, "taxa_mutacao": 0.12}', 230.00, 875.20, 600, 150, 'concluido', '2023-07-15 14:30:00'),
(4, 'Busca Tabu', '{"tamanho_tabu": 50, "iteracoes": 1000}', 240.00, 850.00, 1000, 95, 'concluido', '2023-01-20 10:15:00');

-- Inserir Soluções de Otimização
INSERT INTO otimizacao_solucoes (execucao_id, periodo_letivo_id, versao, fitness_score, num_conflitos, num_janelas_total, media_janelas_aluno, aprovada) VALUES
(1, 1, 1, 892.75, 0, 8, 1.60, 1),
(2, 1, 2, 910.30, 0, 6, 1.20, 1),
(4, 3, 1, 875.20, 1, 10, 2.00, 1),
(5, 4, 1, 850.00, 0, 12, 2.40, 1),
(1, 1, 0, 245.50, 15, 45, 9.00, 0); -- Versão inicial não aprovada

-- Inserir Alocações das Soluções
INSERT INTO solucao_alocacoes (solucao_id, turma_id, sala_id, dia_semana, hora_inicio, hora_fim) VALUES
-- Solução 1 - melhor configuração
(1, 1, 1, 2, '08:00:00', '10:00:00'),
(1, 1, 1, 4, '08:00:00', '10:00:00'),
(1, 3, 2, 2, '10:00:00', '12:00:00'),
(1, 3, 2, 4, '10:00:00', '12:00:00'),
(1, 4, 3, 3, '14:00:00', '16:00:00');

-- ============================================
-- MÉTRICAS E PONTUAÇÃO
-- ============================================

-- Configuração de Pontuação já foi inserida no script principal

-- Inserir Métricas de Alunos
INSERT INTO metricas_aluno (aluno_id, periodo_letivo_id, solucao_id, num_disciplinas, carga_horaria_semanal, num_janelas_total, num_janelas_1h, num_janelas_2h, num_janelas_3h_mais, dias_com_aula, dias_sem_janela, pontuacao_janelas, pontuacao_total, percentual_aproveitamento) VALUES
(1, 1, 1, 2, 8, 0, 0, 0, 0, 4, 4, 40, 80, 100.00),
(2, 1, 1, 2, 8, 0, 0, 0, 0, 4, 4, 40, 80, 100.00),
(3, 1, 1, 1, 4, 0, 0, 0, 0, 2, 2, 20, 40, 100.00),
(4, 1, 1, 1, 4, 2, 2, 0, 0, 2, 1, -10, 10, 75.00),
(5, 1, 1, 2, 8, 1, 0, 1, 0, 4, 3, -15, 25, 85.00);

-- Inserir Métricas de Turmas
INSERT INTO metricas_turma (turma_id, periodo_letivo_id, taxa_ocupacao, media_frequencia, taxa_aprovacao, num_alunos_dependencia) VALUES
(1, 1, 5.00, NULL, NULL, 0),
(2, 1, 2.50, NULL, NULL, 0),
(3, 1, 7.50, NULL, NULL, 1),
(4, 1, 5.00, NULL, NULL, 0),
(6, 1, 2.50, NULL, NULL, 0);
