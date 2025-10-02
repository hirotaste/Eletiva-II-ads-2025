# Guia de Contribuição

Obrigado por considerar contribuir com o Sistema de Gerenciamento Educacional!

## Como Contribuir

### Reportando Bugs

Se você encontrar um bug, por favor crie uma issue incluindo:
- Descrição clara do problema
- Passos para reproduzir
- Comportamento esperado
- Comportamento atual
- Screenshots (se aplicável)
- Versão do PHP/Laravel
- Versão do banco de dados

### Sugerindo Melhorias

Para sugerir uma nova funcionalidade:
1. Verifique se já não existe uma issue similar
2. Crie uma nova issue com o label "enhancement"
3. Descreva claramente a funcionalidade
4. Explique o caso de uso
5. Se possível, sugira uma implementação

### Pull Requests

1. **Fork o repositório**
   ```bash
   git clone https://github.com/hirotaste/Eletiva-II-ads-2025.git
   cd Eletiva-II-ads-2025
   ```

2. **Crie uma branch para sua feature**
   ```bash
   git checkout -b feature/minha-nova-feature
   ```

3. **Faça suas alterações**
   - Siga os padrões de código do projeto
   - Adicione comentários quando necessário
   - Mantenha as alterações focadas e atômicas

4. **Teste suas alterações**
   ```bash
   # Execute os testes (quando disponíveis)
   php artisan test
   ```

5. **Commit suas alterações**
   ```bash
   git add .
   git commit -m "Adiciona nova funcionalidade X"
   ```

6. **Push para seu fork**
   ```bash
   git push origin feature/minha-nova-feature
   ```

7. **Abra um Pull Request**
   - Descreva claramente o que foi alterado
   - Referencie issues relacionadas
   - Inclua screenshots se aplicável

## Padrões de Código

### PHP/Laravel

- Siga a PSR-12 para estilo de código
- Use type hints sempre que possível
- Escreva código autodocumentado
- Adicione DocBlocks para métodos públicos

Exemplo:
```php
/**
 * Busca um professor pelo ID.
 *
 * @param int $id
 * @return Teacher
 * @throws ModelNotFoundException
 */
public function findTeacher(int $id): Teacher
{
    return Teacher::findOrFail($id);
}
```

### Migrations

- Use nomes descritivos
- Sempre forneça método `down()`
- Use foreign keys com cascade apropriado
- Documente campos JSON

### Models

- Defina `$fillable` ou `$guarded`
- Configure `$casts` para tipos especiais
- Adicione relacionamentos
- Use scopes quando apropriado

### Controllers

- Mantenha controllers enxutos
- Valide dados de entrada
- Use Resource Controllers quando possível
- Retorne respostas JSON consistentes

## Estrutura de Commit

Use mensagens de commit claras e descritivas:

```
tipo: descrição curta

Descrição mais detalhada se necessário.

Referências: #123
```

Tipos de commit:
- `feat`: Nova funcionalidade
- `fix`: Correção de bug
- `docs`: Alterações na documentação
- `style`: Formatação, ponto e vírgula, etc
- `refactor`: Refatoração de código
- `test`: Adição ou modificação de testes
- `chore`: Manutenção geral

## Diretrizes de Código

### DRY (Don't Repeat Yourself)
Evite duplicação de código. Se você está copiando código, considere criar uma função auxiliar.

### KISS (Keep It Simple, Stupid)
Mantenha o código simples e legível. Código complexo é difícil de manter.

### SOLID Principles
- Single Responsibility
- Open/Closed
- Liskov Substitution
- Interface Segregation
- Dependency Inversion

### Nomenclatura

#### Classes
```php
// Models - Singular
class Teacher extends Model { }
class Student extends Model { }

// Controllers - Singular + Controller
class TeacherController extends Controller { }

// Resources - Singular + Resource
class TeacherResource extends JsonResource { }
```

#### Métodos
```php
// Use verbos para ações
public function createTeacher() { }
public function updateStudent() { }
public function deleteClassroom() { }

// Use "is" ou "has" para booleanos
public function isActive() { }
public function hasPrerequisites() { }
```

#### Variáveis
```php
// Use camelCase
$teacherName = 'João';
$studentCount = 10;

// Arrays no plural
$teachers = Teacher::all();
$students = Student::all();
```

## Testes

Quando adicionar funcionalidades, inclua testes:

```php
public function test_teacher_can_be_created()
{
    $teacher = Teacher::create([
        'name' => 'Prof. Test',
        'email' => 'test@example.com',
        'specialization' => 'Test',
        'employment_type' => 'full_time',
    ]);

    $this->assertDatabaseHas('teachers', [
        'email' => 'test@example.com'
    ]);
}
```

## Documentação

### Atualize a documentação quando:
- Adicionar novos endpoints
- Modificar estrutura do banco
- Alterar comportamento existente
- Adicionar novas configurações

### Formato da Documentação
- Use Markdown
- Inclua exemplos de código
- Adicione screenshots quando relevante
- Mantenha tabela de conteúdos atualizada

## Code Review

### Para Revisores
- Seja construtivo e respeitoso
- Explique o "porquê" das sugestões
- Aprecie as contribuições
- Teste localmente quando necessário

### Para Contribuidores
- Esteja aberto a feedback
- Responda aos comentários
- Atualize o PR conforme necessário
- Seja paciente durante a revisão

## Recursos Adicionais

- [Laravel Documentation](https://laravel.com/docs)
- [PHP The Right Way](https://phptherightway.com/)
- [PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)
- [Git Flow](https://nvie.com/posts/a-successful-git-branching-model/)

## Dúvidas?

Se tiver dúvidas sobre como contribuir:
1. Leia a documentação existente
2. Procure em issues fechadas
3. Abra uma issue com sua dúvida
4. Entre em contato com os mantenedores

## Código de Conduta

- Seja respeitoso e inclusivo
- Aceite críticas construtivas
- Foque no que é melhor para a comunidade
- Mostre empatia com outros membros

Obrigado por contribuir! 🎉
