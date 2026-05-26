<section class="auth-card">
    <h1>Entrar</h1>
    <p>Acesse sua conta para publicar anúncios, enviar propostas e conversar após o aceite.</p>
    <form method="post" action="<?= e(url('/login')) ?>" class="form">
        <?= csrf_field() ?>
        <label>
            E-mail
            <input type="email" name="email" value="<?= e(old('email')) ?>" required>
        </label>
        <label>
            Senha
            <span class="password-field">
                <input type="password" name="password" required data-password-input>
                <button class="password-toggle" type="button" data-password-toggle aria-label="Mostrar senha" aria-pressed="false">
                    <span aria-hidden="true">Ver</span>
                </button>
            </span>
        </label>
        <button class="btn btn-primary btn-full" type="submit">Entrar</button>
    </form>
    <p class="auth-switch">Ainda não tem conta? <a href="<?= e(url('/cadastro')) ?>">Criar conta</a></p>
</section>
