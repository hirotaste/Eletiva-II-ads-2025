# Visão Geral do Projeto

## Sistema de Gerenciamento de Instituição de Ensino

### Descrição

Sistema desenvolvido para gerenciar uma instituição de ensino superior, permitindo o controle completo de professores, disciplinas, salas de aula, alunos, matrículas e matriz curricular. O sistema foi projetado para facilitar a administração acadêmica e proporcionar uma visão integrada de todos os aspectos da instituição.

### Objetivos

- **Gestão de Professores**: Controlar disponibilidade, preferências e alocação de professores
- **Gestão de Disciplinas**: Gerenciar disciplinas com pré-requisitos e cargas horárias
- **Gestão de Salas**: Otimizar uso de espaços físicos e recursos
- **Gestão de Alunos**: Acompanhar histórico acadêmico e desempenho
- **Gestão de Matrículas**: Controlar matrículas respeitando pré-requisitos
- **Matriz Curricular**: Organizar disciplinas por semestre e período

## Arquitetura

### Stack Tecnológica

- **Backend**: PHP 8.3+ com Laravel Framework
- **Banco de Dados**: MySQL / PostgreSQL / SQLite
- **ORM**: Eloquent ORM
- **API**: RESTful API com JSON
- **Padrão**: MVC (Model-View-Controller)

### Estrutura de Diretórios

```
Eletiva-II-ads-2025/
├── app/
│   ├── Http/
│   │   └── Controllers/          # Controladores da API
│   └── Models/                   # Modelos Eloquent
├── config/                       # Arquivos de configuração
├── database/
│   ├── migrations/               # Migrações do banco de dados
│   └── seeders/                  # Dados de exemplo
├── docs/                         # Documentação do projeto
├── routes/                       # Definições de rotas
└── README.md                     # Documentação principal
```

## Entidades Principais

### 1. Teachers (Professores)

**Responsabilidade**: Gerenciar informações de professores

**Atributos principais**:
- Dados pessoais (nome, email, telefone)
- Especialização
- Tipo de emprego (integral, meio período, contratado)
- Disponibilidade (JSON flexível)
- Preferências de ensino

**Casos de uso**:
- Cadastrar novo professor
- Atualizar disponibilidade
- Consultar turmas atribuídas
- Verificar carga horária

### 2. Disciplines (Disciplinas)

**Responsabilidade**: Gerenciar disciplinas oferecidas

**Atributos principais**:
- Código único
- Nome e descrição
- Carga horária (total e semanal)
- Créditos
- Pré-requisitos
- Tipo (obrigatória, eletiva, optativa)

**Casos de uso**:
- Cadastrar nova disciplina
- Definir pré-requisitos
- Vincular a matriz curricular
- Abrir turmas

### 3. Classrooms (Salas de Aula)

**Responsabilidade**: Gerenciar espaços físicos

**Atributos principais**:
- Código e localização
- Capacidade
- Tipo (teórica, laboratório, auditório)
- Recursos disponíveis
- Acessibilidade

**Casos de uso**:
- Cadastrar nova sala
- Consultar disponibilidade
- Alocar para turmas
- Verificar recursos

### 4. Students (Alunos)

**Responsabilidade**: Gerenciar alunos matriculados

**Atributos principais**:
- Matrícula única
- Dados pessoais
- Status acadêmico
- Histórico
- GPA (média geral)

**Casos de uso**:
- Matricular novo aluno
- Consultar histórico
- Verificar pré-requisitos
- Calcular desempenho

### 5. Curriculum Matrix (Matriz Curricular)

**Responsabilidade**: Organizar estrutura curricular

**Atributos principais**:
- Código e nome
- Ano e semestre de vigência
- Disciplinas por período

**Casos de uso**:
- Criar nova matriz
- Vincular disciplinas
- Distribuir por semestres
- Consultar estrutura

### 6. Enrollments (Matrículas)

**Responsabilidade**: Gerenciar matrículas de alunos

**Atributos principais**:
- Aluno e disciplina
- Período (ano/semestre)
- Status (matriculado, concluído, reprovado)
- Nota e frequência

**Casos de uso**:
- Matricular aluno em disciplina
- Lançar notas
- Registrar frequência
- Cancelar matrícula

### 7. Classes (Turmas)

**Responsabilidade**: Representar turmas oferecidas

**Atributos principais**:
- Código único
- Disciplina, professor e sala
- Período e turno
- Vagas e horários

**Casos de uso**:
- Abrir nova turma
- Alocar professor
- Definir horários
- Gerenciar vagas

## Fluxos Principais

### Fluxo 1: Matrícula de Aluno

```
1. Aluno solicita matrícula em disciplina
2. Sistema verifica pré-requisitos
3. Sistema verifica vagas disponíveis
4. Sistema confirma matrícula
5. Atualiza contagem de alunos na turma
```

### Fluxo 2: Abertura de Turma

```
1. Coordenador seleciona disciplina
2. Sistema sugere professores disponíveis
3. Coordenador aloca professor
4. Sistema sugere salas adequadas
5. Coordenador define horários
6. Sistema valida conflitos
7. Turma é criada e publicada
```

