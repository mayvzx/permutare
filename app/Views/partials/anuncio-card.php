<article class="anuncio-card">
    <a class="anuncio-card-image" href="<?= e(url('/anuncios/' . $anuncio['id'])) ?>">
        <img src="<?= e(upload_url($anuncio['image_path'] ?? null)) ?>" alt="<?= e($anuncio['title']) ?>">
    </a>
    <div class="anuncio-card-body">
        <div class="badges">
            <span class="badge"><?= e(label_for('categories', $anuncio['category'])) ?></span>
            <span class="badge badge-muted"><?= e(label_for('conditions', $anuncio['item_condition'])) ?></span>
        </div>
        <h3><a href="<?= e(url('/anuncios/' . $anuncio['id'])) ?>"><?= e($anuncio['title']) ?></a></h3>
        <p>Busca: <?= e($anuncio['desired_item']) ?></p>
        <div class="card-meta">
            <span><?= e($anuncio['owner_name'] ?? 'Usuário') ?></span>
            <span><?= e($anuncio['owner_institution'] ?? 'Instituição') ?></span>
        </div>
        <?php partial('reputation-badge', [
            'level' => $anuncio['owner_reputation_level'] ?? 'beginner',
            'average' => $anuncio['owner_average_rating'] ?? 0,
        ]) ?>
        <a class="btn btn-secondary btn-full" href="<?= e(url('/anuncios/' . $anuncio['id'])) ?>">Ver detalhes</a>
    </div>
</article>
