# Identidade Visual do Permutare

## Conceito da marca

O Permutare e um marketplace universitario de trocas. A identidade visual deve comunicar comunidade, confianca, economia, reaproveitamento e vida academica, sem parecer infantil, gamer ou corporativa demais.

A marca deve parecer:

- Moderna
- Leve
- Academica
- Confiavel
- Acolhedora
- Simples de usar
- Segura para trocas entre pessoas reais

## Prompt para gerar a logo

```text
Crie uma logo vetorial moderna para uma plataforma chamada "Permutare", um marketplace universitario de permutas entre estudantes, professores e funcionarios. A logo deve transmitir troca, circularidade, comunidade academica, sustentabilidade, economia e confianca.

Use um simbolo simples, memoravel e escalavel, combinando duas setas suaves em movimento circular ou cruzado para representar permuta, com uma referencia sutil ao ambiente universitario, como paginas de livro, marcador academico ou conexao entre pessoas, sem ficar literal demais.

Estilo visual: minimalista, profissional, amigavel, contemporaneo, com formas geometricas arredondadas e boa legibilidade em tamanhos pequenos. Evite excesso de detalhes, mascotes, efeitos 3D, sombras pesadas ou aparencia infantil.

Paleta: verde teal profundo como cor principal (#0F766E), azul petroleo como apoio (#164E63), amarelo academico/sustentavel como acento (#EAB308), fundo claro off-white (#F7F8F5) e branco.

Tipografia sugerida para o wordmark: sans-serif moderna, limpa e levemente arredondada, como Manrope, Sora, Inter ou Poppins. O nome "Permutare" deve ter peso semibold ou bold, com letras bem espacadas visualmente, sem tracking exagerado.

Composicao: simbolo a esquerda e wordmark a direita, alem de uma versao somente simbolo para favicon/app icon. A logo deve funcionar em fundo claro e escuro, em versao colorida e monocromatica.

Resultado desejado: logo vetorial limpa, sofisticada e confiavel para um produto universitario real, com sensacao de movimento, troca justa e comunidade segura.
```

## Ideia do simbolo

O simbolo recomendado para o Permutare combina:

- Duas setas em fluxo circular, representando permuta.
- Espaco negativo central, sugerindo encontro e acordo.
- Cantos arredondados, para transmitir acessibilidade.
- Um pequeno acento amarelo, para dar energia e destaque academico.
- Formato compacto, para funcionar como favicon e avatar social.

O simbolo nao deve depender de texto para ser reconhecivel. Ele precisa continuar claro em tamanhos pequenos, como 32px.

## Paleta

### Cores principais

| Papel | Cor | Hex | Uso |
|---|---:|---:|---|
| Primaria | Teal Permutare | `#0F766E` | CTAs, links importantes, logo, estados ativos |
| Primaria escura | Teal profundo | `#115E59` | Hover, header destacado, fundos institucionais |
| Apoio | Azul petroleo | `#164E63` | Footer, textos de confianca, areas admin |
| Acento | Amarelo troca | `#EAB308` | Destaques, badges especiais, detalhes da logo |
| Fundo | Off-white | `#F7F8F5` | Fundo geral do site |
| Superficie | Branco | `#FFFFFF` | Cards, formularios, tabelas |

### Cores semanticas

| Papel | Hex |
|---|---:|
| Sucesso | `#16A34A` |
| Alerta | `#D97706` |
| Perigo | `#DC2626` |
| Texto | `#172121` |
| Texto secundario | `#64748B` |
| Borda | `#DDE3DF` |

## Tipografia

### Recomendacao principal

- Interface: `Inter`, `system-ui`, `Segoe UI`, sans-serif.
- Titulos opcionais: `Manrope` ou `Sora`.

### Direcao tipografica

