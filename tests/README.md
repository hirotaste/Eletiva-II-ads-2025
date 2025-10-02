# Testes do Sistema de Gerenciamento Educacional

## Executar Testes

Para executar todos os testes:

```bash
php artisan test
# ou
vendor/bin/phpunit
```

Para executar testes com saída detalhada:

```bash
vendor/bin/phpunit --testdox
```

## Cobertura de Testes

### Controllers Testados

1. **TeacherController** (9 testes)
   - Lista de professores ativos
   - Criação de professor
   - Validação de campos obrigatórios
   - Validação de email único
   - Exibição de professor específico
   - Atualização de professor
   - Desativação de professor
   - Validação de tipo de emprego

2. **DisciplineController** (11 testes)
   - Lista de disciplinas ativas
   - Criação de disciplina
   - Validação de campos obrigatórios
   - Validação de código único
   - Exibição de disciplina específica
   - Atualização de disciplina
   - Desativação de disciplina
   - Validação de tipo inválido
   - Criação com pré-requisitos

3. **ClassroomController** (10 testes)
   - Lista de salas ativas
   - Criação de sala
   - Validação de campos obrigatórios
   - Validação de código único
   - Exibição de sala específica
   - Atualização de sala
   - Desativação de sala
   - Validação de tipo inválido
   - Validação de capacidade

4. **StudentController** (11 testes)
   - Lista de alunos ativos
   - Criação de aluno
   - Validação de campos obrigatórios
   - Validação de matrícula única
   - Validação de email único
   - Exibição de aluno específico
   - Atualização de aluno
   - Desativação de aluno
   - Validação de status

5. **EnrollmentController** (11 testes)
   - Lista de matrículas
   - Criação de matrícula
   - Validação de campos obrigatórios
   - Validação de aluno inválido
   - Validação de disciplina inválida
   - Validação de semestre
   - Exibição de matrícula específica
   - Atualização de status e nota
   - Validação de nota
   - Cancelamento de matrícula
   - Validação de status inválido

6. **CurriculumMatrixController** (11 testes)
   - Lista de matrizes ativas
   - Criação de matriz curricular
   - Validação de campos obrigatórios
   - Validação de código único
   - Exibição de matriz específica
   - Atualização de matriz
   - Desativação de matriz
   - Anexar disciplina à matriz
   - Validação ao anexar disciplina
   - Remover disciplina da matriz
   - Validação de semestre

## Configuração de Testes

Os testes utilizam:
- **Banco de dados**: SQLite em memória (`:memory:`)
- **RefreshDatabase**: Cada teste executa em um banco limpo
- **Factories**: Não utilizadas (criação manual de dados nos testes)

## Estrutura de Testes

```
tests/
├── Feature/          # Testes de integração
│   ├── TeacherControllerTest.php
│   ├── DisciplineControllerTest.php
│   ├── ClassroomControllerTest.php
│   ├── StudentControllerTest.php
│   ├── EnrollmentControllerTest.php
│   └── CurriculumMatrixControllerTest.php
├── Unit/             # Testes unitários (vazio por enquanto)
├── TestCase.php
└── CreatesApplication.php
```

## Notas

- Todos os testes validam as operações CRUD completas
- Validações de entrada são testadas extensivamente
- Soft deletes são implementados através de flags `is_active` ou `status`
- Banco SQLite é usado para compatibilidade e facilidade de setup
