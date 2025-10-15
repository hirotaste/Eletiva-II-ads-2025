# Sistema de Autenticação e Autorização

Este documento descreve as funcionalidades de autenticação e autorização implementadas no sistema.

## Funcionalidades Implementadas

### 1. Recuperação de Senha

O sistema possui um fluxo completo de recuperação de senha:

- **Rota para solicitar recuperação**: `GET /forgot-password`
- **Enviar link de recuperação**: `POST /forgot-password`
- **Formulário de redefinição**: `GET /reset-password/{token}`
- **Processar nova senha**: `POST /reset-password`

#### Como usar:
1. Na tela de login, clique em "Esqueceu a senha?"
2. Digite seu e-mail e clique em "Enviar Link de Recuperação"
3. Você receberá um e-mail com link para redefinir a senha (se configurado)
4. Clique no link e defina sua nova senha

### 2. Verificação de E-mail

O sistema suporta verificação de e-mail para novos usuários:

- **Tela de aviso**: `GET /email/verify`
- **Verificar e-mail**: `GET /email/verify/{id}/{hash}`
- **Reenviar verificação**: `POST /email/verification-notification`

O modelo `User` implementa `MustVerifyEmail`, permitindo restringir acesso até que o e-mail seja verificado.

### 3. Middleware de Níveis de Usuário

Foram criados três middleware para controle de acesso baseado em níveis:

#### NivelAdmMiddleware
- **Arquivo**: `app/Http/Middleware/NivelAdmMiddleware.php`
- **Uso**: Apenas administradores podem acessar
- **Rotas protegidas**:
  - Criar, editar e deletar professores
  - Criar, editar e deletar estudantes
  - Criar, editar e deletar disciplinas
  - Criar, editar e deletar salas

#### NivelProfessorMiddleware
- **Arquivo**: `app/Http/Middleware/NivelProfessorMiddleware.php`
- **Uso**: Professores e administradores podem acessar
- **Rotas protegidas**:
  - Visualizar listas de professores
  - Visualizar listas de estudantes
  - Visualizar disciplinas
  - Visualizar salas

#### NivelEstudanteMiddleware
- **Arquivo**: `app/Http/Middleware/NivelEstudanteMiddleware.php`
- **Uso**: Todos os usuários autenticados podem acessar
- **Função**: Permite que estudantes vejam informações, mas sem permissões de edição

### 4. Dashboards Diferenciados por Nível

Cada nível de usuário tem seu próprio dashboard personalizado:

#### Dashboard do Administrador
- **View**: `resources/views/dashboards/admin.blade.php`
- **Funcionalidades**:
  - Estatísticas completas do sistema
  - Ações rápidas para criar recursos
  - Logs de acesso recentes
  - Acesso completo a todos os módulos

#### Dashboard do Professor
- **View**: `resources/views/dashboards/professor.blade.php`
- **Funcionalidades**:
  - Estatísticas de disciplinas e estudantes
  - Acesso a listas de estudantes e disciplinas
  - Visualização de salas de aula

#### Dashboard do Estudante
- **View**: `resources/views/dashboards/estudante.blade.php`
- **Funcionalidades**:
  - Informações acadêmicas
  - Visualização de disciplinas disponíveis
  - Acesso a informações de professores

### 5. Sistema de Logs de Acesso

Foi implementado um sistema completo de auditoria:

#### Tabela access_logs
- **Campos**:
  - `user_id`: ID do usuário (pode ser null para tentativas falhas)
  - `action`: Tipo de ação (login, logout, dashboard_access, page_access, etc.)
  - `ip_address`: Endereço IP do usuário
  - `user_agent`: Navegador/dispositivo utilizado
  - `url`: URL acessada
  - `method`: Método HTTP (GET, POST, etc.)
  - `description`: Descrição detalhada da ação
  - `created_at`: Data/hora do acesso

#### Middleware LogUserAccess
- **Arquivo**: `app/Http/Middleware/LogUserAccess.php`
- **Função**: Registra automaticamente todos os acessos de usuários autenticados

#### Logs Implementados
- Login bem-sucedido
- Tentativas de login falhadas
- Logout
- Acesso ao dashboard
- Acesso a páginas protegidas (quando middleware é aplicado)

## Níveis de Usuário

