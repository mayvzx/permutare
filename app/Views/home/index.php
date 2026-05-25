<section class="hero campus-hero" data-animate>
    <div class="hero-copy">
        <p class="eyebrow">Trocas dentro da comunidade acadêmica</p>
        <h1>O quadro de trocas da sua universidade.</h1>
        <p>Encontre livros, eletrônicos, materiais e serviços acadêmicos com pessoas do seu campus. Combine propostas pelo chat interno, consulte reputação e mantenha seus dados pessoais fora do feed público.</p>
        <div class="actions">
            <a class="btn btn-primary" href="<?= e(url('/anuncios')) ?>">Explorar anúncios</a>
            <?php if (!auth_check()): ?>
                <a class="btn btn-secondary" href="<?= e(url('/cadastro')) ?>">Criar conta</a>
            <?php endif; ?>
        </div>
        <div class="hero-proof" aria-label="Diferenciais do Permutare">
            <span>Chat após aceite</span>
            <span>Reputação visível</span>
            <span>Moderação da comunidade</span>
        </div>
    </div>

    <div class="exchange-board" aria-label="Exemplos de trocas no Permutare">
        <article class="board-card board-card-featured">
            <span class="board-tag">Livro</span>
            <strong>Cálculo I</strong>
            <p>Troco por calculadora científica ou materiais de desenho.</p>
            <small>Campus Centro</small>
        </article>
        <article class="board-card board-card-offset">
            <span class="board-tag board-tag-warm">Eletrônico</span>
            <strong>Fone Bluetooth</strong>
            <p>Busco mochila em bom estado.</p>
            <small>Reputação prata</small>
        </article>
        <article class="board-card">
            <span class="board-tag board-tag-blue">Serviço</span>
            <strong>Revisão ABNT</strong>
            <p>Troco por apostilas de estatística.</p>
            <small>Chat liberado após aceite</small>
        </article>
    </div>
</section>

<section class="campus-strip" data-animate>
    <span>Troque com pessoas do seu campus</span>
    <span>Combine em locais movimentados</span>
    <span>Construa reputação a cada permuta</span>
</section>

<section class="section flow-section" data-animate>
    <div class="section-heading">
        <div>
            <p class="section-kicker">Fluxo simples</p>
            <h2>Da publicação à avaliação</h2>
        </div>
    </div>
    <div class="steps-grid">
        <div class="info-card" data-animate><strong>Publique</strong><p>Cadastre um item parado e diga o que aceita em troca.</p></div>
        <div class="info-card" data-animate><strong>Receba propostas</strong><p>Compare interessados e aceite a melhor oportunidade.</p></div>
        <div class="info-card" data-animate><strong>Combine no chat</strong><p>Converse apenas depois do aceite, com aviso de segurança.</p></div>
        <div class="info-card" data-animate><strong>Avalie</strong><p>Finalize a troca e ajude a comunidade a confiar mais.</p></div>
    </div>
</section>

<section class="section categories-section" data-animate>
    <div class="section-heading">
        <div>
            <p class="section-kicker">Categorias</p>
            <h2>O que está circulando</h2>
        </div>
        <a href="<?= e(url('/anuncios')) ?>">Ver todas</a>
    </div>
    <div class="category-list">
        <?php foreach ($categories as $key => $label): ?>
            <a href="<?= e(url('/anuncios?category=' . $key)) ?>"><?= e($label) ?></a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section recent-section" data-animate>
    <div class="section-heading">
        <div>
            <p class="section-kicker">Feed</p>
            <h2>Anúncios recentes</h2>
        </div>
        <a href="<?= e(url('/anuncios')) ?>">Explorar feed</a>
    </div>
    <?php if ($recentAnuncios): ?>
        <div class="anuncio-grid">
            <?php foreach ($recentAnuncios as $anuncio): ?>
                <?php partial('anuncio-card', ['anuncio' => $anuncio]) ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h3>Nenhum anúncio publicado ainda.</h3>
            <p>Quando os primeiros itens chegarem, eles aparecem aqui.</p>
        </div>
    <?php endif; ?>
</section>

<section class="trust-band" data-animate>
    <div>
        <p class="section-kicker">Confiança</p>
        <h2>Segurança e reputação sem expor seus dados.</h2>
        <p>O Permutare evita exibir e-mail ou telefone publicamente, registra avaliações e permite denúncias para moderação.</p>
    </div>
    <a class="btn btn-primary" href="<?= e(url('/cadastro')) ?>">Participar</a>
</section>
