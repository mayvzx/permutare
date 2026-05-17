<section class="panel narrow-panel">
    <h1>Avaliar troca</h1>
    <p>Sua avaliação ajuda outras pessoas a confiarem em <?= e($reviewedName) ?>.</p>
    <form method="post" action="<?= e(url('/avaliacoes/' . $proposta['id'])) ?>" class="form">
        <?= csrf_field() ?>
        <label>
            Nota
            <select name="rating" required>
                <option value="">Selecione</option>
                <?php for ($rating = 5; $rating >= 1; $rating--): ?>
                    <option value="<?= e($rating) ?>" <?= (string) old('rating') === (string) $rating ? 'selected' : '' ?>><?= e($rating) ?>/5</option>
                <?php endfor; ?>
            </select>
        </label>
        <label>
            Comentário
            <textarea name="comment" rows="5" maxlength="1000"><?= e(old('comment')) ?></textarea>
        </label>
        <button class="btn btn-primary" type="submit">Enviar avaliação</button>
    </form>
</section>
