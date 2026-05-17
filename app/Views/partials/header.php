<?php
$unreadMessages = auth_check() ? (new \App\Models\Mensagem())->countUnread(auth_id()) : 0;
?>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?= e(url('/')) ?>">
            <img class="brand-logo" src="<?= e(versioned_asset('img/logo-permutare.svg')) ?>" alt="Permutare">
        </a>
        <button class="nav-toggle" type="button" data-nav-toggle aria-label="Abrir menu">&#9776;</button>
        <nav class="nav" data-nav>
            <a href="<?= e(url('/anuncios')) ?>">Explorar</a>
            <?php if (auth_check()): ?>
                <a href="<?= e(url('/meus-anuncios')) ?>">Meus anúncios</a>
                <a href="<?= e(url('/propostas/enviadas')) ?>">Enviadas</a>
                <a href="<?= e(url('/propostas/recebidas')) ?>">Recebidas</a>
                <a href="<?= e(url('/perfil/' . auth_id())) ?>">Perfil</a>
                <?php if ($unreadMessages > 0): ?>
                    <a href="<?= e(url('/propostas/recebidas')) ?>">Mensagens <span class="badge badge-accent"><?= e($unreadMessages) ?></span></a>
                <?php endif; ?>
                <?php if (is_admin()): ?>
                    <a href="<?= e(url('/admin')) ?>">Admin</a>
                <?php endif; ?>
                <form method="post" action="<?= e(url('/logout')) ?>" class="inline-form">
                    <?= csrf_field() ?>
                    <button class="btn btn-ghost" type="submit">Sair</button>
                </form>
            <?php else: ?>
                <a href="<?= e(url('/login')) ?>">Entrar</a>
                <a class="btn btn-primary" href="<?= e(url('/cadastro')) ?>">Criar conta</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
