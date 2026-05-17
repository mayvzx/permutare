<?php

namespace App\Controllers;

use App\Models\Avaliacao;
use App\Models\Proposta;
use App\Services\ReputationService;
use Core\Controller;

class AvaliacaoController extends Controller
{
    public function create(int $propostaId): string
    {
        $proposta = $this->guardReview($propostaId);

        return $this->view('avaliacoes/create', [
            'title' => 'Avaliar troca',
            'proposta' => $proposta,
            'reviewedName' => $this->reviewedName($proposta),
        ]);
    }

    public function store(int $propostaId): void
    {
        $proposta = $this->guardReview($propostaId);
        $rating = (int) ($_POST['rating'] ?? 0);
        $comment = trim((string) ($_POST['comment'] ?? ''));

        if ($rating < 1 || $rating > 5 || mb_strlen($comment) > 1000) {
            set_old($_POST);
            flash('error', 'Informe uma nota entre 1 e 5 e comentario de ate 1000 caracteres.');
            redirect_back();
        }

        $reviewedId = (int) $proposta['owner_id'] === auth_id() ? (int) $proposta['proposer_id'] : (int) $proposta['owner_id'];

        (new Avaliacao())->create([
            'proposta_id' => $propostaId,
            'reviewer_id' => auth_id(),
            'reviewed_id' => $reviewedId,
            'rating' => $rating,
            'comment' => $comment,
        ]);

        (new ReputationService())->recalculate($reviewedId);

        flash('success', 'Avaliação registrada. Obrigado por fortalecer a reputação da comunidade.');
        redirect('/propostas/' . $propostaId);
    }

    private function guardReview(int $propostaId): array
    {
        $proposta = (new Proposta())->findById($propostaId);

        if (!$proposta || $proposta['status'] !== 'completed') {
            flash('error', 'A avaliação fica disponível apenas depois da conclusão da troca.');
            redirect('/propostas/enviadas');
        }

        if ((int) $proposta['owner_id'] !== auth_id() && (int) $proposta['proposer_id'] !== auth_id()) {
            flash('error', 'Você não participou desta troca.');
            redirect('/propostas/enviadas');
        }

        if ((new Avaliacao())->alreadyReviewed($propostaId, auth_id())) {
            flash('warning', 'Você já avaliou esta troca.');
            redirect('/propostas/' . $propostaId);
        }

        return $proposta;
    }

    private function reviewedName(array $proposta): string
    {
        return (int) $proposta['owner_id'] === auth_id() ? $proposta['proposer_name'] : $proposta['owner_name'];
    }
}
