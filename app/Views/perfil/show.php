<section class="profile-header panel">
    <img src="<?= e(upload_url($user['avatar_path'])) ?>" alt="">
    <div>
        <h1><?= e($user['name']) ?></h1>
        <p><?= e($user['institution'] ?? 'Instituição não informada') ?> - <?= e($user['course'] ?? 'Curso não informado') ?></p>
        <?php if (!empty($user['campus'])): ?>
            <p>Campus <?= e($user['campus']) ?></p>
        <?php endif; ?>
        <?php partial('reputation-badge', ['level' => $user['reputation_level'], 'average' => $user['average_rating']]) ?>
        <span class="muted"><?= e((int) $user['total_reviews']) ?> avaliações</span>
        <?php if (auth_check() && (int) $user['id'] === auth_id()): ?>
            <a class="btn btn-secondary" href="<?= e(url('/perfil/editar')) ?>">Editar perfil</a>
        <?php elseif (auth_check()): ?>
            <a class="text-danger" href="<?= e(url('/denuncias/criar?reported_user_id=' . $user['id'])) ?>">Denunciar usuário</a>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($user['bio'])): ?>
    <section class="section panel">
        <h2>Bio</h2>
        <p><?= nl2br(e($user['bio'])) ?></p>
    </section>
<?php endif; ?>

<section class="section">
    <div class="section-heading">
        <h2>Anúncios ativos</h2>
    </div>
    <?php if ($anuncios): ?>
        <div class="anuncio-grid">
            <?php foreach ($anuncios as $anuncio): ?>
                <?php $anuncio['owner_name'] = $user['name']; $anuncio['owner_institution'] = $user['institution']; $anuncio['owner_reputation_level'] = $user['reputation_level']; $anuncio['owner_average_rating'] = $user['average_rating']; ?>
                <?php partial('anuncio-card', ['anuncio' => $anuncio]) ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state"><h3>Nenhum anúncio ativo.</h3></div>
    <?php endif; ?>
</section>

<section class="section">
    <div class="section-heading">
        <h2>Avaliações recebidas</h2>
    </div>
    <?php if ($avaliacoes): ?>
        <div class="review-list">
            <?php foreach ($avaliacoes as $avaliacao): ?>
                <article class="review-card">
                    <strong><?= e($avaliacao['rating']) ?>/5 por <?= e($avaliacao['reviewer_name']) ?></strong>
                    <?php if (!empty($avaliacao['comment'])): ?>
                        <p><?= e($avaliacao['comment']) ?></p>
                    <?php endif; ?>
                    <span><?= e(format_date($avaliacao['created_at'])) ?></span>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state"><h3>Você ainda não recebeu avaliações.</h3></div>
    <?php endif; ?>
</section>
