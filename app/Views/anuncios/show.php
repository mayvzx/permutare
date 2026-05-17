<section class="detail-layout">
    <div class="detail-media">
        <img src="<?= e(upload_url($anuncio['image_path'])) ?>" alt="<?= e($anuncio['title']) ?>">
    </div>
    <div class="detail-content">
        <div class="badges">
            <span class="badge"><?= e(label_for('categories', $anuncio['category'])) ?></span>
            <span class="badge badge-muted"><?= e(label_for('conditions', $anuncio['item_condition'])) ?></span>
            <span class="badge badge-status"><?= e(label_for('anuncio_statuses', $anuncio['status'])) ?></span>
        </div>
        <h1><?= e($anuncio['title']) ?></h1>
        <p class="lead"><?= nl2br(e($anuncio['description'])) ?></p>
        <div class="wanted-box">
            <span>Deseja receber</span>
            <strong><?= e($anuncio['desired_item']) ?></strong>
        </div>
        <?php partial('safety-alert') ?>

        <section class="owner-summary">
            <img src="<?= e(upload_url($anuncio['owner_avatar'] ?? null)) ?>" alt="">
            <div>
                <strong><?= e($anuncio['owner_name']) ?></strong>
                <span><?= e($anuncio['owner_institution'] ?? 'Instituição não informada') ?></span>
                <?php partial('reputation-badge', [
                    'level' => $anuncio['owner_reputation_level'],
                    'average' => $anuncio['owner_average_rating'],
                ]) ?>
                <a href="<?= e(url('/perfil/' . $anuncio['user_id'])) ?>">Ver perfil</a>
            </div>
        </section>

        <?php if (!auth_check()): ?>
            <a class="btn btn-primary btn-full" href="<?= e(url('/login')) ?>">Entrar para enviar proposta</a>
        <?php elseif ((int) $anuncio['user_id'] === auth_id()): ?>
            <a class="btn btn-secondary btn-full" href="<?= e(url('/anuncios/' . $anuncio['id'] . '/editar')) ?>">Editar meu anúncio</a>
        <?php elseif ($anuncio['status'] === 'active'): ?>
            <form method="post" action="<?= e(url('/anuncios/' . $anuncio['id'] . '/propostas')) ?>" class="form proposal-form">
                <?= csrf_field() ?>
                <label>
                    Mensagem da proposta
                    <textarea name="message" rows="4" maxlength="1000" required><?= e(old('message')) ?></textarea>
                </label>
                <button class="btn btn-primary btn-full" type="submit">Enviar proposta</button>
            </form>
        <?php else: ?>
            <div class="alert alert-warning">Este anúncio não está recebendo novas propostas.</div>
        <?php endif; ?>

        <?php if (auth_check() && (int) $anuncio['user_id'] !== auth_id()): ?>
            <a class="text-danger" href="<?= e(url('/denuncias/criar?anuncio_id=' . $anuncio['id'] . '&reported_user_id=' . $anuncio['user_id'])) ?>">Denunciar anúncio</a>
        <?php endif; ?>
    </div>
</section>
