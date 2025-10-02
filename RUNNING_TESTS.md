# Guia Rápido - Executando o Projeto com SQLite

## Pré-requisitos
- PHP 8.1+
- Composer

## Setup Rápido

### 1. Instalar Dependências (se necessário)
```bash
composer install
```

### 2. Configurar Ambiente
O arquivo `.env` já está configurado com SQLite:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/caminho/completo/para/database.sqlite
```

### 3. Criar Banco de Dados e Executar Migrations
```bash
# Criar o arquivo do banco SQLite
touch database/database.sqlite

# Executar as migrations
php artisan migrate
```

### 4. Iniciar o Servidor
```bash
php artisan serve
```
O servidor estará disponível em: http://localhost:8000

## Testar a API

### Exemplos de Requisições

#### Listar Professores
```bash
curl http://localhost:8000/api/teachers
```

#### Criar Professor
```bash
curl -X POST http://localhost:8000/api/teachers \
  -H "Content-Type: application/json" \
  -d '{
    "name": "João Silva",
    "email": "joao@exemplo.com",
    "specialization": "Matemática",
    "employment_type": "full_time"
  }'
```

#### Listar Disciplinas
```bash
curl http://localhost:8000/api/disciplines
```

#### Criar Disciplina
```bash
curl -X POST http://localhost:8000/api/disciplines \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Cálculo I",
    "code": "MAT101",
    "description": "Cálculo Diferencial e Integral",
    "credits": 4,
    "workload_hours": 60,
    "weekly_hours": 4,
    "type": "mandatory"
  }'
```

#### Criar Aluno
```bash
curl -X POST http://localhost:8000/api/students \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Maria Santos",
    "registration_number": "ST2024001",
    "email": "maria@exemplo.com",
    "birth_date": "2000-05-15",
    "enrollment_date": "2024-01-15"
  }'
```

## Executar Testes Unitários

### Executar Todos os Testes
```bash
php artisan test
```

### Executar com Output Detalhado
```bash
vendor/bin/phpunit --testdox
```

### Executar Testes de um Controller Específico
```bash
vendor/bin/phpunit tests/Feature/TeacherControllerTest.php
```

### Ver Resultado dos Testes
Os testes cobrem todas as operações CRUD:
- ✅ 9 testes para TeacherController
- ✅ 11 testes para DisciplineController  
- ✅ 10 testes para ClassroomController
- ✅ 11 testes para StudentController
- ✅ 11 testes para EnrollmentController
- ✅ 11 testes para CurriculumMatrixController

**Total: 63 testes de feature**

## Endpoints Disponíveis

### Teachers (Professores)
- `GET /api/teachers` - Listar todos
- `POST /api/teachers` - Criar novo
- `GET /api/teachers/{id}` - Buscar específico
- `PUT /api/teachers/{id}` - Atualizar
- `DELETE /api/teachers/{id}` - Desativar

### Disciplines (Disciplinas)
- `GET /api/disciplines` - Listar todas
- `POST /api/disciplines` - Criar nova
- `GET /api/disciplines/{id}` - Buscar específica
- `PUT /api/disciplines/{id}` - Atualizar
- `DELETE /api/disciplines/{id}` - Desativar

### Classrooms (Salas)
- `GET /api/classrooms` - Listar todas
- `POST /api/classrooms` - Criar nova
- `GET /api/classrooms/{id}` - Buscar específica
- `PUT /api/classrooms/{id}` - Atualizar
- `DELETE /api/classrooms/{id}` - Desativar

### Students (Alunos)
- `GET /api/students` - Listar todos
- `POST /api/students` - Criar novo
- `GET /api/students/{id}` - Buscar específico
- `PUT /api/students/{id}` - Atualizar
- `DELETE /api/students/{id}` - Desativar

### Enrollments (Matrículas)
- `GET /api/enrollments` - Listar todas
- `POST /api/enrollments` - Criar nova
- `GET /api/enrollments/{id}` - Buscar específica
- `PUT /api/enrollments/{id}` - Atualizar
- `DELETE /api/enrollments/{id}` - Cancelar

### Curriculum Matrices (Matriz Curricular)
- `GET /api/curriculum-matrices` - Listar todas
- `POST /api/curriculum-matrices` - Criar nova
- `GET /api/curriculum-matrices/{id}` - Buscar específica
- `PUT /api/curriculum-matrices/{id}` - Atualizar
- `DELETE /api/curriculum-matrices/{id}` - Desativar
- `POST /api/curriculum-matrices/{id}/disciplines` - Adicionar disciplina
- `DELETE /api/curriculum-matrices/{id}/disciplines/{disciplineId}` - Remover disciplina

## Estrutura do Banco SQLite

O banco de dados SQLite possui 8 tabelas:
1. **teachers** - Professores
2. **disciplines** - Disciplinas
3. **classrooms** - Salas de aula
4. **students** - Alunos
5. **curriculum_matrix** - Matrizes curriculares
6. **curriculum_disciplines** - Relação matriz-disciplinas
7. **enrollments** - Matrículas dos alunos
8. **classes** - Turmas

## Troubleshooting

### Erro: "database.sqlite not found"
```bash
touch database/database.sqlite
php artisan migrate
```

### Erro: "Please provide a valid cache path"
```bash
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
```

### Erro: "Permission denied"
```bash
chmod -R 775 storage bootstrap/cache
```

## Validações Implementadas

Todos os controllers possuem validações robustas:
- ✅ Campos obrigatórios
- ✅ Validação de tipos (email, datas, enums)
- ✅ Validação de unicidade (códigos, emails)
- ✅ Validação de integridade referencial
- ✅ Validação de ranges (notas, capacidades)

## Próximos Passos

Com o backend funcionando, você pode:
1. Adicionar autenticação (Laravel Sanctum)
2. Criar um frontend (React, Vue, etc)
3. Adicionar mais endpoints customizados
4. Implementar relatórios
5. Adicionar upload de arquivos
