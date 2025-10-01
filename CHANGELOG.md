# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

## [Unreleased]

### A Adicionar
- Sistema de autenticação com Laravel Sanctum
- Dashboard administrativo
- Relatórios em PDF
- Notificações por email
- Sistema de permissões (ACL)
- API de busca avançada com filtros
- Integração com calendário acadêmico
- Sistema de avaliações e feedback

## [1.0.0] - 2025-10-01

### Adicionado

#### Estrutura Base
- Estrutura inicial do projeto Laravel
- Configuração de ambiente (.env.example)
- Configuração de banco de dados (MySQL, PostgreSQL, SQLite)
- Gitignore para Laravel

#### Banco de Dados
- **Migration**: Tabela `teachers` com campos:
  - Dados pessoais (name, email, phone)
  - Especialização
  - Tipo de emprego
  - Disponibilidade (JSON)
  - Preferências (JSON)
  
- **Migration**: Tabela `disciplines` com campos:
  - Código único
  - Nome e descrição
  - Carga horária (total e semanal)
  - Créditos
  - Pré-requisitos (JSON)
  - Tipo (mandatory, elective, optional)

- **Migration**: Tabela `classrooms` com campos:
  - Código e localização
  - Capacidade
  - Tipo (lecture, lab, auditorium, seminar)
  - Recursos (JSON)
  - Acessibilidade

- **Migration**: Tabela `students` com campos:
  - Número de matrícula único
  - Dados pessoais
  - Data de nascimento e matrícula
  - Status acadêmico
  - Histórico (JSON)
  - GPA (média geral)

- **Migration**: Tabela `curriculum_matrix` com campos:
  - Código único
  - Nome da matriz
  - Ano e semestre de vigência
  - Descrição
  
- **Migration**: Tabela `curriculum_disciplines` (pivot) com campos:
  - Relacionamento matriz-disciplina
  - Semestre dentro da matriz
  - Período (manhã, tarde, noite)

- **Migration**: Tabela `enrollments` com campos:
  - Relacionamento aluno-disciplina
  - Ano e semestre
  - Status (enrolled, completed, failed, withdrawn)
  - Nota e frequência

- **Migration**: Tabela `classes` com campos:
  - Código único da turma
  - Relacionamentos (discipline, teacher, classroom)
  - Ano e semestre
  - Turno
  - Vagas (máximo e matriculados)
  - Grade horária (JSON)

#### Modelos (Models)
- **Teacher**: Modelo com relacionamentos e casts JSON
- **Discipline**: Modelo com relacionamentos N:M
- **Classroom**: Modelo com relacionamentos
- **Student**: Modelo com cálculos de performance
- **CurriculumMatrix**: Modelo com relacionamentos N:M
- **Enrollment**: Modelo pivot com dados extras
- **ClassModel**: Modelo para turmas com relacionamentos

#### Controllers
- **TeacherController**: CRUD completo
  - Lista professores ativos
  - Criação com validação
  - Visualização com turmas relacionadas
  - Atualização parcial
  - Soft delete (desativação)

- **DisciplineController**: CRUD completo
  - Lista disciplinas ativas
  - Criação com validação de pré-requisitos
  - Visualização com relacionamentos
  - Atualização
  - Soft delete

- **ClassroomController**: CRUD completo
  - Lista salas ativas
  - Criação com validação de capacidade
  - Visualização com turmas alocadas
  - Atualização
  - Soft delete

- **StudentController**: CRUD completo
  - Lista alunos ativos
  - Criação automática de status
  - Visualização com matrículas
  - Atualização de GPA e histórico
  - Soft delete

- **CurriculumMatrixController**: CRUD + relacionamentos
  - Gerenciamento de matrizes
  - Vinculação de disciplinas
  - Desvinculação de disciplinas
  - Consulta de estrutura curricular

- **EnrollmentController**: CRUD completo
  - Gerenciamento de matrículas
  - Validação de pré-requisitos (via rota)
  - Lançamento de notas
  - Controle de frequência

#### Rotas (Routes)
- Rotas RESTful para todos os recursos
- Rotas customizadas para:
  - Turmas de um professor
  - Matrículas atuais de um aluno
  - Salas disponíveis com filtros
  - Disciplinas por tipo
  - Performance acadêmica do aluno
  - Verificação de pré-requisitos

#### Seeders
- **DatabaseSeeder**: Dados de exemplo incluindo:
  - 2 professores com disponibilidade
  - 4 disciplinas com pré-requisitos
  - 2 salas (laboratório e sala teórica)
  - 2 alunos
  - 1 matriz curricular completa
  - 2 turmas abertas
  - 3 matrículas de exemplo