O sistema possui três níveis de usuário:

### Admin
- **Level**: `admin`
- **Método helper**: `$user->isAdmin()`
- **Permissões**: Acesso total ao sistema

### Professor
- **Level**: `professor`
- **Método helper**: `$user->isProfessor()`
- **Permissões**: Visualização e gestão de disciplinas e estudantes

### Estudante
- **Level**: `estudante`
- **Método helper**: `$user->isEstudante()`
- **Permissões**: Visualização de informações acadêmicas

## Estrutura de Rotas

### Rotas Públicas
```php
GET  /login               - Tela de login
POST /login               - Processar login
POST /logout              - Logout
GET  /forgot-password     - Solicitar recuperação
POST /forgot-password     - Enviar link
GET  /reset-password/{token} - Formulário de redefinição
POST /reset-password      - Processar redefinição
```

### Rotas Autenticadas
```php
GET /dashboard - Dashboard (redireciona baseado no nível)
```

### Rotas de Admin
```php
GET    /teachers/create    - Criar professor
POST   /teachers           - Salvar professor
GET    /teachers/{id}/edit - Editar professor
PUT    /teachers/{id}      - Atualizar professor
DELETE /teachers/{id}      - Deletar professor

// Mesma estrutura para students, disciplines, classrooms
```

### Rotas de Professor e Admin
```php
GET /teachers    - Listar professores
GET /students    - Listar estudantes
GET /disciplines - Listar disciplinas
GET /classrooms  - Listar salas
```

## Credenciais de Teste

Para testar o sistema, use as seguintes credenciais:

### Administrador
- **E-mail**: `stefano@admin.com`
- **Senha**: `password`

### Professor
- **E-mail**: `joao.silva@instituicao.edu.br`
- **Senha**: `password`

### Estudante
- **E-mail**: `carlos.souza@aluno.edu.br`
- **Senha**: `password`

## Como Testar

1. **Instalar dependências**:
   ```bash
   composer install
   ```

2. **Configurar ambiente**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configurar banco de dados** (SQLite para testes):
   ```bash
   touch database/database.sqlite
   # Alterar DB_CONNECTION para sqlite no .env
   ```

4. **Executar migrações**:
   ```bash
   php artisan migrate
   ```

5. **Criar usuários de teste**:
   ```bash
   php artisan db:seed --class=UserSeeder
   ```

6. **Iniciar servidor**:
   ```bash
   php artisan serve
   ```

7. **Acessar**: http://localhost:8000/login

## Segurança

### Proteções Implementadas
- ✅ Middleware de autenticação em todas as rotas protegidas
- ✅ Middleware de nível de usuário para controle de acesso
- ✅ Logs de todas as tentativas de login
- ✅ Proteção CSRF em todos os formulários
- ✅ Senhas hasheadas com bcrypt
- ✅ Tokens de recuperação de senha com expiração
- ✅ Verificação de e-mail opcional

### Recomendações de Produção
- [ ] Configurar envio de e-mails (SMTP)
- [ ] Habilitar HTTPS
- [ ] Configurar rate limiting em rotas de login
- [ ] Implementar 2FA (autenticação de dois fatores)
- [ ] Revisar logs de acesso regularmente
- [ ] Implementar alertas para atividades suspeitas

## Manutenção

### Visualizar Logs de Acesso
```php
// No Tinker ou em um controller
$logs = App\Models\AccessLog::with('user')
    ->orderBy('created_at', 'desc')
    ->take(100)
    ->get();

foreach ($logs as $log) {
    echo $log->user->name ?? 'Guest';
    echo ' - ' . $log->action;
    echo ' - ' . $log->created_at;
    echo PHP_EOL;
}
```

### Limpar Logs Antigos
```php
// Deletar logs com mais de 90 dias
App\Models\AccessLog::where('created_at', '<', now()->subDays(90))->delete();
```

## Próximos Passos

- [ ] Implementar notificações por e-mail
- [ ] Adicionar autenticação de dois fatores (2FA)
- [ ] Criar painel de auditoria para administradores
- [ ] Implementar rate limiting
- [ ] Adicionar filtros avançados nos logs
- [ ] Criar relatórios de acesso
- [ ] Implementar permissões granulares
- [ ] Adicionar suporte a roles e permissões customizadas
