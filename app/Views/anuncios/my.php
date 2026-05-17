<section class="page-heading">
    <div>
        <h1>Meus anúncios</h1>
        <p>Gerencie itens publicados, pause quando estiver negociando e remova o que não deve aparecer.</p>
    </div>
    <a class="btn btn-primary" href="<?= e(url('/anuncios/criar')) ?>">Novo anúncio</a>
</section>

<?php if ($anuncios): ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Status</th>
                    <th>Criado em</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($anuncios as $anuncio): ?>
                    <tr>
                        <td><a href="<?= e(url('/anuncios/' . $anuncio['id'])) ?>"><?= e($anuncio['title']) ?></a></td>
                        <td><span class="badge"><?= e(label_for('anuncio_statuses', $anuncio['status'])) ?></span></td>
                        <td><?= e(format_date($anuncio['created_at'])) ?></td>
                        <td class="actions-cell">
                            <?php if (!in_array($anuncio['status'], ['completed', 'removed'], true)): ?>
                                <a class="btn btn-ghost" href="<?= e(url('/anuncios/' . $anuncio['id'] . '/editar')) ?>">Editar</a>
                                <form method="post" action="<?= e(url('/anuncios/' . $anuncio['id'] . '/pausar')) ?>" class="inline-form">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-secondary" type="submit"><?= $anuncio['status'] === 'paused' ? 'Reativar' : 'Pausar' ?></button>
                                </form>
                                <form method="post" action="<?= e(url('/anuncios/' . $anuncio['id'] . '/remover')) ?>" class="inline-form" data-confirm="Remover este anúncio?">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-danger" type="submit">Remover</button>
                                </form>
                            <?php else: ?>
                                <span>-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="empty-state">
        <h2>Você ainda não publicou anúncios.</h2>
        <p>Crie seu primeiro anúncio para receber propostas de troca.</p>
        <a class="btn btn-primary" href="<?= e(url('/anuncios/criar')) ?>">Criar anúncio</a>
    </div>
<?php endif; ?>
