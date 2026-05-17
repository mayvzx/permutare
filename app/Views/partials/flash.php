<?php $flash = get_flash(); $errors = get_errors(); ?>
<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?>">
        <?= e($flash['message']) ?>
    </div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="alert alert-error">
        <strong>Confira estes pontos:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
