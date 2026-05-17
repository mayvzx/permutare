<section class="page-heading">
    <div>
        <h1>Usuários</h1>
        <p>Modere status e permissões de acesso.</p>
    </div>
</section>

<form method="get" action="<?= e(url('/admin/usuarios')) ?>" class="filters">
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Buscar nome ou e-mail">
    <button class="btn btn-secondary" type="submit">Buscar</button>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Role</th>
                <th>Status</th>
                <th>Acoes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= e($user['name']) ?></td>
                    <td><?= e($user['email']) ?></td>
                    <td><span class="badge"><?= e($user['role']) ?></span></td>
                    <td><span class="badge"><?= e($user['status']) ?></span></td>
                    <td class="actions-cell">
                        <?php if ($user['status'] === 'blocked'): ?>
                            <form method="post" action="<?= e(url('/admin/usuarios/' . $user['id'] . '/desbloquear')) ?>" class="inline-form">
                                <?= csrf_field() ?>
                                <button class="btn btn-secondary" type="submit">Desbloquear</button>
                            </form>
                        <?php else: ?>
                            <form method="post" action="<?= e(url('/admin/usuarios/' . $user['id'] . '/bloquear')) ?>" class="inline-form" data-confirm="Bloquear este usuário?">
                                <?= csrf_field() ?>
                                <button class="btn btn-danger" type="submit">Bloquear</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($user['role'] === 'admin'): ?>
                            <form method="post" action="<?= e(url('/admin/usuarios/' . $user['id'] . '/rebaixar')) ?>" class="inline-form" data-confirm="Rebaixar para user?">
                                <?= csrf_field() ?>
                                <button class="btn btn-ghost" type="submit">Rebaixar</button>
                            </form>
                        <?php else: ?>
                            <form method="post" action="<?= e(url('/admin/usuarios/' . $user['id'] . '/promover')) ?>" class="inline-form" data-confirm="Promover para admin?">
                                <?= csrf_field() ?>
                                <button class="btn btn-ghost" type="submit">Promover</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
