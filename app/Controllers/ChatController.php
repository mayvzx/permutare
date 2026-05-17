<?php

namespace App\Controllers;

use App\Models\Mensagem;
use App\Models\Proposta;
use Core\Controller;

class ChatController extends Controller
{
    public function show(int $propostaId): string
    {
        $proposta = $this->guardChat($propostaId);
        $mensagens = new Mensagem();
        $mensagens->markAsRead($propostaId, auth_id());

        return $this->view('chat/show', [
            'title' => 'Conversa da troca',
            'proposta' => $proposta,
            'mensagens' => $mensagens->findByProposta($propostaId),
        ]);
    }

    public function store(int $propostaId): void
    {
        $this->guardChat($propostaId);
        $message = trim((string) ($_POST['message'] ?? ''));

        if (!str_between($message, 1, 2000)) {
            flash('error', 'Envie uma mensagem entre 1 e 2000 caracteres.');
            redirect('/chat/' . $propostaId);
        }

        (new Mensagem())->create([
            'proposta_id' => $propostaId,
            'sender_id' => auth_id(),
            'message' => $message,
        ]);

        redirect('/chat/' . $propostaId);
    }

    private function guardChat(int $propostaId): array
    {
        $proposta = (new Proposta())->findById($propostaId);

        if (
            !$proposta ||
            !in_array($proposta['status'], ['accepted', 'completed'], true) ||
            ((int) $proposta['owner_id'] !== auth_id() && (int) $proposta['proposer_id'] !== auth_id())
        ) {
            flash('error', 'Você não tem permissão para acessar esta conversa.');
            redirect('/propostas/enviadas');
        }

        return $proposta;
    }
}
