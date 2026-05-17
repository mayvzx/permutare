<section class="page-heading">
    <div>
        <h1>Propostas recebidas</h1>
        <p>Analise interessados nos seus anúncios e libere o chat quando aceitar uma proposta.</p>
    </div>
</section>

<?php if ($propostas): ?>
    <div class="proposal-list">
        <?php foreach ($propostas as $proposta): ?>
            <article class="proposal-card">
                <img src="<?= e(upload_url($proposta['anuncio_image'])) ?>" alt="">
                <div>
                    <span class="badge"><?= e(label_for('proposal_statuses', $proposta['status'])) ?></span>
                    <h2><?= e($proposta['anuncio_title']) ?></h2>
                    <p>De: <?= e($proposta['proposer_name']) ?> - <?= e(format_date($proposta['created_at'])) ?></p>
                    <p><?= e(excerpt($proposta['message'], 220)) ?></p>
                    <div class="actions">
                        <a class="btn btn-ghost" href="<?= e(url('/propostas/' . $proposta['id'])) ?>">Ver detalhes</a>
                        <?php if ($proposta['status'] === 'pending'): ?>
                            <form method="post" action="<?= e(url('/propostas/' . $proposta['id'] . '/aceitar')) ?>" class="inline-form" data-confirm="Aceitar esta proposta e recusar as outras pendentes?">
                                <?= csrf_field() ?>
                                <button class="btn btn-primary" type="submit">Aceitar</button>
                            </form>
                            <form method="post" action="<?= e(url('/propostas/' . $proposta['id'] . '/recusar')) ?>" class="inline-form" data-confirm="Recusar esta proposta?">
                                <?= csrf_field() ?>
                                <button class="btn btn-secondary" type="submit">Recusar</button>
                            </form>
                        <?php endif; ?>
                        <?php if (in_array($proposta['status'], ['accepted', 'completed'], true)): ?>
                            <a class="btn btn-primary" href="<?= e(url('/chat/' . $proposta['id'])) ?>">Abrir chat</a>
                        <?php endif; ?>
                        <?php if ($proposta['status'] === 'accepted'): ?>
                            <form method="post" action="<?= e(url('/propostas/' . $proposta['id'] . '/concluir')) ?>" class="inline-form" data-confirm="Marcar a troca como concluída?">
                                <?= csrf_field() ?>
                                <button class="btn btn-success" type="submit">Concluir troca</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($proposta['status'] === 'completed'): ?>
                            <a class="btn btn-secondary" href="<?= e(url('/avaliacoes/' . $proposta['id'] . '/criar')) ?>">Avaliar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <h2>Você ainda não recebeu propostas.</h2>
        <p>Quando alguém se interessar por um anúncio seu, a proposta aparece aqui.</p>
    </div>
<?php endif; ?>
