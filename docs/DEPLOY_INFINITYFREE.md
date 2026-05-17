# Deploy no InfinityFree

## Ideia geral

O InfinityFree e hospedagem compartilhada. A v1.0 foi pensada para funcionar sem Composer obrigatorio, sem terminal no servidor e sem workers.

## Passos

1. Crie a conta no InfinityFree e um site.
2. Crie um banco MySQL no painel.
3. Abra o phpMyAdmin do InfinityFree, selecione o banco criado e importe `database/schema.sql`.
4. Importe `database/seed.sql` se quiser criar o admin inicial.
5. Envie os arquivos do projeto por FTP ou Gerenciador de Arquivos.
6. Crie um `.env` no servidor com as credenciais reais:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.infinityfreeapp.com
DB_HOST=sqlXXX.infinityfree.com
DB_NAME=if0_XXXXXXX_permutare
DB_USER=if0_XXXXXXX
DB_PASS=SUA_SENHA
DB_CHARSET=utf8mb4
```

7. Garanta que `.env` nao foi enviado ao GitHub.
8. Acesse a URL publica e teste login, cadastro, anuncio e proposta.

## Observacoes

- Se a hospedagem apontar para a raiz do projeto, o `.htaccess` redireciona para `public/`.
- Se a hospedagem permitir escolher a pasta publica, aponte diretamente para `public/`.
- Uploads ficam em `storage/uploads` e sao servidos por rota PHP controlada.
- O envio real de e-mail nao esta implementado na v1.0; o token existe e pode ser integrado depois.
