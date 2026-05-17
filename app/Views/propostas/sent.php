<section class="page-heading">
    <div>
        <h1>Propostas enviadas</h1>
        <p>Acompanhe as trocas que você iniciou.</p>
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
                    <p>Para: <?= e($proposta['owner_name']) ?> - <?= e(format_date($proposta['created_at'])) ?></p>
                    <p><?= e(excerpt($proposta['message'], 180)) ?></p>
                    <div class="actions">
                        <a class="btn btn-ghost" href="<?= e(url('/propostas/' . $proposta['id'])) ?>">Ver detalhes</a>
                        <?php if ($proposta['status'] === 'pending'): ?>
                            <form method="post" action="<?= e(url('/propostas/' . $proposta['id'] . '/cancelar')) ?>" class="inline-form" data-confirm="Cancelar esta proposta?">
                                <?= csrf_field() ?>
                                <button class="btn btn-secondary" type="submit">Cancelar</button>
                            </form>
                        <?php endif; ?>
                        <?php if (in_array($proposta['status'], ['accepted', 'completed'], true)): ?>
                            <a class="btn btn-primary" href="<?= e(url('/chat/' . $proposta['id'])) ?>">Abrir chat</a>
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
        <h2>Você ainda não enviou propostas.</h2>
        <p>Explore anúncios e encontre uma troca que faça sentido para o seu semestre.</p>
        <a class="btn btn-primary" href="<?= e(url('/anuncios')) ?>">Explorar anúncios</a>
    </div>
<?php endif; ?>
