# Sistema de Gerenciamento de Instituição de Ensino

Sistema desenvolvido para gerenciar uma instituição de ensino, incluindo professores, disciplinas, salas de aula, alunos e matriz curricular.

## ✨ Novas Funcionalidades

### Autenticação e Autorização
- ✅ **Recuperação de Senha**: Sistema completo de reset de senha via e-mail
- ✅ **Verificação de E-mail**: Confirmação de e-mail para novos usuários
- ✅ **Dashboards por Nível**: Interfaces personalizadas para Admin, Professor e Estudante
- ✅ **Middleware de Permissões**: Controle de acesso baseado em níveis de usuário
- ✅ **Sistema de Logs**: Auditoria completa de acessos e ações no sistema

📖 **Documentação completa**: [AUTHENTICATION_AUTHORIZATION.md](docs/AUTHENTICATION_AUTHORIZATION.md)

## Estrutura do Banco de Dados

### Tabelas Principais

#### 1. Teachers (Professores)
Armazena informações sobre os professores da instituição.
- **Campos principais:**
  - `name`: Nome do professor
  - `email`: Email único
  - `specialization`: Área de especialização
  - `employment_type`: Tipo de contrato (integral, meio período, contratado)
  - `availability`: JSON com disponibilidade (dias/horários)
  - `preferences`: JSON com preferências de ensino (disciplinas, turnos, etc.)

#### 2. Disciplines (Disciplinas)
Gerencia as disciplinas oferecidas.
- **Campos principais:**
  - `code`: Código único da disciplina
  - `name`: Nome da disciplina
  - `workload_hours`: Carga horária total
  - `weekly_hours`: Horas semanais
  - `credits`: Créditos
  - `prerequisites`: JSON com IDs de disciplinas pré-requisito
  - `type`: Tipo (obrigatória, eletiva, optativa)

#### 3. Classrooms (Salas de Aula)
Cadastro de salas de aula disponíveis.
- **Campos principais:**
  - `code`: Código único da sala
  - `building`: Prédio
  - `capacity`: Capacidade de alunos
  - `type`: Tipo (aula teórica, laboratório, auditório, seminário)
  - `resources`: JSON com recursos disponíveis (projetor, computadores, etc.)
  - `has_accessibility`: Indica se possui acessibilidade

#### 4. Students (Alunos)
Informações dos alunos matriculados.
- **Campos principais:**
  - `registration_number`: Matrícula única
  - `name`: Nome do aluno
  - `email`: Email único
  - `status`: Status (ativo, inativo, graduado, suspenso)
  - `history`: JSON com histórico acadêmico
  - `gpa`: Média geral (0-10)

#### 5. Curriculum Matrix (Matriz Curricular)
Define a estrutura curricular por semestre.
- **Campos principais:**
  - `code`: Código único da matriz
  - `name`: Nome da matriz
  - `year`: Ano de vigência
  - `semester`: Semestre

#### 6. Curriculum Disciplines
Relaciona disciplinas com matrizes curriculares.
- **Campos principais:**
  - `curriculum_matrix_id`: ID da matriz
  - `discipline_id`: ID da disciplina
  - `semester`: Semestre em que a disciplina é oferecida
  - `period`: Período (manhã, tarde, noite)

#### 7. Enrollments (Matrículas)
Registra as matrículas dos alunos em disciplinas.
- **Campos principais:**
  - `student_id`: ID do aluno
  - `discipline_id`: ID da disciplina
  - `year`: Ano
  - `semester`: Semestre
  - `status`: Status (matriculado, concluído, reprovado, cancelado)
  - `grade`: Nota final
  - `attendance_percentage`: Percentual de presença

#### 8. Classes (Turmas)
Representa as turmas oferecidas.
- **Campos principais:**
  - `code`: Código único da turma
  - `discipline_id`: ID da disciplina
  - `teacher_id`: ID do professor
  - `classroom_id`: ID da sala
  - `shift`: Turno (manhã, tarde, noite)
  - `max_students`: Número máximo de alunos
  - `schedule`: JSON com grade horária (dias e horários)

## Modelos (Models)

Os modelos Eloquent estão localizados em `app/Models/`:
- `Teacher.php`
- `Discipline.php`
- `Classroom.php`
- `Student.php`
- `CurriculumMatrix.php`
- `Enrollment.php`
- `ClassModel.php`

### Relacionamentos

- **Teacher**: hasMany Classes
- **Discipline**: hasMany Classes, hasMany Enrollments, belongsToMany CurriculumMatrix
- **Student**: hasMany Enrollments, belongsToMany Disciplines
- **Classroom**: hasMany Classes
- **CurriculumMatrix**: belongsToMany Disciplines
- **Enrollment**: belongsTo Student, belongsTo Discipline
- **ClassModel**: belongsTo Discipline, belongsTo Teacher, belongsTo Classroom

## Controllers

