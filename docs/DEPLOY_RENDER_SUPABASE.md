# Deploy no Render com Supabase

Este projeto pode rodar no Render como Web Service Docker e usar Supabase como banco PostgreSQL.

## Repositório

Use o repositório privado com o código completo:

```text
mayvzx/permutare
```

## Supabase

Projeto configurado:

```text
Nome: permutare
Project ID: obhvcrthghmdujdndgfv
Host: db.obhvcrthghmdujdndgfv.supabase.co
Região: sa-east-1
Status validado: ACTIVE_HEALTHY
```

Para Render, use o **Session Pooler** do Supabase, não a conexão direta. Render não aceita IPv6 para conexão direta com Supabase em muitos ambientes, e o Supabase recomenda Supavisor quando o host não tem IPv6.

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

O arquivo `render.yaml` já está pronto para criar um Web Service Docker.

Configuração esperada:

- Runtime: Docker
- Branch: `main`
- Dockerfile: `Dockerfile`
- Health check path: `/`
- Auto deploy: ativo

## Variáveis de ambiente

Configure no Render:

```env
APP_NAME=Permutare
APP_ENV=production
APP_DEBUG=false
APP_URL=https://SEU-SERVICO.onrender.com
SESSION_NAME=permutare_session

DB_CONNECTION=pgsql
DB_HOST=aws-0-sa-east-1.pooler.supabase.com
DB_PORT=5432
DB_NAME=postgres
DB_USER=postgres.obhvcrthghmdujdndgfv
DB_PASS=SENHA_DO_BANCO_SUPABASE
DB_SSLMODE=require
DB_CHARSET=utf8

UPLOAD_MAX_SIZE=2097152
```

## Local

O projeto local continua compatível com MySQL usando:

```text
database/schema.sql
database/seed.sql
```

## Observações

- Supabase usa PostgreSQL, não MySQL. Por isso existem scripts SQL separados.
- No Render, use o Session Pooler em `aws-0-sa-east-1.pooler.supabase.com` com usuário `postgres.obhvcrthghmdujdndgfv`.
- Evite o Transaction Pooler para este PHP/PDO porque ele não suporta prepared statements da forma esperada.
- Uploads em disco no Render podem ser efêmeros se não houver disco persistente configurado. Para produção real, o ideal é mover imagens para Supabase Storage ou configurar Persistent Disk no Render.
- Login admin inicial:
  - E-mail: `admin@permutare.local`
  - Senha: `Admin@123456`
