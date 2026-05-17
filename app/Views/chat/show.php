<section class="chat-page">
    <div class="chat-header panel">
        <div>
            <span class="badge"><?= e(label_for('proposal_statuses', $proposta['status'])) ?></span>
            <h1><?= e($proposta['anuncio_title']) ?></h1>
            <p><?= e($proposta['owner_name']) ?> e <?= e($proposta['proposer_name']) ?></p>
        </div>
        <a class="btn btn-ghost" href="<?= e(url('/denuncias/criar?proposta_id=' . $proposta['id'])) ?>">Denunciar conversa</a>
    </div>

    <?php partial('safety-alert') ?>

    <div class="messages" data-chat-scroll>
        <?php if ($mensagens): ?>
            <?php foreach ($mensagens as $mensagem): ?>
                <article class="message <?= (int) $mensagem['sender_id'] === auth_id() ? 'message-own' : '' ?>">
                    <div class="message-bubble">
                        <strong><?= e($mensagem['sender_name']) ?></strong>
                        <p><?= nl2br(e($mensagem['message'])) ?></p>
                        <span><?= e(format_date($mensagem['created_at'])) ?></span>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <h2>Nenhuma mensagem ainda.</h2>
                <p>Comece combinando detalhes da troca com calma e segurança.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($proposta['status'] === 'accepted'): ?>
        <form method="post" action="<?= e(url('/chat/' . $proposta['id'] . '/mensagens')) ?>" class="chat-form">
            <?= csrf_field() ?>
            <textarea name="message" rows="3" maxlength="2000" placeholder="Escreva sua mensagem" required></textarea>
            <button class="btn btn-primary" type="submit">Enviar</button>
        </form>
    <?php else: ?>
        <div class="alert alert-info">Esta troca já foi concluída. O histórico fica disponível para consulta.</div>
    <?php endif; ?>
</section>
