# Guia de Início Rápido

Este guia ajudará você a começar rapidamente com o Sistema de Gerenciamento Educacional.

## 🚀 Início Rápido (5 minutos)

### Pré-requisitos Mínimos
- PHP 8.1+
- Composer
- MySQL/PostgreSQL/SQLite

### Passos Básicos

```bash
# 1. Clone o repositório
git clone https://github.com/hirotaste/Eletiva-II-ads-2025.git
cd Eletiva-II-ads-2025

# 2. Configure o ambiente
cp .env.example .env

# 3. Edite .env com suas credenciais de banco de dados
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=eletiva_db
# DB_USERNAME=seu_usuario
# DB_PASSWORD=sua_senha

# 4. Crie o banco de dados
mysql -u root -p
CREATE DATABASE eletiva_db;
EXIT;

# 5. Execute as migrations (quando Laravel estiver completo)
# php artisan migrate

# 6. (Opcional) Popule com dados de exemplo
# php artisan db:seed

# 7. Inicie o servidor
# php artisan serve
```

## 📁 Estrutura do Projeto

```
Eletiva-II-ads-2025/
├── app/
│   ├── Http/Controllers/     # 6 controllers RESTful
│   │   ├── TeacherController.php
│   │   ├── DisciplineController.php
│   │   ├── ClassroomController.php
│   │   ├── StudentController.php
│   │   ├── CurriculumMatrixController.php
│   │   └── EnrollmentController.php
│   └── Models/               # 7 modelos Eloquent
│       ├── Teacher.php
│       ├── Discipline.php
│       ├── Classroom.php
│       ├── Student.php
│       ├── CurriculumMatrix.php
│       ├── Enrollment.php
│       └── ClassModel.php
│
├── database/
│   ├── migrations/           # 8 migrações
│   │   ├── create_teachers_table.php
│   │   ├── create_disciplines_table.php
│   │   ├── create_classrooms_table.php
│   │   ├── create_students_table.php
│   │   ├── create_curriculum_matrix_table.php
│   │   ├── create_curriculum_disciplines_table.php
│   │   ├── create_enrollments_table.php
│   │   └── create_classes_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
│
├── docs/                     # Documentação completa
│   ├── API_DOCUMENTATION.md
│   ├── DATABASE_SCHEMA.md
│   ├── INSTALLATION.md
│   ├── CONTRIBUTING.md
│   └── PROJECT_OVERVIEW.md
│
├── routes/
│   └── api.php              # Rotas da API
│
└── README.md                # Este arquivo
```

## 🎯 Recursos Principais

### 1. Professores (Teachers)
- ✅ Cadastro com disponibilidade em JSON
- ✅ Preferências de ensino
- ✅ Controle de carga horária
- ✅ Alocação em turmas

### 2. Disciplinas (Disciplines)
- ✅ Código único
- ✅ Carga horária e créditos
- ✅ Pré-requisitos em JSON
- ✅ Tipos: obrigatória, eletiva, optativa

### 3. Salas de Aula (Classrooms)
- ✅ Capacidade e localização
- ✅ Recursos em JSON
- ✅ Tipos: teórica, lab, auditório
- ✅ Indicador de acessibilidade

### 4. Alunos (Students)
- ✅ Matrícula única
- ✅ Histórico acadêmico em JSON
- ✅ Cálculo de GPA
- ✅ Status acadêmico

### 5. Matriz Curricular (Curriculum Matrix)
- ✅ Organização por semestre
- ✅ Vinculação de disciplinas
- ✅ Períodos (manhã, tarde, noite)

### 6. Matrículas (Enrollments)
- ✅ Controle de pré-requisitos
- ✅ Notas e frequência
- ✅ Status da matrícula

### 7. Turmas (Classes)
- ✅ Alocação de professor e sala
- ✅ Controle de vagas
- ✅ Grade horária em JSON

## 🔌 Endpoints da API

### Professores
```http
GET    /api/teachers          # Listar todos
POST   /api/teachers          # Criar novo
GET    /api/teachers/{id}     # Buscar específico
PUT    /api/teachers/{id}     # Atualizar
DELETE /api/teachers/{id}     # Desativar
```

