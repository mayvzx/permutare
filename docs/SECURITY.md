# Seguranca

## SQL Injection

Todas as consultas da aplicacao passam por PDO com prepared statements. Controllers nao devem montar SQL diretamente.

## XSS

Saidas dinamicas em views usam `e()`, que aplica `htmlspecialchars` com `ENT_QUOTES` e UTF-8.

## CSRF

Todo POST passa pela verificacao central do `Router`. Formularios usam `csrf_field()`.

## IDOR

Controllers validam ownership e participacao:

- Apenas dono edita, pausa ou remove anuncio.
- Apenas dono aceita, recusa ou conclui proposta.
- Apenas proponente cancela proposta.
- Apenas participantes acessam chat e avaliacao.
- Admin exige role `admin`.

## Sessoes

O ID da sessao e regenerado apos login. A sessao guarda apenas `user_id`. Cookies usam `HttpOnly` e `SameSite=Lax`.

## Upload

Uploads aceitam apenas JPG, PNG e WEBP ate 2MB. A validacao confere extensao e MIME real via `finfo`. Arquivos sao renomeados com nome aleatorio e salvos em `storage/uploads`.

## Rate limit

Tentativas de login sao registradas em `login_attempts`. Depois de 5 falhas em 15 minutos por e-mail ou IP, o login e bloqueado temporariamente.

## Producao

- Use `APP_DEBUG=false`.
- Nunca versione `.env`.
- Troque a senha do admin inicial.
- Teste permissoes antes de divulgar a URL.
