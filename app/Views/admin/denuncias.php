<section class="page-heading">
    <div>
        <h1>Denúncias</h1>
        <p>Analise relatos, registre notas e tome medidas proporcionais.</p>
    </div>
</section>

<form method="get" action="<?= e(url('/admin/denuncias')) ?>" class="filters">
    <select name="status">
        <option value="">Todos os status</option>
        <?php foreach (['pending' => 'Pendente', 'reviewing' => 'Em análise', 'resolved' => 'Resolvida', 'archived' => 'Arquivada'] as $key => $label): ?>
            <option value="<?= e($key) ?>" <?= $status === $key ? 'selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn btn-secondary" type="submit">Filtrar</button>
</form>

<?php if ($denuncias): ?>
    <div class="report-list">
        <?php foreach ($denuncias as $denuncia): ?>
            <article class="panel report-card">
                <div class="report-card-head">
                    <div>
                        <span class="badge"><?= e(label_for('report_statuses', $denuncia['status'])) ?></span>
                        <h2><?= e(label_for('report_reasons', $denuncia['reason'])) ?></h2>
                        <p>Denunciante: <?= e($denuncia['reporter_name']) ?></p>
                        <?php if (!empty($denuncia['reported_user_name'])): ?>
                            <p>Usuário denunciado: <?= e($denuncia['reported_user_name']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($denuncia['anuncio_title'])): ?>
                            <p>Anúncio: <?= e($denuncia['anuncio_title']) ?></p>
                        <?php endif; ?>
                    </div>
                    <span><?= e(format_date($denuncia['created_at'])) ?></span>
                </div>
                <?php if (!empty($denuncia['description'])): ?>
                    <p><?= nl2br(e($denuncia['description'])) ?></p>
                <?php endif; ?>
                <form method="post" class="form report-actions">
                    <?= csrf_field() ?>
                    <label>
                        Notas administrativas
                        <textarea name="admin_notes" rows="3"><?= e($denuncia['admin_notes'] ?? '') ?></textarea>
                    </label>
                    <?php if (!empty($denuncia['reported_user_id'])): ?>
                        <label class="checkbox-line">
                            <input type="checkbox" name="block_user_id" value="<?= e($denuncia['reported_user_id']) ?>">
                            Bloquear usuário denunciado ao resolver
                        </label>
                    <?php endif; ?>
                    <div class="actions">
                        <button class="btn btn-secondary" formaction="<?= e(url('/admin/denuncias/' . $denuncia['id'] . '/analise')) ?>" type="submit">Em análise</button>
                        <button class="btn btn-primary" formaction="<?= e(url('/admin/denuncias/' . $denuncia['id'] . '/resolver')) ?>" type="submit">Resolver</button>
                        <button class="btn btn-ghost" formaction="<?= e(url('/admin/denuncias/' . $denuncia['id'] . '/arquivar')) ?>" type="submit">Arquivar</button>
                    </div>
                </form>
            </article>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <h2>Nenhuma denúncia pendente.</h2>
    </div>
<?php endif; ?>
