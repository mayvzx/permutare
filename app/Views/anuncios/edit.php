<section class="page-heading">
    <div>
        <h1>Editar anúncio</h1>
        <p>Atualize as informações mantendo a descrição honesta e segura.</p>
    </div>
</section>

<form method="post" action="<?= e(url('/anuncios/' . $anuncio['id'] . '/atualizar')) ?>" enctype="multipart/form-data" class="form panel">
    <?= csrf_field() ?>
    <label>
        Titulo
        <input type="text" name="title" value="<?= e(old('title', $anuncio['title'])) ?>" minlength="5" maxlength="120" required>
    </label>
    <label>
        Descrição
        <textarea name="description" rows="6" minlength="20" maxlength="2000" required><?= e(old('description', $anuncio['description'])) ?></textarea>
    </label>
    <div class="form-grid">
        <label>
            Categoria
            <select name="category" required>
                <?php foreach (config('constants.categories') as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= old('category', $anuncio['category']) === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Condição
            <select name="item_condition" required>
                <?php foreach (config('constants.conditions') as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= old('item_condition', $anuncio['item_condition']) === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <label>
        O que você deseja em troca?
        <input type="text" name="desired_item" value="<?= e(old('desired_item', $anuncio['desired_item'])) ?>" maxlength="255" required>
    </label>
    <label>
        Nova imagem
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-preview-input>
    </label>
    <img class="image-preview" src="<?= e(upload_url($anuncio['image_path'])) ?>" data-preview alt="">
    <button class="btn btn-primary" type="submit">Salvar alterações</button>
</form>
