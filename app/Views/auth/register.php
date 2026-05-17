<section class="auth-card auth-card-wide">
    <h1>Criar conta</h1>
    <p>Use seus dados acadêmicos básicos para construir confiança nas trocas.</p>
    <form method="post" action="<?= e(url('/cadastro')) ?>" class="form form-grid">
        <?= csrf_field() ?>
        <label>
            Nome
            <input type="text" name="name" value="<?= e(old('name')) ?>" required minlength="3" maxlength="120">
        </label>
        <label>
            E-mail
            <input type="email" name="email" value="<?= e(old('email')) ?>" required>
        </label>
        <label>
            Senha
            <input type="password" name="password" required minlength="8">
        </label>
        <label>
            Confirmar senha
            <input type="password" name="password_confirmation" required minlength="8">
        </label>
        <label>
            Instituição
            <input type="text" name="institution" value="<?= e(old('institution')) ?>" required>
        </label>
        <label>
            Curso
            <input type="text" name="course" value="<?= e(old('course')) ?>" required>
        </label>
        <label>
            Campus
            <input type="text" name="campus" value="<?= e(old('campus')) ?>">
        </label>
        <label class="checkbox-line">
            <input type="checkbox" name="terms" value="1" required>
            Aceito usar a plataforma com respeito, segurança e responsabilidade.
        </label>
        <button class="btn btn-primary btn-full" type="submit">Criar conta</button>
    </form>
    <p class="auth-switch">Já tem conta? <a href="<?= e(url('/login')) ?>">Entrar</a></p>
</section>
