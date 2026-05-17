<section class="page-heading">
    <div>
        <h1>Dashboard admin</h1>
        <p>Visão rápida da atividade e moderação do Permutare.</p>
    </div>
</section>

<div class="stats-grid">
    <div class="stat-card"><span>Usuários</span><strong><?= e($stats['users']) ?></strong></div>
    <div class="stat-card"><span>Anúncios ativos</span><strong><?= e($stats['active_anuncios']) ?></strong></div>
    <div class="stat-card"><span>Propostas</span><strong><?= e($stats['propostas']) ?></strong></div>
    <div class="stat-card"><span>Denúncias pendentes</span><strong><?= e($stats['pending_denuncias']) ?></strong></div>
</div>

<div class="admin-columns">
    <section class="panel">
        <h2>Últimos usuários</h2>
        <?php foreach ($latestUsers as $user): ?>
            <p><?= e($user['name']) ?> <span class="muted"><?= e($user['email']) ?></span></p>
        <?php endforeach; ?>
    </section>
    <section class="panel">
        <h2>Últimos anúncios</h2>
        <?php foreach ($latestAnuncios as $anuncio): ?>
            <p><?= e($anuncio['title']) ?> <span class="badge"><?= e(label_for('anuncio_statuses', $anuncio['status'])) ?></span></p>
        <?php endforeach; ?>
    </section>
    <section class="panel">
        <h2>Últimas denúncias</h2>
        <?php foreach ($latestDenuncias as $denuncia): ?>
            <p><?= e(label_for('report_reasons', $denuncia['reason'])) ?> <span class="badge"><?= e(label_for('report_statuses', $denuncia['status'])) ?></span></p>
        <?php endforeach; ?>
    </section>
</div>
