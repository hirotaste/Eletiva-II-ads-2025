# Esquema do Banco de Dados - Sistema de Gerenciamento Educacional

## Diagrama de Relacionamentos

```
┌─────────────────┐
│    TEACHERS     │
├─────────────────┤
│ id              │
│ name            │
│ email           │
│ phone           │
│ specialization  │
│ employment_type │
│ availability    │◄──────┐
│ preferences     │       │
│ is_active       │       │
│ timestamps      │       │
└─────────────────┘       │
                          │
                          │ 1:N
                          │
                    ┌─────┴──────────┐
                    │    CLASSES     │
                    ├────────────────┤
                    │ id             │
                    │ code           │
                    │ discipline_id  │──┐
                    │ teacher_id     │  │
                    │ classroom_id   │──┤
                    │ year           │  │
                    │ semester       │  │
                    │ shift          │  │
                    │ max_students   │  │
                    │ enrolled_...   │  │
                    │ schedule       │  │
                    │ is_active      │  │
                    │ timestamps     │  │
                    └────────────────┘  │
                          │             │
                          │ N:1         │ N:1
                          │             │
                    ┌─────┴──────────┐  │
                    │  DISCIPLINES   │◄─┘
                    ├────────────────┤
                    │ id             │
                    │ code           │
                    │ name           │
                    │ description    │
                    │ workload_hours │
                    │ weekly_hours   │
                    │ credits        │
                    │ prerequisites  │
                    │ type           │
                    │ is_active      │
                    │ timestamps     │
                    └────────────────┘
                          │
                          │ N:M (via CURRICULUM_DISCIPLINES)
                          │
                    ┌─────┴────────────────┐
                    │ CURRICULUM_MATRIX    │
                    ├──────────────────────┤
                    │ id                   │
                    │ code                 │
                    │ name                 │
                    │ year                 │
                    │ semester             │
                    │ description          │
                    │ is_active            │
                    │ timestamps           │
                    └──────────────────────┘

┌─────────────────┐                  ┌──────────────────┐
│   CLASSROOMS    │                  │    STUDENTS      │
├─────────────────┤                  ├──────────────────┤
│ id              │                  │ id               │
│ code            │                  │ registration_... │
│ building        │                  │ name             │
│ floor           │                  │ email            │
│ capacity        │                  │ phone            │
│ type            │                  │ birth_date       │
│ resources       │                  │ enrollment_date  │
│ has_access...   │                  │ status           │
│ is_active       │                  │ history          │
│ timestamps      │                  │ gpa              │
└─────────────────┘                  │ timestamps       │
        │                            └──────────────────┘
        │ 1:N                                │
        │                                    │ 1:N
        │                                    │
        └──────────────┐                    │
                       │              ┌─────┴────────────┐
                       │              │   ENROLLMENTS    │
                       │              ├──────────────────┤
                       │              │ id               │
                       └──────────────┤ student_id       │
                                      │ discipline_id    │──┐
                                      │ year             │  │
                                      │ semester         │  │
                                      │ status           │  │
                                      │ grade            │  │
                                      │ attendance_%     │  │
                                      │ timestamps       │  │
                                      └──────────────────┘  │
                                                            │
                                                            │ N:1
                                                            │
                                                      ┌─────┴──────────┐
                                                      │  DISCIPLINES   │
                                                      └────────────────┘
```

## Tabelas Pivot

### CURRICULUM_DISCIPLINES
```
├──────────────────────┤
│ id                   │
│ curriculum_matrix_id │───► FK para CURRICULUM_MATRIX
│ discipline_id        │───► FK para DISCIPLINES
│ semester             │
│ period               │
│ timestamps           │
└──────────────────────┘
```

## Tipos de Dados JSON

### Teachers.availability
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
```json
{
  "preferred_disciplines": [1, 3, 5, 7],
  "preferred_shifts": ["morning", "afternoon"],
  "max_classes_per_week": 20,
  "avoid_consecutive_days": false
}
```

### Disciplines.prerequisites
```json
[1, 3, 7]
```

### Classrooms.resources
```json
{
  "projector": true,
  "computers": 40,
  "whiteboard": true,
  "air_conditioning": true,
  "sound_system": false,
  "internet": "wifi",
  "accessibility_features": ["ramp", "elevator", "adapted_restroom"]
}
```

### Students.history
```json
{
  "awards": [
    "Dean's List 2024",
    "Best Student Award 2024"
  ],
  "achievements": [
    "Best Project Award",
    "Hackathon Winner"
  ],
  "notes": "Excellent performance in mathematics and programming",
  "extracurricular": [
    "Programming Club President",
    "Volunteer Tutor"
  ]
}
```

