<section class="page-heading">
    <div>
        <h1>Explorar anúncios</h1>
        <p>Encontre livros, materiais, eletrônicos e oportunidades de troca na comunidade acadêmica.</p>
    </div>
    <?php if (auth_check()): ?>
        <a class="btn btn-primary" href="<?= e(url('/anuncios/criar')) ?>">Criar anúncio</a>
    <?php endif; ?>
</section>

<form class="filters" method="get" action="<?= e(url('/anuncios')) ?>" data-auto-submit>
    <input type="search" name="q" placeholder="Buscar por item, descrição ou desejo" value="<?= e($filters['q'] ?? '') ?>">
    <select name="category">
        <option value="">Categoria</option>
        <?php foreach (config('constants.categories') as $key => $label): ?>
            <option value="<?= e($key) ?>" <?= ($filters['category'] ?? '') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
    </select>
    <select name="condition">
        <option value="">Condicao</option>
        <?php foreach (config('constants.conditions') as $key => $label): ?>
            <option value="<?= e($key) ?>" <?= ($filters['condition'] ?? '') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
    </select>
    <select name="sort">
        <option value="recent" <?= ($filters['sort'] ?? '') === 'recent' ? 'selected' : '' ?>>Mais recentes</option>
        <option value="reputation" <?= ($filters['sort'] ?? '') === 'reputation' ? 'selected' : '' ?>>Maior reputação</option>
    </select>
    <button class="btn btn-secondary" type="submit">Filtrar</button>
</form>

<?php if ($anuncios): ?>
    <div class="anuncio-grid">
        <?php foreach ($anuncios as $anuncio): ?>
            <?php partial('anuncio-card', ['anuncio' => $anuncio]) ?>
        <?php endforeach; ?>
    </div>
    <?php partial('pagination', ['page' => $page, 'path' => '/anuncios']) ?>
<?php else: ?>
    <div class="empty-state">
        <h2>Nenhum anúncio encontrado.</h2>
        <p>Ajuste os filtros ou publique o primeiro item dessa categoria.</p>
    </div>
<?php endif; ?>