### Fluxo 3: Lançamento de Notas

```
1. Professor acessa turma
2. Lança notas dos alunos
3. Registra frequência
4. Sistema calcula aprovação
5. Atualiza status das matrículas
6. Recalcula GPA dos alunos
```

### Fluxo 4: Criação de Matriz Curricular

```
1. Coordenador cria nova matriz
2. Seleciona disciplinas obrigatórias
3. Distribui por semestres
4. Adiciona eletivas
5. Define pré-requisitos
6. Valida carga horária total
7. Ativa matriz
```

## Regras de Negócio

### Matrículas

1. Aluno só pode se matricular se completou pré-requisitos
2. Número de alunos não pode exceder capacidade da turma
3. Aluno não pode se matricular em disciplinas com horários conflitantes
4. Aprovação requer nota >= 6.0 e frequência >= 75%

### Turmas

1. Professor não pode ter turmas com horários conflitantes
2. Sala não pode ser alocada para múltiplas turmas no mesmo horário
3. Capacidade da sala deve comportar número máximo de alunos
4. Tipo de sala deve ser adequado à disciplina (lab para práticas)

### Professores

1. Carga horária total não pode exceder limite do contrato
2. Disponibilidade deve ser respeitada na alocação
3. Preferências devem ser consideradas (mas não são obrigatórias)

### Disciplinas

1. Pré-requisitos devem existir no sistema
2. Carga horária semanal deve ser múltiplo de 2
3. Créditos devem corresponder à carga horária
4. Código deve ser único

## Recursos JSON

### Teachers.availability

Permite flexibilidade na definição de horários:

```json
{
  "monday": ["08:00-12:00", "14:00-18:00"],
  "tuesday": ["08:00-12:00"],
  "wednesday": ["08:00-12:00", "14:00-18:00"],
  "thursday": ["14:00-18:00"],
  "friday": ["08:00-12:00"]
}
```

### Teachers.preferences

Armazena preferências de ensino:

```json
{
  "preferred_disciplines": [1, 3, 5],
  "preferred_shifts": ["morning", "afternoon"],
  "max_classes_per_week": 20,
  "avoid_consecutive_days": false
}
```

### Classrooms.resources

Lista recursos disponíveis:

```json
{
  "projector": true,
  "computers": 40,
  "whiteboard": true,
  "air_conditioning": true,
  "sound_system": false,
  "internet": "wifi",
  "accessibility_features": ["ramp", "elevator"]
}
```

### Students.history

Registra histórico acadêmico:

```json
{
  "awards": ["Dean's List 2024"],
  "achievements": ["Best Project Award"],
  "notes": "Excellent performance",
  "extracurricular": ["Programming Club"]
}
```

### Classes.schedule

Define grade horária da turma:

```json
{
  "monday": ["08:00-10:00", "10:00-12:00"],
  "wednesday": ["08:00-10:00"],
  "friday": ["14:00-16:00"]
}
```

## Extensibilidade

O sistema foi projetado para fácil extensão:

### Funcionalidades Futuras

- **Autenticação e Autorização**: Laravel Sanctum/Passport
- **Notificações**: Email/SMS para alunos e professores
- **Relatórios**: Geração de relatórios em PDF
- **Dashboard**: Visualizações e estatísticas
- **Integração**: APIs externas (sistemas legados)
- **Mobile**: App mobile para alunos
- **Chat**: Sistema de mensagens interno

### Módulos Adicionais

- **Biblioteca**: Gestão de empréstimos
- **Financeiro**: Controle de mensalidades
- **RH**: Gestão de funcionários
- **Eventos**: Calendário acadêmico
- **Pesquisa**: Projetos de pesquisa
- **Extensão**: Projetos de extensão

## Performance

### Otimizações Implementadas

- Índices em colunas frequentemente consultadas
- Eager loading de relacionamentos
- Soft deletes ao invés de exclusões físicas
- JSON para dados flexíveis (evita múltiplas tabelas)

### Recomendações

- Cache de queries frequentes (Redis)
- Filas para tarefas pesadas (envio de emails)
- CDN para assets estáticos
- Load balancer para alta demanda

## Segurança

### Medidas Implementadas

- Validação de entrada em todos os endpoints
- Proteção contra SQL Injection (Eloquent ORM)
- HTTPS obrigatório em produção
- Rate limiting em APIs

### Recomendações

- Implementar autenticação 2FA
- Logs de auditoria
- Backup automático diário
- Testes de penetração

## Conformidade

### LGPD (Lei Geral de Proteção de Dados)

- Dados pessoais são criptografados
- Soft delete preserva histórico quando necessário
- Possibilidade de exportar dados do usuário
- Logs de acesso a dados sensíveis

## Licença

Este projeto está licenciado sob os termos especificados no arquivo LICENSE.

## Equipe

- Desenvolvido para a disciplina Eletiva II
- Curso: Análise e Desenvolvimento de Sistemas
- Ano: 2025

## Contato

Para dúvidas ou sugestões, abra uma issue no GitHub.
