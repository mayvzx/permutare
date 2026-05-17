<div class="pagination">
    <?php if (($page ?? 1) > 1): ?>
        <a class="btn btn-ghost" href="<?= e(url($path . '?page=' . (($page ?? 1) - 1))) ?>">Anterior</a>
    <?php endif; ?>
    <span>Pagina <?= e($page ?? 1) ?></span>
    <a class="btn btn-ghost" href="<?= e(url($path . '?page=' . (($page ?? 1) + 1))) ?>">Proxima</a>
</div>
