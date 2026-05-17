# Banco de Dados

## Tabelas

- `users`: autenticacao, role e status.
- `user_profiles`: dados publicos e academicos.
- `anuncios`: itens publicados.
- `propostas`: propostas de troca e status.
- `mensagens`: chat interno.
- `avaliacoes`: notas e comentarios.
- `user_scores`: reputacao agregada.
- `denuncias`: moderacao.
- `login_attempts`: rate limit simples.
- `email_verifications`: tokens de verificacao.

## Relacionamentos principais

- `users 1:1 user_profiles`
- `users 1:N anuncios`
- `anuncios 1:N propostas`
- `propostas 1:N mensagens`
- `propostas 1:N avaliacoes`
- `users 1:1 user_scores`

## Regras importantes

- `email` e unico.
- Avaliacao e unica por `proposta_id + reviewer_id`.
- Uploads guardam apenas o caminho relativo.
- Exclusao de anuncio e logica via status `removed`.
- Usuario deletado usa status `deleted`.

## Scripts

- `schema.sql`: cria banco e tabelas.
- `seed.sql`: cria admin inicial.
- `reset.sql`: remove tabelas para recriar o ambiente.
