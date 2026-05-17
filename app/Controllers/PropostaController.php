<?php

namespace App\Controllers;

use App\Models\Anuncio;
use App\Models\Proposta;
use App\Services\ProposalService;
use Core\Controller;

class PropostaController extends Controller
{
    private Proposta $propostas;

    public function __construct()
    {
        $this->propostas = new Proposta();
    }

    public function store(int $anuncioId): void
    {
        $anuncio = (new Anuncio())->findById($anuncioId);
        $message = trim((string) ($_POST['message'] ?? ''));

        if (!$anuncio || $anuncio['status'] !== 'active') {
            flash('error', 'Este anúncio não está disponível para propostas.');
            redirect('/anuncios');
        }

        if ((int) $anuncio['user_id'] === auth_id()) {
            flash('error', 'Você não pode enviar proposta para seu próprio anúncio.');
            redirect('/anuncios/' . $anuncioId);
        }

        if (!str_between($message, 10, 1000)) {
            set_old($_POST);
            flash('error', 'A mensagem da proposta deve ter entre 10 e 1000 caracteres.');
            redirect('/anuncios/' . $anuncioId);
        }

        if ($this->propostas->hasPendingDuplicate($anuncioId, auth_id())) {
            flash('warning', 'Você já possui uma proposta pendente para este anúncio.');
            redirect('/anuncios/' . $anuncioId);
        }

        $this->propostas->create([
            'anuncio_id' => $anuncioId,
            'owner_id' => $anuncio['user_id'],
            'proposer_id' => auth_id(),
            'message' => $message,
        ]);

        flash('success', 'Proposta enviada com sucesso.');
        redirect('/propostas/enviadas');
    }

    public function sent(): string
    {
        return $this->view('propostas/sent', [
            'title' => 'Propostas enviadas',
            'propostas' => $this->propostas->findSentByUser(auth_id()),
        ]);
    }

    public function received(): string
    {
        return $this->view('propostas/received', [
            'title' => 'Propostas recebidas',
            'propostas' => $this->propostas->findReceivedByUser(auth_id()),
        ]);
    }

    public function show(int $id): string
    {
        $proposta = $this->guardParticipant($id);

        return $this->view('propostas/show', [
            'title' => 'Detalhe da proposta',
            'proposta' => $proposta,
        ]);
    }

    public function accept(int $id): void
    {
        $ok = (new ProposalService())->accept($id, auth_id());
        flash($ok ? 'success' : 'error', $ok ? 'Proposta aceita. O chat foi liberado.' : 'Não foi possível aceitar esta proposta.');
        redirect('/propostas/recebidas');
    }

    public function reject(int $id): void
    {
        $proposta = $this->propostas->findById($id);

        if (!$proposta || (int) $proposta['owner_id'] !== auth_id() || $proposta['status'] !== 'pending') {
            flash('error', 'Não foi possível recusar esta proposta.');
            redirect('/propostas/recebidas');
        }

        $this->propostas->updateStatus($id, 'rejected');
        flash('success', 'Proposta recusada.');
        redirect('/propostas/recebidas');
    }

    public function cancel(int $id): void
    {
        $proposta = $this->propostas->findById($id);

        if (!$proposta || (int) $proposta['proposer_id'] !== auth_id() || $proposta['status'] !== 'pending') {
            flash('error', 'Não foi possível cancelar esta proposta.');
            redirect('/propostas/enviadas');
        }

        $this->propostas->updateStatus($id, 'cancelled');
        flash('success', 'Proposta cancelada.');
        redirect('/propostas/enviadas');
    }

    public function complete(int $id): void
    {
        $ok = (new ProposalService())->complete($id, auth_id());
        flash($ok ? 'success' : 'error', $ok ? 'Troca concluída. As avaliações foram liberadas.' : 'Não foi possível concluir esta troca.');
        redirect('/propostas/recebidas');
    }

    private function guardParticipant(int $id): array
    {
        $proposta = $this->propostas->findById($id);

        if (!$proposta || ((int) $proposta['owner_id'] !== auth_id() && (int) $proposta['proposer_id'] !== auth_id())) {
            flash('error', 'Você não tem permissão para acessar esta proposta.');
            redirect('/anuncios');
        }

        return $proposta;
    }
}
