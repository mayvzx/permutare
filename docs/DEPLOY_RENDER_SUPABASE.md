# Deploy no Render com Supabase

Este projeto pode rodar no Render como Web Service Docker e usar Supabase como banco PostgreSQL.

## Repositorio

Use o repositorio privado com o codigo completo:

```text
mayvzx/permutare
```

## Supabase

Projeto configurado:

```text
Nome: permutare
Project ID: obhvcrthghmdujdndgfv
Host direto: db.obhvcrthghmdujdndgfv.supabase.co
Regiao: sa-east-1
Status validado: ACTIVE_HEALTHY
```

Para Render, use o **Session Pooler** do Supabase. A conexao direta do Supabase pode depender de IPv6, e o Render normalmente precisa de uma conexao compativel com IPv4.

Nao tente adivinhar o host do pooler. Copie o valor exato em:

```text
Supabase Dashboard > Project > Connect > Session pooler
```

O erro `tenant/user not found` geralmente significa que o usuario `postgres.<project-ref>` foi usado no host errado do pooler.

Migrations aplicadas:

```text
create_permutare_schema
seed_initial_admin
```

Scripts correspondentes no projeto:

```text
database/schema_postgres.sql
database/seed_postgres.sql
```

## Render

O arquivo `render.yaml` esta pronto para criar um Web Service Docker.

Configuracao esperada:

- Runtime: Docker
- Branch: `main`
- Dockerfile: `Dockerfile`
- Health check path: `/health`
- Auto deploy: ativo

## Variaveis de ambiente

Opcao recomendada: configure `DATABASE_URL` com a string oficial do **Session Pooler** copiada do painel do Supabase.

```env
APP_NAME=Permutare
APP_ENV=production
APP_DEBUG=false
APP_URL=https://SEU-SERVICO.onrender.com
SESSION_NAME=permutare_session

DATABASE_URL=postgres://postgres.PROJECT_REF:SENHA_URL_ENCODED@HOST_EXATO_DO_SESSION_POOLER:5432/postgres?sslmode=require

UPLOAD_MAX_SIZE=2097152
```

Se a senha tiver caracteres especiais, como `@`, `#`, `%`, `/` ou espaco, eles precisam estar codificados na URL. Exemplo: `@` vira `%40`.

Opcao alternativa: configurar variaveis separadas.

```env
DB_CONNECTION=pgsql
DB_HOST=HOST_EXATO_DO_SESSION_POOLER
DB_PORT=5432
DB_NAME=postgres
DB_USER=USUARIO_EXATO_DO_SESSION_POOLER
DB_PASS=SENHA_DO_BANCO_SUPABASE
DB_SSLMODE=require
DB_CHARSET=utf8
```

Use apenas o host e o usuario exibidos no bloco **Session pooler** do Supabase. Nao use o Transaction Pooler para este projeto, porque ele pode causar problemas com prepared statements em PDO.

## Local

O projeto local continua compativel com MySQL usando:

```text
database/schema.sql
database/seed.sql
```

## Observacoes

- Supabase usa PostgreSQL, nao MySQL. Por isso existem scripts SQL separados.
- O endpoint `/health` nao valida banco; ele serve apenas para o Render confirmar que o container subiu.
- Uploads em disco no Render podem ser efemeros se nao houver disco persistente configurado. Para producao real, o ideal e mover imagens para Supabase Storage ou configurar Persistent Disk no Render.
- Login admin inicial:
  - E-mail: `admin@permutare.local`
  - Senha: `Admin@123456`