#### Documentação
- **README.md**: Documentação principal em português
  - Estrutura do banco
  - Modelos e relacionamentos
  - Controllers e endpoints
  - Instruções de instalação
  - Exemplos de uso

- **docs/API_DOCUMENTATION.md**: Documentação completa da API
  - Todos os endpoints documentados
  - Exemplos de request/response
  - Tipos de dados e enums
  - Tratamento de erros

- **docs/DATABASE_SCHEMA.md**: Esquema do banco de dados
  - Diagrama de relacionamentos ASCII
  - Estrutura de todas as tabelas
  - Tipos de dados JSON explicados
  - Índices e constraints
  - Exemplos de queries SQL

- **docs/INSTALLATION.md**: Guia de instalação
  - Pré-requisitos
  - Instalação passo a passo
  - Configuração de diferentes bancos
  - Docker e Docker Compose
  - Troubleshooting

- **docs/CONTRIBUTING.md**: Guia de contribuição
  - Como reportar bugs
  - Como sugerir melhorias
  - Processo de Pull Request
  - Padrões de código
  - Convenções de commit

- **docs/PROJECT_OVERVIEW.md**: Visão geral do projeto
  - Arquitetura do sistema
  - Entidades e responsabilidades
  - Fluxos principais
  - Regras de negócio
  - Recursos JSON
  - Extensibilidade

- **QUICKSTART.md**: Guia de início rápido
  - Setup em 5 minutos
  - Estrutura do projeto
  - Recursos principais
  - Exemplos de uso
  - Próximos passos

- **CHANGELOG.md**: Este arquivo

#### Configuração
- **config/database.php**: Configurações de banco de dados
  - Suporte MySQL, PostgreSQL, SQLite
  - Configuração de Redis
  - Opções de migração

### Funcionalidades

#### Sistema de Disponibilidade
- Professores podem definir disponibilidade em JSON flexível
- Sistema respeita disponibilidade na alocação de turmas
- Preferências são consideradas mas não obrigatórias

#### Sistema de Pré-requisitos
- Disciplinas podem ter múltiplos pré-requisitos
- Verificação automática antes da matrícula
- Consulta de disciplinas faltantes

#### Sistema de Recursos
- Salas possuem recursos em JSON
- Matching de recursos com necessidades da disciplina
- Filtros por tipo e capacidade

#### Sistema de Histórico
- Alunos mantém histórico em JSON
- Registro de prêmios e conquistas
- Anotações acadêmicas

#### Soft Delete
- Desativação ao invés de exclusão física
- Preservação de dados históricos
- Possibilidade de reativação

### Tecnologias Utilizadas
- PHP 8.3+
- Laravel Framework (estrutura compatível)
- Eloquent ORM
- MySQL / PostgreSQL / SQLite
- RESTful API
- JSON para dados flexíveis

### Padrões e Boas Práticas
- PSR-12 Coding Standard
- MVC Architecture
- Repository Pattern (preparado)
- RESTful API Design
- Semantic Versioning
- Keep a Changelog

### Segurança
- Validação de entrada em todos os endpoints
- Proteção contra SQL Injection via ORM
- Foreign keys com cascade apropriado
- Preparado para HTTPS

### Performance
- Índices em colunas de busca
- Eager loading de relacionamentos
- JSON para evitar over-engineering
- Estrutura otimizada

## Notas de Versão

### Versão 1.0.0
Esta é a primeira versão estável do sistema. Inclui todas as funcionalidades básicas necessárias para gerenciar uma instituição de ensino, com foco em:
- Gestão completa de professores, disciplinas, salas e alunos
- Sistema de matrículas com validação de pré-requisitos
- Matriz curricular flexível por semestre
- API RESTful completa e documentada
- Seed data para testes e demonstração

### Próximas Versões Planejadas

#### v1.1.0 (Planejado)
- Sistema de autenticação
- Permissões e papéis (ACL)
- Filtros avançados na API
- Paginação de resultados

#### v1.2.0 (Planejado)
- Dashboard administrativo
- Relatórios básicos
- Exportação de dados
- Logs de auditoria

#### v2.0.0 (Planejado)
- Interface web completa
- Sistema de notificações
- Integração com email
- Calendário acadêmico

---

## Como Usar Este Changelog

Este changelog segue o formato [Keep a Changelog](https://keepachangelog.com/):

- `Added` para novas funcionalidades
- `Changed` para mudanças em funcionalidades existentes
- `Deprecated` para funcionalidades que serão removidas
- `Removed` para funcionalidades removidas
- `Fixed` para correções de bugs
- `Security` para correções de segurança

As versões seguem [Semantic Versioning](https://semver.org/):
- MAJOR: mudanças incompatíveis na API
- MINOR: funcionalidades adicionadas de forma compatível
- PATCH: correções de bugs compatíveis
