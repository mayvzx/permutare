<?php $pageTitle = isset($title) ? $title . ' | Admin Permutare' : 'Admin Permutare'; ?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= e(versioned_asset('css/reset.css')) ?>">
    <link rel="stylesheet" href="<?= e(versioned_asset('css/variables.css')) ?>">
    <link rel="stylesheet" href="<?= e(versioned_asset('css/components.css')) ?>">
    <link rel="stylesheet" href="<?= e(versioned_asset('css/layout.css')) ?>">
    <link rel="stylesheet" href="<?= e(versioned_asset('css/pages.css')) ?>">
</head>
<body>
    <?php partial('header') ?>
    <main class="main admin-main">
        <div class="container admin-layout">
            <aside class="admin-sidebar">
                <a href="<?= e(url('/admin')) ?>">Dashboard</a>
                <a href="<?= e(url('/admin/usuarios')) ?>">Usuários</a>
                <a href="<?= e(url('/admin/anuncios')) ?>">Anúncios</a>
                <a href="<?= e(url('/admin/denuncias')) ?>">Denúncias</a>
            </aside>
            <section class="admin-content">
                <?php partial('flash') ?>
                <?= $content ?>
            </section>
        </div>
    </main>
    <script src="<?= e(versioned_asset('js/main.js')) ?>"></script>
</body>
</html>
