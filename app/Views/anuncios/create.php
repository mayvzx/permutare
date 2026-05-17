<section class="page-heading">
    <div>
        <h1>Criar anúncio</h1>
        <p>Descreva bem o item e deixe claro o que você aceita em troca.</p>
    </div>
</section>

<form method="post" action="<?= e(url('/anuncios')) ?>" enctype="multipart/form-data" class="form panel">
    <?= csrf_field() ?>
    <label>
        Titulo
        <input type="text" name="title" value="<?= e(old('title')) ?>" minlength="5" maxlength="120" required>
    </label>
    <label>
        Descrição
        <textarea name="description" rows="6" minlength="20" maxlength="2000" required><?= e(old('description')) ?></textarea>
    </label>
    <div class="form-grid">
        <label>
            Categoria
            <select name="category" required>
                <?php foreach (config('constants.categories') as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= old('category') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>
            Condição
            <select name="item_condition" required>
                <?php foreach (config('constants.conditions') as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= old('item_condition') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <label>
        O que você deseja em troca?
        <input type="text" name="desired_item" value="<?= e(old('desired_item')) ?>" maxlength="255" required>
    </label>
    <label>
        Imagem do item
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" data-preview-input>
    </label>
    <img class="image-preview" data-preview alt="">
    <button class="btn btn-primary" type="submit">Publicar anúncio</button>
</form>
