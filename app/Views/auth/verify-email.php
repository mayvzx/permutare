<section class="auth-card">
    <h1>Verificação de e-mail</h1>
    <p>O sistema gera um token seguro para confirmar seu e-mail. Em ambiente local, o link didático aparece abaixo para facilitar os testes.</p>

    <?php if (!empty($verificationLink)): ?>
        <div class="dev-link">
            <span>Link local:</span>
            <a href="<?= e($verificationLink) ?>"><?= e($verificationLink) ?></a>
        </div>
    <?php else: ?>
        <p>Se você recebeu um link de verificação, abra-o para ativar o e-mail da conta.</p>
    <?php endif; ?>

    <a class="btn btn-primary btn-full" href="<?= e(url('/login')) ?>">Ir para login</a>
</section>
