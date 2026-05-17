<section class="panel narrow-panel">
    <h1>Criar denúncia</h1>
    <p>Use este canal para ajudar a manter a comunidade segura e confiável.</p>
    <form method="post" action="<?= e(url('/denuncias')) ?>" class="form">
        <?= csrf_field() ?>
        <input type="hidden" name="reported_user_id" value="<?= e($reportedUserId) ?>">
        <input type="hidden" name="anuncio_id" value="<?= e($anuncioId) ?>">
        <input type="hidden" name="proposta_id" value="<?= e($propostaId) ?>">
        <label>
            Motivo
            <select name="reason" required>
                <option value="">Selecione</option>
                <?php foreach (config('constants.report_reasons') as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= old('reason') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Descrição
            <textarea name="description" rows="6" maxlength="1500"><?= e(old('description')) ?></textarea>
        </label>
        <button class="btn btn-danger" type="submit">Enviar denúncia</button>
    </form>
</section>
