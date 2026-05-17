<section class="page-heading">
    <div>
        <h1>Moderação de anúncios</h1>
        <p>Remova anúncios inadequados sem apagar o histórico do sistema.</p>
    </div>
</section>

<form method="get" action="<?= e(url('/admin/anuncios')) ?>" class="filters">
    <input type="search" name="q" value="<?= e($filters['q']) ?>" placeholder="Buscar titulo">
    <select name="status">
        <option value="">Todos os status</option>
        <?php foreach (config('constants.anuncio_statuses') as $key => $label): ?>
            <option value="<?= e($key) ?>" <?= $filters['status'] === $key ? 'selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn btn-secondary" type="submit">Filtrar</button>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Titulo</th>
                <th>Dono</th>
                <th>Status</th>
                <th>Criado em</th>
                <th>Acoes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($anuncios as $anuncio): ?>
                <tr>
                    <td><a href="<?= e(url('/anuncios/' . $anuncio['id'])) ?>"><?= e($anuncio['title']) ?></a></td>
                    <td><?= e($anuncio['owner_name']) ?></td>
                    <td><span class="badge"><?= e(label_for('anuncio_statuses', $anuncio['status'])) ?></span></td>
                    <td><?= e(format_date($anuncio['created_at'])) ?></td>
                    <td>
                        <?php if ($anuncio['status'] !== 'removed'): ?>
                            <form method="post" action="<?= e(url('/admin/anuncios/' . $anuncio['id'] . '/remover')) ?>" class="inline-form" data-confirm="Remover este anúncio?">
                                <?= csrf_field() ?>
                                <button class="btn btn-danger" type="submit">Remover</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