### Classes.schedule
```json
{
  "monday": ["08:00-10:00", "10:00-12:00"],
  "wednesday": ["08:00-10:00"],
  "friday": ["14:00-16:00"]
}
```

## Enumerações (ENUM)

### Teachers.employment_type
- `full_time` - Tempo integral
- `part_time` - Meio período
- `contractor` - Contratado

### Disciplines.type
- `mandatory` - Obrigatória
- `elective` - Eletiva
- `optional` - Optativa

### Classrooms.type
- `lecture` - Sala de aula teórica
- `lab` - Laboratório
- `auditorium` - Auditório
- `seminar` - Sala de seminário

### Students.status
- `active` - Ativo
- `inactive` - Inativo
- `graduated` - Graduado
- `suspended` - Suspenso

### Classes.shift
- `morning` - Manhã
- `afternoon` - Tarde
- `evening` - Noite
- `night` - Noturno

### Enrollments.status
- `enrolled` - Matriculado
- `completed` - Concluído
- `failed` - Reprovado
- `withdrawn` - Cancelado

## Índices Recomendados

### Índices Únicos
- `teachers.email`
- `disciplines.code`
- `classrooms.code`
- `students.registration_number`
- `students.email`
- `curriculum_matrix.code`
- `classes.code`
- `curriculum_disciplines(curriculum_matrix_id, discipline_id)`

### Índices Compostos
- `enrollments(student_id, discipline_id, year, semester)`
- `classes(discipline_id, year, semester)`
- `classes(teacher_id, year, semester)`

### Índices de Busca
- `students.name`
- `teachers.name`
- `disciplines.name`

## Constraints (Restrições)

### Foreign Keys
- `classes.discipline_id` → `disciplines.id` (CASCADE)
- `classes.teacher_id` → `teachers.id` (CASCADE)
- `classes.classroom_id` → `classrooms.id` (SET NULL)
- `enrollments.student_id` → `students.id` (CASCADE)
- `enrollments.discipline_id` → `disciplines.id` (CASCADE)
- `curriculum_disciplines.curriculum_matrix_id` → `curriculum_matrix.id` (CASCADE)
- `curriculum_disciplines.discipline_id` → `disciplines.id` (CASCADE)

### Check Constraints (Lógica de Aplicação)
- `disciplines.workload_hours` > 0
- `disciplines.weekly_hours` > 0
- `disciplines.credits` > 0
- `classrooms.capacity` > 0
- `students.gpa` BETWEEN 0 AND 10
- `enrollments.grade` BETWEEN 0 AND 10
- `enrollments.attendance_percentage` BETWEEN 0 AND 100
- `classes.max_students` > 0
- `classes.enrolled_students` >= 0

## Observações de Design

1. **Soft Delete**: As entidades principais usam `is_active` ao invés de exclusão física
2. **Timestamps**: Todas as tabelas possuem `created_at` e `updated_at`
3. **JSON Fields**: Usados para dados flexíveis (availability, preferences, resources, etc.)
4. **Normalização**: O banco está na 3ª forma normal
5. **Escalabilidade**: O design permite fácil expansão com novas funcionalidades
6. **Integridade**: Uso extensivo de foreign keys e constraints

## Exemplos de Consultas Comuns

### 1. Buscar turmas de um professor em um semestre
```sql
SELECT c.*, d.name as discipline_name, cl.code as classroom_code
FROM classes c
JOIN disciplines d ON c.discipline_id = d.id
LEFT JOIN classrooms cl ON c.classroom_id = cl.id
WHERE c.teacher_id = ? AND c.year = ? AND c.semester = ?
```

### 2. Buscar histórico de um aluno
```sql
SELECT e.*, d.name as discipline_name, d.code as discipline_code
FROM enrollments e
JOIN disciplines d ON e.discipline_id = d.id
WHERE e.student_id = ?
ORDER BY e.year DESC, e.semester DESC
```

### 3. Verificar pré-requisitos de uma disciplina
```sql
SELECT d2.*
FROM disciplines d1
CROSS JOIN LATERAL JSON_TABLE(
    d1.prerequisites,
    '$[*]' COLUMNS (prerequisite_id INT PATH '$')
) AS jt
JOIN disciplines d2 ON d2.id = jt.prerequisite_id
WHERE d1.id = ?
```

### 4. Buscar salas disponíveis com capacidade mínima
```sql
SELECT *
FROM classrooms
WHERE capacity >= ? AND is_active = true AND type = ?
ORDER BY capacity
```

### 5. Calcular média de um aluno
```sql
SELECT AVG(grade) as average
FROM enrollments
WHERE student_id = ? AND status = 'completed'
```
