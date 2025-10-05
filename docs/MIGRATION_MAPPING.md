# Migration Mapping: database.sql to Laravel Migrations

This document maps the tables in `database/datasbase.sql` to their corresponding Laravel migration files.

## Overview

The repository now contains a complete set of Laravel migrations that match the comprehensive PostgreSQL schema defined in `database/datasbase.sql`. All migrations are database-agnostic and work correctly with MySQL, PostgreSQL, and SQLite.

## Migration Files Created

### Base Academic Structure Tables

| SQL Table | Migration File | Description |
|-----------|----------------|-------------|
| `cursos` | `2025_01_01_000009_create_cursos_table.php` | Courses offered by institution |
| `disciplinas` | `2025_01_01_000010_create_disciplinas_table.php` | Discipline catalog |
| `curso_disciplinas` | `2025_01_01_000011_create_curso_disciplinas_table.php` | Course-discipline relationship (curriculum matrix) |
| `prerequisitos` | `2025_01_01_000012_create_prerequisitos_table.php` | Prerequisites between disciplines |
| `professores` | `2025_01_01_000013_create_professores_table.php` | Teachers/Professors |
| `professor_disponibilidade` | `2025_01_01_000014_create_professor_disponibilidade_table.php` | Teacher availability schedule |
| `professor_disciplinas` | `2025_01_01_000015_create_professor_disciplinas_table.php` | Teacher-discipline relationships |
| `salas` | `2025_01_01_000016_create_salas_table.php` | Classrooms |
| `alunos` | `2025_01_01_000017_create_alunos_table.php` | Students |
| `historico_academico` | `2025_01_01_000018_create_historico_academico_table.php` | Academic history |

### Schedule and Enrollment Tables

| SQL Table | Migration File | Description |
|-----------|----------------|-------------|
| `periodos_letivos` | `2025_01_01_000019_create_periodos_letivos_table.php` | Academic periods |
| `turmas` | `2025_01_01_000020_create_turmas_table.php` | Classes/Sections |
| `turma_horarios` | `2025_01_01_000021_create_turma_horarios_table.php` | Class schedules |
| `matriculas` | `2025_01_01_000022_create_matriculas_table.php` | Student enrollments |

### Optimization Tables

| SQL Table | Migration File | Description |
|-----------|----------------|-------------|
| `otimizacao_execucoes` | `2025_01_01_000023_create_otimizacao_execucoes_table.php` | Optimization algorithm executions |
| `otimizacao_solucoes` | `2025_01_01_000024_create_otimizacao_solucoes_table.php` | Optimization solutions |
| `solucao_alocacoes` | `2025_01_01_000025_create_solucao_alocacoes_table.php` | Solution allocations |

### Metrics and Scoring Tables

| SQL Table | Migration File | Description |
|-----------|----------------|-------------|
| `configuracao_pontuacao` | `2025_01_01_000026_create_configuracao_pontuacao_table.php` | Scoring configuration |
| `metricas_aluno` | `2025_01_01_000027_create_metricas_aluno_table.php` | Student metrics |
| `metricas_turma` | `2025_01_01_000028_create_metricas_turma_table.php` | Class metrics |
| `metricas_professor` | `2025_01_01_000029_create_metricas_professor_table.php` | Teacher metrics |
| `metricas_sistema` | `2025_01_01_000030_create_metricas_sistema_table.php` | System metrics |

### Recommendation and Reporting Tables

| SQL Table | Migration File | Description |
|-----------|----------------|-------------|
| `recomendacoes_disciplinas` | `2025_01_01_000031_create_recomendacoes_disciplinas_table.php` | Discipline recommendations |
| `simulacoes_matricula` | `2025_01_01_000032_create_simulacoes_matricula_table.php` | Enrollment simulations |
| `relatorios` | `2025_01_01_000033_create_relatorios_table.php` | Reports |
| `alertas_sistema` | `2025_01_01_000034_create_alertas_sistema_table.php` | System alerts |

### Performance Optimization

| Migration File | Description |
|----------------|-------------|
| `2025_01_01_000035_add_indexes_for_performance.php` | Adds performance indexes on frequently queried columns |

## Pre-existing English Tables

These tables existed before and use English naming conventions:

| Table Name | Migration File | Description |
|-----------|----------------|-------------|
| `teachers` | `2025_01_01_000001_create_teachers_table.php` | Teachers (English naming) |
| `disciplines` | `2025_01_01_000002_create_disciplines_table.php` | Disciplines (English naming) |
| `classrooms` | `2025_01_01_000003_create_classrooms_table.php` | Classrooms (English naming) |
| `students` | `2025_01_01_000004_create_students_table.php` | Students (English naming) |
| `curriculum_matrix` | `2025_01_01_000005_create_curriculum_matrix_table.php` | Curriculum matrix |
| `curriculum_disciplines` | `2025_01_01_000006_create_curriculum_disciplines_table.php` | Curriculum-discipline pivot |
| `enrollments` | `2025_01_01_000007_create_enrollments_table.php` | Enrollments (English naming) |
| `classes` | `2025_01_01_000008_create_classes_table.php` | Classes (English naming) |

## Key Features

### Database Compatibility
All migrations are compatible with:
- PostgreSQL
- MySQL
- SQLite

### CHECK Constraints
CHECK constraints are implemented correctly for each database type:
- For PostgreSQL/MySQL: Added via `ALTER TABLE` statements
- For SQLite: Embedded directly in the `CREATE TABLE` statement

### Foreign Keys
All foreign key relationships from the SQL schema are preserved with appropriate `ON DELETE` actions:
- `CASCADE`: Deletes dependent records
- `SET NULL`: Sets foreign key to NULL when parent is deleted

### Indexes
Performance indexes are added for:
- Foreign key columns
- Frequently queried columns
- Composite keys for complex queries

### Unique Constraints
All unique constraints from the SQL schema are preserved:
- Single column uniqueness (e.g., `codigo`, `email`, `cpf`)
- Composite uniqueness (e.g., `curso_id + disciplina_id`)

## Running Migrations

```bash
# Run all migrations
php artisan migrate

# Rollback last batch
php artisan migrate:rollback

# Rollback specific steps
php artisan migrate:rollback --step=5

# Reset and re-run all migrations
php artisan migrate:fresh
```

## Testing

All migrations have been tested with:
- ✓ Migration execution (up)
- ✓ Migration rollback (down)
- ✓ Foreign key constraint validation
- ✓ CHECK constraint validation (where supported)
- ✓ Unique constraint validation

## Notes

1. **Dual Naming Convention**: The system now has both English and Portuguese table names. This maintains backward compatibility while implementing the complete SQL schema.

2. **No Triggers**: The SQL file includes PostgreSQL triggers and functions. These are not implemented in Laravel migrations but can be added separately if needed for PostgreSQL deployments.

3. **No Views**: The SQL file includes views (`vw_grade_aluno`, `vw_disciplinas_disponiveis`, `vw_estatisticas_janelas`). These can be created using Laravel database migrations or raw SQL if needed.

4. **Seeding**: The SQL file includes initial data for `configuracao_pontuacao`. This should be added to Laravel seeders if needed.

## Future Considerations

If you need to add the PostgreSQL-specific features (triggers, functions, views), consider:

1. Creating a separate migration for PostgreSQL-specific features
2. Using database detection to conditionally create these features
3. Adding Laravel event listeners to replicate trigger behavior in application code
