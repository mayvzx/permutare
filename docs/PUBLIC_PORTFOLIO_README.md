# Permutare

**Permutare** é um marketplace universitário de permutas pensado para estudantes, professores e funcionários trocarem livros, eletrônicos, materiais e serviços acadêmicos com mais organização, segurança e confiança.

O projeto foi desenvolvido como um produto web completo, com foco em experiência de marketplace, identidade visual própria, arquitetura MVC simples em PHP e regras de segurança aplicadas ao fluxo principal.

![Preview da home do Permutare](assets/permutare-home-preview.png)

## Conceito

A proposta do Permutare é resolver um problema comum em ambientes acadêmicos: muitos itens úteis ficam parados depois de disciplinas, semestres ou projetos, enquanto outras pessoas do mesmo campus precisam exatamente desses materiais.

O sistema cria um ambiente próprio para essas trocas, reduzindo a dependência de grupos informais e evitando exposição pública de dados pessoais.

## Proposta de valor

> Troque o que você não usa mais por algo que precisa no semestre, com segurança, reputação e comunidade.

## Funcionalidades

- Cadastro e autenticação de usuários.
- Perfil público com dados acadêmicos e reputação.
- Publicação de anúncios de troca.
- Feed com busca, categorias e filtros.
- Propostas de permuta entre usuários.
- Aceite, recusa e cancelamento de propostas.
- Chat interno liberado após proposta aceita.
- Finalização de troca.
- Avaliação mútua entre participantes.
- Reputação agregada por usuário.
- Denúncias de usuários, anúncios ou conversas.
- Painel administrativo para moderação.
- Interface responsiva para desktop e mobile.

## Identidade visual

A interface foi redesenhada para fugir de um visual genérico de e-commerce. A direção adotada foi um **quadro de trocas universitário**, combinando:

- paleta em verde profundo, papel, dourado e coral;
- cartões inspirados em avisos de campus;
- textura sutil de grade/papel;
- símbolo com circularidade, livro aberto e comunidade;
- microinterações e animações suaves;
- componentes consistentes para cards, botões, badges, formulários e navegação.

## Tecnologias utilizadas

- PHP 8+
- MySQL/MariaDB
- PDO
- HTML5
- CSS3
- JavaScript vanilla
- Apache/XAMPP
- Git/GitHub

## Arquitetura

O projeto foi estruturado com uma arquitetura MVC simples em PHP puro, separando responsabilidades entre:

- controllers;
- models;
- services;
- views;
- helpers;
- rotas;
- arquivos públicos;
- configurações;
- scripts SQL.

Essa abordagem mantém o projeto compreensível, evolutivo e adequado para hospedagem compartilhada.

## Segurança

O Permutare foi planejado considerando práticas essenciais de segurança web:

- senhas com hash;
- prepared statements com PDO;
- proteção contra CSRF;
- escape de saída para reduzir XSS;
- validação de permissões para evitar IDOR;
- upload validado;
- sessões com regeneração após login;
- controle básico de tentativas de login;
- separação entre arquivos públicos e privados.

## Status do projeto

Projeto em desenvolvimento como produto autoral e estudo prático de desenvolvimento full stack, UX de marketplace, segurança web e arquitetura PHP sem framework pesado.

O código-fonte completo é mantido em repositório privado.

## Autor

Desenvolvido por **mayvzx**.
