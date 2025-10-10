# Segurança - Security Guide

Este documento descreve as medidas de segurança implementadas no sistema de gerenciamento educacional.

## Índice

- [Autenticação](#autenticação)
- [Autorização](#autorização)
- [Validação de Dados](#validação-de-dados)
- [Tratamento de Erros](#tratamento-de-erros)
- [Middleware](#middleware)
- [Boas Práticas](#boas-práticas)

## Autenticação

### Configuração Atual

O sistema está preparado para autenticação através do Laravel Auth system. A configuração está em `config/auth.php`.

### Implementação Futura

Para implementar autenticação completa, recomendamos:

1. **Laravel Sanctum** para API tokens:
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

2. **Configurar rotas protegidas**:
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('teachers', TeacherController::class);
});
```

## Autorização

### Policies Implementadas

O sistema possui Policies para controlar o acesso aos recursos:

#### TeacherPolicy

- `viewAny()`: Visualizar lista de professores (público)
- `view()`: Visualizar professor específico (público)
- `create()`: Criar professor (requer autenticação)
- `update()`: Atualizar professor (requer autenticação)
- `delete()`: Deletar professor (requer autenticação)

#### DisciplinePolicy

- `viewAny()`: Visualizar lista de disciplinas (público)
- `view()`: Visualizar disciplina específica (público)
- `create()`: Criar disciplina (requer autenticação)
- `update()`: Atualizar disciplina (requer autenticação)
- `delete()`: Deletar disciplina (requer autenticação)

### Uso em Controllers

```php
public function update(DisciplineRequest $request, Discipline $discipline)
{
    $this->authorize('update', $discipline);
    // ... resto do código
}
```

## Validação de Dados

### Form Requests

Todas as operações de criação e atualização usam Form Requests para validação centralizada:

- **TeacherRequest**: Validação de dados de professores
- **DisciplineRequest**: Validação de dados de disciplinas
- **StudentRequest**: Validação de dados de alunos
- **ClassroomRequest**: Validação de dados de salas
- **EnrollmentRequest**: Validação de dados de matrículas

### Exemplo de Validação

```php
// TeacherRequest
public function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:teachers,email',
        'specialization' => 'required|string|max:255',
        'employment_type' => 'required|in:full_time,part_time,contractor',
    ];
}
```

### Mensagens Personalizadas

Todas as Form Requests incluem mensagens de erro em português:

```php
public function messages(): array
{
    return [
        'name.required' => 'O nome do professor é obrigatório.',
        'email.unique' => 'Este email já está cadastrado.',
    ];
}
```

## Tratamento de Erros

### Exception Handler Customizado

O sistema possui um handler centralizado que trata exceções de forma consistente:

```php
// app/Exceptions/Handler.php
- Retorna respostas JSON para APIs
- Mensagens de erro claras e consistentes
- Status codes HTTP apropriados
```

### Exceções Customizadas

#### ResourceNotFoundException

Usada quando um recurso não é encontrado:
```php
throw new ResourceNotFoundException('Teacher', $id);
// Retorna: 404 com mensagem "Teacher with identifier '123' not found."
```

#### ValidationException

Usada para erros de validação de negócio:
```php
throw new ValidationException('Email already exists', [
    'email' => ['The email has already been taken.']
]);
// Retorna: 422 com detalhes do erro
```

#### UnauthorizedException

Usada quando o usuário não tem permissão:
```php
throw new UnauthorizedException('You cannot modify this resource.');
// Retorna: 403 com mensagem de erro
```

## Middleware

### ValidateJsonRequest

Valida que as requisições API têm os headers corretos:
- Verifica `Accept: application/json`
- Verifica `Content-Type: application/json` para POST/PUT/PATCH

### LogApiRequests

Registra todas as requisições API para auditoria:
- Método HTTP
- URI acessada
- IP do cliente
- User Agent
- Status da resposta
- Tempo de resposta

### Configuração

Adicione no `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'api' => [
        'throttle:api',
        \App\Http\Middleware\ValidateJsonRequest::class,
        \App\Http\Middleware\LogApiRequests::class,
    ],
];
```

## Boas Práticas

### 1. Proteção contra SQL Injection

✅ Usar sempre Eloquent ORM ou Query Builder
✅ Nunca interpolar variáveis diretamente em SQL

```php
// ✅ CORRETO
Teacher::where('email', $email)->first();

// ❌ ERRADO
DB::select("SELECT * FROM teachers WHERE email = '$email'");
```

### 2. Proteção contra Mass Assignment

✅ Definir `$fillable` ou `$guarded` em todos os models

```php
protected $fillable = [
    'name',
    'email',
    'specialization',
];
```

### 3. Validação de Input

✅ Sempre validar dados de entrada usando Form Requests
✅ Usar regras de validação apropriadas
✅ Validar tipos de dados (integer, boolean, etc.)

### 4. HTTPS

⚠️ Em produção, sempre use HTTPS
⚠️ Configure `APP_URL` com https://

```env
APP_URL=https://seu-dominio.com
```

### 5. Rate Limiting

Configure rate limiting para proteger contra ataques:

```php
Route::middleware('throttle:60,1')->group(function () {
    // Máximo 60 requisições por minuto
});
```

### 6. CORS

Configure CORS adequadamente para APIs públicas:

```php
// config/cors.php
'paths' => ['api/*'],
'allowed_origins' => ['*'], // Em produção, especificar domínios
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
```

### 7. Logs e Auditoria

✅ Registrar operações críticas
✅ Monitorar tentativas de acesso não autorizado
✅ Revisar logs regularmente

```php
Log::info('Teacher created', ['id' => $teacher->id, 'user' => auth()->id()]);
```

### 8. Senhas e Dados Sensíveis

✅ Nunca armazenar senhas em texto plano
✅ Usar `bcrypt()` ou `Hash::make()`
✅ Não logar dados sensíveis

```php
$user->password = bcrypt($request->password);
```

### 9. Tokens e Chaves

⚠️ Nunca commitar chaves ou tokens no Git
⚠️ Usar variáveis de ambiente (.env)
⚠️ Rotacionar chaves regularmente

### 10. Atualizações

✅ Manter Laravel e dependências atualizadas
✅ Revisar security advisories
✅ Fazer backup antes de atualizar

```bash
composer update
composer audit
```

## Checklist de Segurança

- [ ] HTTPS configurado
- [ ] Autenticação implementada
- [ ] Autorização (Policies) configurada
- [ ] Validação de entrada em todos os endpoints
- [ ] Exception handling apropriado
- [ ] Rate limiting configurado
- [ ] CORS configurado
- [ ] Logs de auditoria habilitados
- [ ] Backup regular configurado
- [ ] Dependências atualizadas
- [ ] `.env` protegido (não no Git)
- [ ] Chaves de aplicação geradas e únicas

## Recursos Adicionais

- [Laravel Security Documentation](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Laravel Policies](https://laravel.com/docs/authorization)

## Suporte

Para questões de segurança, entre em contato através dos canais oficiais do projeto.
