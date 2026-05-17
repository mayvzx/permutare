# Instalacao Local

## Requisitos

- XAMPP com Apache, PHP 8.2+ e MySQL/MariaDB
- Git
- Navegador
- Editor como VS Code

## Passo a passo

1. Coloque a pasta do projeto em `C:\xampp\htdocs\permutare`.
2. Inicie Apache e MySQL no painel do XAMPP.
3. Copie `.env.example` para `.env`.
4. Confira as credenciais:

```env
APP_URL=http://localhost/permutare/public
DB_HOST=localhost
DB_NAME=permutare
DB_USER=root
DB_PASS=
```

5. Abra o phpMyAdmin e crie um banco chamado `permutare` com charset `utf8mb4`.
6. Com o banco `permutare` selecionado, importe `database/schema.sql`.
7. Importe `database/seed.sql`.
8. Acesse `http://localhost/permutare/public`.

## Teste rapido

1. Entre com `admin@permutare.local` e senha `Admin@123456`.
2. Crie uma conta comum.
3. Publique um anuncio.
4. Entre com outro usuario e envie proposta.
5. Volte ao dono do anuncio e aceite.
6. Abra o chat, conclua a troca e avalie.
