# Guia de Instalação

Este guia fornece instruções detalhadas para configurar o Sistema de Gerenciamento Educacional.

## Pré-requisitos

### Software Necessário

1. **PHP 8.1 ou superior**
   - Extensões: PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath

2. **Composer** (Gerenciador de dependências PHP)
   - Download: https://getcomposer.org/download/

3. **Banco de Dados** (escolha um):
   - MySQL 5.7+ ou MariaDB 10.3+
   - PostgreSQL 10+
   - SQLite 3.8.8+

4. **Servidor Web** (opcional para desenvolvimento):
   - Apache 2.4+
   - Nginx 1.15+
   - PHP Built-in Server (para desenvolvimento)

### Verificando Requisitos

```bash
# Verificar versão do PHP
php -v

# Verificar extensões PHP instaladas
php -m

# Verificar Composer
composer --version
```

## Instalação

### 1. Clone o Repositório

```bash
git clone https://github.com/hirotaste/Eletiva-II-ads-2025.git
cd Eletiva-II-ads-2025
```

### 2. Instale as Dependências (Quando Laravel completo estiver configurado)

```bash
composer install
```

### 3. Configure o Ambiente

```bash
# Copie o arquivo de exemplo
cp .env.example .env

# Gere a chave da aplicação (quando Laravel estiver completo)
php artisan key:generate
```

### 4. Configure o Banco de Dados

Edite o arquivo `.env` e configure as credenciais do banco:

#### Para MySQL/MariaDB:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eletiva_db
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

#### Para PostgreSQL:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=eletiva_db
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

#### Para SQLite (desenvolvimento):
```env
DB_CONNECTION=sqlite
DB_DATABASE=/caminho/completo/para/database.sqlite
```

### 5. Crie o Banco de Dados

#### MySQL/MariaDB:
```bash
mysql -u root -p
CREATE DATABASE eletiva_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### PostgreSQL:
```bash
psql -U postgres
CREATE DATABASE eletiva_db;
\q
```

#### SQLite:
```bash
touch database/database.sqlite
```

### 6. Execute as Migrations

```bash
php artisan migrate
```

### 7. (Opcional) Popule o Banco com Dados de Teste

```bash
php artisan db:seed
```

### 8. Inicie o Servidor de Desenvolvimento

```bash
php artisan serve
```

A aplicação estará disponível em: `http://localhost:8000`

## Configurações Adicionais

### Permissões de Diretório

Para ambientes Linux/Mac:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Configuração do Servidor Web

#### Apache

Certifique-se de que o módulo `mod_rewrite` está habilitado:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Configure o VirtualHost:

```apache
<VirtualHost *:80>
    ServerName eletiva.local
    DocumentRoot /caminho/para/Eletiva-II-ads-2025/public

    <Directory /caminho/para/Eletiva-II-ads-2025/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/eletiva_error.log
    CustomLog ${APACHE_LOG_DIR}/eletiva_access.log combined
</VirtualHost>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name eletiva.local;
    root /caminho/para/Eletiva-II-ads-2025/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Instalação com Docker (Alternativa)

### Usando Laravel Sail

```bash
# Instale as dependências via Docker
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs

# Configure o alias do Sail
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'

# Inicie os containers
sail up -d

# Execute as migrations
sail artisan migrate

# Seed o banco de dados
sail artisan db:seed
```

### Docker Compose Manual

Crie um arquivo `docker-compose.yml`:

```yaml
version: '3'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    depends_on:
      - db
    environment:
      - DB_HOST=db
      - DB_DATABASE=eletiva_db
      - DB_USERNAME=root
      - DB_PASSWORD=secret

  db:
    image: mysql:8.0
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: secret
      MYSQL_DATABASE: eletiva_db
    volumes:
      - dbdata:/var/lib/mysql

volumes:
  dbdata:
```

## Testes

### Configurar Banco de Testes

Adicione ao `.env`:

```env
DB_CONNECTION_TEST=sqlite
DB_DATABASE_TEST=:memory:
```

### Executar Testes

```bash
php artisan test
```

## Troubleshooting

### Erro: "Class not found"

```bash
composer dump-autoload
```

### Erro: "SQLSTATE[HY000] [2002] Connection refused"

- Verifique se o serviço do banco está rodando
- Confirme as credenciais no `.env`
- Teste a conexão manualmente

### Erro: "Permission denied" ao acessar storage

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Erro: "419 Page Expired" (CSRF Token)

```bash
php artisan cache:clear
php artisan config:clear
```

### Porta 8000 já está em uso

```bash
# Use uma porta diferente
php artisan serve --port=8001
```

## Ambientes

### Desenvolvimento
- Debug habilitado
- Logs detalhados
- Hot reload (com Vite)

### Produção
- Debug desabilitado
- Cache otimizado
- Assets compilados

```bash
# Otimizar para produção
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Manutenção

### Backup do Banco de Dados

```bash
# MySQL
mysqldump -u usuario -p eletiva_db > backup.sql

# PostgreSQL
pg_dump eletiva_db > backup.sql

# Restaurar
mysql -u usuario -p eletiva_db < backup.sql
```

### Atualizar o Sistema

```bash
git pull origin main
composer install
php artisan migrate
php artisan cache:clear
```

## Recursos Adicionais

- [Documentação Laravel](https://laravel.com/docs)
- [Laracasts (Tutoriais em vídeo)](https://laracasts.com)
- [Laravel News](https://laravel-news.com)

## Suporte

Se encontrar problemas durante a instalação:

1. Consulte a seção de Troubleshooting
2. Verifique as [Issues existentes](https://github.com/hirotaste/Eletiva-II-ads-2025/issues)
3. Abra uma nova issue com detalhes do erro

## Próximos Passos

Após a instalação:

1. Leia a [Documentação da API](API_DOCUMENTATION.md)
2. Explore o [Esquema do Banco de Dados](DATABASE_SCHEMA.md)
3. Consulte o [Guia de Contribuição](CONTRIBUTING.md)
4. Teste os endpoints da API
