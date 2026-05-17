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
<body>
    <?php partial('header') ?>
    <main class="main">
        <div class="container">
            <?php partial('flash') ?>
            <?= $content ?>
        </div>
    </main>
    <?php partial('footer') ?>
    <script src="<?= e(versioned_asset('js/main.js')) ?>"></script>
    <script src="<?= e(versioned_asset('js/forms.js')) ?>"></script>
    <script src="<?= e(versioned_asset('js/filters.js')) ?>"></script>
    <script src="<?= e(versioned_asset('js/chat.js')) ?>"></script>
</body>
</html>
