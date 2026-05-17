<section class="panel">
    <div class="proposal-detail-header">
        <img src="<?= e(upload_url($proposta['anuncio_image'])) ?>" alt="">
        <div>
            <span class="badge"><?= e(label_for('proposal_statuses', $proposta['status'])) ?></span>
            <h1><?= e($proposta['anuncio_title']) ?></h1>
            <p>Dono: <?= e($proposta['owner_name']) ?> - Proponente: <?= e($proposta['proposer_name']) ?></p>
        </div>
    </div>
    <h2>Mensagem inicial</h2>
    <p><?= nl2br(e($proposta['message'])) ?></p>
    <div class="actions">
        <a class="btn btn-ghost" href="<?= e(url('/anuncios/' . $proposta['anuncio_id'])) ?>">Ver anúncio</a>
        <?php if (in_array($proposta['status'], ['accepted', 'completed'], true)): ?>
            <a class="btn btn-primary" href="<?= e(url('/chat/' . $proposta['id'])) ?>">Abrir chat</a>
        <?php endif; ?>
        <?php if ($proposta['status'] === 'completed'): ?>
            <a class="btn btn-secondary" href="<?= e(url('/avaliacoes/' . $proposta['id'] . '/criar')) ?>">Avaliar participante</a>
        <?php endif; ?>
    </div>
</section>