- Titulos devem ser claros e confiantes, sem parecer propaganda exagerada.
- Textos de interface devem ser curtos e objetivos.
- Evitar letras muito finas, condensadas ou decorativas.
- Usar peso `700` para acoes e navegacao.
- Usar peso `800` ou `900` apenas em hero e numeros importantes.

## Estilo de UI

### Sensacao geral

O site deve ser uma ferramenta de marketplace, nao uma landing page decorativa. A interface precisa ser facil de escanear, com informacao bem organizada e acoes evidentes.

### Componentes

- Cards com raio de 8px.
- Bordas suaves em `#DDE3DF`.
- Sombras leves, apenas para separar superficies.
- Badges arredondadas para categoria, condicao e status.
- Botoes com labels diretos.
- Formulario limpo, com campos largos e mensagens de erro proximas do contexto.
- Tabelas simples e legiveis para admin.

### Imagens

- Anuncios devem priorizar imagem real do item.
- Usar proporcao consistente `4:3`.
- Evitar imagens escuras ou excessivamente cortadas.
- Avatar deve ser circular e simples.

## Layout

### Home

A home deve apresentar o produto com clareza:

- Hero com nome/proposta do produto.
- CTA para explorar anuncios.
- CTA para criar conta.
- Como funciona em 3 ou 4 passos.
- Categorias principais.
- Anuncios recentes.
- Bloco de seguranca e reputacao.

### Feed

O feed deve parecer utilitario e confiavel:

- Busca em destaque.
- Filtros visiveis.
- Cards consistentes.
- Estado vazio claro.
- Reputacao resumida no card.

### Detalhe do anuncio

Foco em decisao segura:

- Imagem grande.
- Titulo e descricao.
- Item desejado em troca.
- Reputacao do anunciante.
- CTA para proposta.
- Aviso de seguranca.

### Admin

O admin deve ser mais denso e funcional:

- Tabelas limpas.
- Filtros simples.
- Acoes destrutivas em vermelho.
- Sem visual decorativo.

## Tom visual

O Permutare deve ficar entre o academico e o comunitario:

- Academico, mas nao rigido.
- Jovem, mas nao informal demais.
- Sustentavel, mas nao artesanal.
- Seguro, mas nao burocratico.

## Exemplos de microcopy

- "Troque livros, eletronicos e materiais com pessoas da sua universidade."
- "Economize no semestre dando um novo destino ao que voce nao usa mais."
- "Combine trocas em locais movimentados dentro da instituicao."
- "Sua reputacao ajuda outras pessoas a confiarem em voce."
- "Envie uma proposta clara e respeitosa."

## Tokens CSS recomendados

```css
:root {
  --color-bg: #F7F8F5;
  --color-surface: #FFFFFF;
  --color-surface-muted: #F0F3EF;

  --color-primary: #0F766E;
  --color-primary-dark: #115E59;
  --color-primary-light: #CCFBF1;

  --color-secondary: #164E63;
  --color-secondary-light: #E0F2FE;
  --color-accent: #EAB308;
  --color-accent-light: #FEF3C7;

  --color-text: #172121;
  --color-text-muted: #64748B;
  --color-border: #DDE3DF;

  --color-danger: #DC2626;
  --color-warning: #D97706;
  --color-success: #16A34A;

  --radius-sm: 6px;
  --radius-md: 8px;
  --radius-lg: 8px;

  --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.08);
  --shadow-md: 0 8px 24px rgba(15, 23, 42, 0.10);
}
```

## Checklist de consistencia visual

- [ ] O logo funciona em 32px.
- [ ] O contraste dos botoes principais e suficiente.
- [ ] Cards de anuncio mantem a mesma proporcao de imagem.
- [ ] A cor amarela aparece apenas como acento.
- [ ] Telas admin sao funcionais e legiveis.
- [ ] Textos de seguranca aparecem no detalhe do anuncio e chat.
- [ ] Mobile tem prioridade real no layout.
- [ ] A interface nao depende de uma unica cor para comunicar status.