Os controladores estão em `app/Http/Controllers/`:
- `TeacherController.php` - CRUD de professores
- `DisciplineController.php` - CRUD de disciplinas
- `ClassroomController.php` - CRUD de salas
- `StudentController.php` - CRUD de alunos
- `CurriculumMatrixController.php` - CRUD de matriz curricular
- `EnrollmentController.php` - Gerenciamento de matrículas

## Migrations

As migrations estão em `database/migrations/` com a seguinte ordem de execução:
1. `create_teachers_table.php`
2. `create_disciplines_table.php`
3. `create_classrooms_table.php`
4. `create_students_table.php`
5. `create_curriculum_matrix_table.php`
6. `create_curriculum_disciplines_table.php`
7. `create_enrollments_table.php`
8. `create_classes_table.php`

## Instalação (Para uso futuro com Laravel completo)

```bash
# 1. Clone o repositório
git clone https://github.com/hirotaste/Eletiva-II-ads-2025.git
cd Eletiva-II-ads-2025

# 2. Instale as dependências do Composer
composer install

# 3. Configure o arquivo .env
cp .env.example .env
php artisan key:generate

# 4. Configure o banco de dados no .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eletiva_db
DB_USERNAME=root
DB_PASSWORD=

# 5. Execute as migrations
php artisan migrate

# 6. (Opcional) Execute os seeders
php artisan db:seed

# 7. Inicie o servidor
php artisan serve
```

## API Endpoints (Exemplos)

### Teachers
- `GET /api/teachers` - Lista todos os professores
- `POST /api/teachers` - Cria um novo professor
- `GET /api/teachers/{id}` - Busca um professor específico
- `PUT /api/teachers/{id}` - Atualiza um professor
- `DELETE /api/teachers/{id}` - Desativa um professor

### Disciplines
- `GET /api/disciplines` - Lista todas as disciplinas
- `POST /api/disciplines` - Cria uma nova disciplina
- `GET /api/disciplines/{id}` - Busca uma disciplina específica
- `PUT /api/disciplines/{id}` - Atualiza uma disciplina
- `DELETE /api/disciplines/{id}` - Desativa uma disciplina

### Students
- `GET /api/students` - Lista todos os alunos
- `POST /api/students` - Cria um novo aluno
- `GET /api/students/{id}` - Busca um aluno específico
- `PUT /api/students/{id}` - Atualiza um aluno
- `DELETE /api/students/{id}` - Desativa um aluno

### Classrooms
- `GET /api/classrooms` - Lista todas as salas
- `POST /api/classrooms` - Cria uma nova sala
- `GET /api/classrooms/{id}` - Busca uma sala específica
- `PUT /api/classrooms/{id}` - Atualiza uma sala
- `DELETE /api/classrooms/{id}` - Desativa uma sala

### Curriculum Matrix
- `GET /api/curriculum-matrices` - Lista todas as matrizes
- `POST /api/curriculum-matrices` - Cria uma nova matriz
- `GET /api/curriculum-matrices/{id}` - Busca uma matriz específica
- `POST /api/curriculum-matrices/{id}/disciplines` - Adiciona disciplina à matriz
- `DELETE /api/curriculum-matrices/{id}/disciplines/{disciplineId}` - Remove disciplina da matriz

### Enrollments
- `GET /api/enrollments` - Lista todas as matrículas
- `POST /api/enrollments` - Cria uma nova matrícula
- `GET /api/enrollments/{id}` - Busca uma matrícula específica
- `PUT /api/enrollments/{id}` - Atualiza uma matrícula
- `DELETE /api/enrollments/{id}` - Cancela uma matrícula

## Recursos Especiais

### 1. Disponibilidade dos Professores
Os professores podem ter sua disponibilidade configurada em formato JSON:
```json
{
  "monday": ["08:00-12:00", "14:00-18:00"],
  "wednesday": ["08:00-12:00"],
  "friday": ["14:00-18:00"]
}
```

### 2. Preferências dos Professores
Preferências como disciplinas favoritas, turnos preferidos, etc.:
```json
{
  "preferred_disciplines": [1, 3, 5],
  "preferred_shifts": ["morning", "afternoon"],
  "max_classes_per_week": 20
}
```

### 3. Recursos das Salas
Recursos disponíveis em cada sala:
```json
{
  "projector": true,
  "computers": 40,
  "whiteboard": true,
  "air_conditioning": true,
  "sound_system": false
}
```

### 4. Pré-requisitos das Disciplinas
Disciplinas podem ter pré-requisitos:
```json
[1, 3, 7]
```

### 5. Histórico do Aluno
Registro de eventos acadêmicos:
```json
{
  "awards": ["Dean's List 2024"],
  "achievements": ["Best Project Award"],
  "notes": "Excellent performance in mathematics"
}
```

## Tecnologias Utilizadas

- PHP 8.3+
- Laravel Framework (estrutura compatível)
- MySQL/PostgreSQL (banco de dados)
- Eloquent ORM

## Contribuição

Este projeto foi desenvolvido como parte da disciplina Eletiva II do curso de Análise e Desenvolvimento de Sistemas.

## Licença

Este projeto está sob a licença especificada no arquivo LICENSE.