### Disciplinas
```http
GET    /api/disciplines          # Listar todas
POST   /api/disciplines          # Criar nova
GET    /api/disciplines/{id}     # Buscar específica
PUT    /api/disciplines/{id}     # Atualizar
DELETE /api/disciplines/{id}     # Desativar
```

### Alunos
```http
GET    /api/students          # Listar todos
POST   /api/students          # Criar novo
GET    /api/students/{id}     # Buscar específico
PUT    /api/students/{id}     # Atualizar
DELETE /api/students/{id}     # Desativar
```

### Salas
```http
GET    /api/classrooms          # Listar todas
POST   /api/classrooms          # Criar nova
GET    /api/classrooms/{id}     # Buscar específica
PUT    /api/classrooms/{id}     # Atualizar
DELETE /api/classrooms/{id}     # Desativar
```

### Matriz Curricular
```http
GET    /api/curriculum-matrices          # Listar todas
POST   /api/curriculum-matrices          # Criar nova
GET    /api/curriculum-matrices/{id}     # Buscar específica
POST   /api/curriculum-matrices/{id}/disciplines  # Adicionar disciplina
DELETE /api/curriculum-matrices/{id}/disciplines/{disciplineId}  # Remover disciplina
```

### Matrículas
```http
GET    /api/enrollments          # Listar todas
POST   /api/enrollments          # Criar nova
GET    /api/enrollments/{id}     # Buscar específica
PUT    /api/enrollments/{id}     # Atualizar
DELETE /api/enrollments/{id}     # Cancelar
```

## 📊 Exemplo de Uso

### Criar um Professor

```bash
curl -X POST http://localhost:8000/api/teachers \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Prof. João Silva",
    "email": "joao.silva@instituicao.edu.br",
    "phone": "(11) 98765-4321",
    "specialization": "Ciência da Computação",
    "employment_type": "full_time",
    "availability": {
      "monday": ["08:00-12:00", "14:00-18:00"],
      "wednesday": ["08:00-12:00"]
    },
    "preferences": {
      "preferred_shifts": ["morning", "afternoon"],
      "max_classes_per_week": 20
    }
  }'
```

### Criar uma Disciplina

```bash
curl -X POST http://localhost:8000/api/disciplines \
  -H "Content-Type: application/json" \
  -d '{
    "code": "ADS101",
    "name": "Algoritmos e Programação I",
    "description": "Introdução à lógica de programação",
    "workload_hours": 80,
    "weekly_hours": 4,
    "credits": 4,
    "type": "mandatory"
  }'
```

### Matricular um Aluno

```bash
curl -X POST http://localhost:8000/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{
    "student_id": 1,
    "discipline_id": 1,
    "year": 2025,
    "semester": 1
  }'
```

## 📚 Documentação Completa

Para mais detalhes, consulte:

- **[README.md](README.md)** - Visão geral e introdução
- **[docs/INSTALLATION.md](docs/INSTALLATION.md)** - Guia de instalação completo
- **[docs/API_DOCUMENTATION.md](docs/API_DOCUMENTATION.md)** - Documentação da API
- **[docs/DATABASE_SCHEMA.md](docs/DATABASE_SCHEMA.md)** - Esquema do banco de dados
- **[docs/PROJECT_OVERVIEW.md](docs/PROJECT_OVERVIEW.md)** - Visão geral do projeto
- **[docs/CONTRIBUTING.md](docs/CONTRIBUTING.md)** - Como contribuir

## 💡 Próximos Passos

1. ✅ Explore os endpoints da API
2. ✅ Leia a documentação do banco de dados
3. ✅ Execute os seeders para ter dados de exemplo
4. ✅ Teste as operações CRUD
5. ✅ Personalize para suas necessidades

## 🐛 Problemas?

Se encontrar algum problema:

1. Verifique os [problemas conhecidos](docs/INSTALLATION.md#troubleshooting)
2. Consulte as [issues no GitHub](https://github.com/hirotaste/Eletiva-II-ads-2025/issues)
3. Abra uma nova issue com detalhes

## 📝 Licença

Este projeto está sob a licença especificada no arquivo [LICENSE](LICENSE).

## 🤝 Contribuindo

Contribuições são bem-vindas! Leia [docs/CONTRIBUTING.md](docs/CONTRIBUTING.md) para começar.

---

**Desenvolvido para a disciplina Eletiva II - ADS 2025**
