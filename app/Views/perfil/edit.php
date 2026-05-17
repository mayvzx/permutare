<section class="page-heading">
    <div>
        <h1>Editar perfil</h1>
        <p>Mantenha seus dados acadêmicos claros para aumentar confiança nas trocas.</p>
    </div>
</section>

<form method="post" action="<?= e(url('/perfil/atualizar')) ?>" enctype="multipart/form-data" class="form panel">
    <?= csrf_field() ?>
    <div class="profile-edit-preview">
        <img src="<?= e(upload_url($user['avatar_path'] ?? null)) ?>" data-preview alt="">
    </div>
    <label>
        Nome
        <input type="text" name="name" value="<?= e(old('name', $user['name'])) ?>" required minlength="3" maxlength="120">
    </label>
    <div class="form-grid">
        <label>
            Instituição
            <input type="text" name="institution" value="<?= e(old('institution', $user['institution'])) ?>" required>
        </label>
        <label>
            Curso
            <input type="text" name="course" value="<?= e(old('course', $user['course'])) ?>" required>
        </label>
    </div>
    <label>
        Campus
        <input type="text" name="campus" value="<?= e(old('campus', $user['campus'])) ?>">
    </label>
    <label>
        Bio
        <textarea name="bio" rows="5" maxlength="1000"><?= e(old('bio', $user['bio'])) ?></textarea>
    </label>
    <label>
        Foto de perfil
        <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" data-preview-input>
    </label>
    <button class="btn btn-primary" type="submit">Salvar perfil</button>
</form>
