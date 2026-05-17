<span class="reputation-badge">
    <?= e(label_for('reputation_levels', $level ?? 'beginner')) ?>
    <?php if (isset($average)): ?>
        - <?= e(number_format((float) $average, 1, ',', '.')) ?>/5
    <?php endif; ?>
</span>
