<?php $pageTitle = isset($title) ? $title . ' | ' . config('app.name') : config('app.name'); ?>
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
<body class="auth-body">
    <main class="auth-shell">
        <a class="brand auth-brand" href="<?= e(url('/')) ?>">
            <img class="brand-logo" src="<?= e(versioned_asset('img/logo-permutare.svg')) ?>" alt="Permutare">
        </a>
        <?php partial('flash') ?>
        <?= $content ?>
    </main>
    <script src="<?= e(versioned_asset('js/forms.js')) ?>"></script>
</body>
</html>
