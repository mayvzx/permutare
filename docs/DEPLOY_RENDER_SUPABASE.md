# Deploy no Render com Supabase

Este projeto pode rodar no Render como Web Service Docker e usar Supabase como banco PostgreSQL.

## Repositório

Use o repositório privado com o código completo:

```text
mayvzx/permutare
```

## Render

Crie um Web Service:

- Runtime: Docker
- Branch: `main`
- Dockerfile: `Dockerfile`
- Health check path: `/`
- Auto deploy: opcional

Render permite web services a partir de repositório Git ou Dockerfile. A própria documentação do Render recomenda Docker quando a linguagem/runtime não se encaixa nos presets nativos.

## Variáveis de ambiente

Configure no Render:

```env
APP_NAME=Permutare
APP_ENV=production
APP_DEBUG=false
APP_URL=https://SEU-SERVICO.onrender.com
SESSION_NAME=permutare_session

DB_CONNECTION=pgsql
DB_HOST=db.SEUPROJETO.supabase.co
DB_PORT=5432
DB_NAME=postgres
DB_USER=postgres
DB_PASS=SENHA_DO_BANCO_SUPABASE
DB_SSLMODE=require
DB_CHARSET=utf8

UPLOAD_MAX_SIZE=2097152
```

## Supabase

Execute os scripts:

```text
database/schema_postgres.sql
database/seed_postgres.sql
```

O projeto original local continua compatível com MySQL usando:

```text
database/schema.sql
database/seed.sql
```

## Observações

- Supabase usa PostgreSQL, não MySQL. Por isso existem scripts SQL separados.
- Uploads em disco no Render podem ser efêmeros se não houver disco persistente configurado. Para produção real, o ideal é mover imagens para Supabase Storage ou configurar Persistent Disk no Render.
- O login admin inicial continua:
  - E-mail: `admin@permutare.local`
  - Senha: `Admin@123456`
