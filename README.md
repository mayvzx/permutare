# Permutare

Marketplace universitario de permutas feito em PHP puro, MySQL e MVC simples. A v1.0 cobre o ciclo principal: cadastro, login, anuncios, propostas, chat apos aceite, conclusao de troca, avaliacoes, reputacao, denuncias e painel admin.

## Tecnologias

- PHP 8.2+
- MySQL ou MariaDB
- PDO
- HTML5, CSS3 e JavaScript vanilla
- Apache/XAMPP no ambiente local
- InfinityFree para producao manual

## Instalar localmente

1. Copie o projeto para uma pasta acessivel pelo Apache do XAMPP, por exemplo `C:\xampp\htdocs\permutare`.
2. Copie `.env.example` para `.env`.
3. Ajuste `APP_URL=http://localhost/permutare/public`.
4. Crie um banco chamado `permutare` com charset `utf8mb4`.
5. Com esse banco selecionado, importe `database/schema.sql`.
6. Importe `database/seed.sql` para criar o admin inicial.
7. Acesse `http://localhost/permutare/public`.

Admin inicial:

- E-mail: `admin@permutare.local`
- Senha: `Admin@123456`

Troque essa senha quando criar um fluxo proprio de alteracao de senha.

## Estrutura

```text
app/          Controllers, Models, Views, Helpers e Services
bootstrap/    Inicializacao da aplicacao
config/       Configuracoes
core/         Router, Controller, Model, Database, Request e Session
database/     SQL de schema, seed e reset
docs/         Documentacao tecnica
public/       Entrada publica, CSS, JS e imagens
routes/       Rotas web
storage/      Uploads e logs protegidos
tests_manual/ Checklists de QA manual
```

## Funcionalidades da v1.0

- Cadastro, login, logout e verificacao simples por token
- Perfil publico e edicao de perfil
- CRUD de anuncios com upload seguro
- Feed com busca, filtros e ordenacao basica
- Propostas enviadas e recebidas
- Aceitar, recusar, cancelar e concluir proposta
- Chat interno para propostas aceitas
- Avaliacao mutua apos conclusao
- Reputacao agregada
- Denuncias
- Painel administrativo para usuarios, anuncios e denuncias

## Deploy no InfinityFree

O deploy e manual. Consulte [docs/DEPLOY_INFINITYFREE.md](docs/DEPLOY_INFINITYFREE.md). Nao envie `.env` com senhas reais para o GitHub.

## Seguranca

O projeto usa PDO com prepared statements, escape de saida com `e()`, CSRF em formularios POST, validacao de ownership, sessoes regeneradas no login, upload com MIME real e rate limit simples no login. Veja [docs/SECURITY.md](docs/SECURITY.md).

## Roadmap

Veja [docs/ROADMAP.md](docs/ROADMAP.md).
