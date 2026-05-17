<?php

namespace App\Controllers;

use App\Models\Denuncia;
use Core\Controller;
use Core\Request;

class DenunciaController extends Controller
{
    public function create(): string
    {
        $request = new Request();

        return $this->view('denuncias/create', [
            'title' => 'Criar denúncia',
            'reportedUserId' => $request->query('reported_user_id'),
            'anuncioId' => $request->query('anuncio_id'),
            'propostaId' => $request->query('proposta_id'),
        ]);
    }

    public function store(): void
    {
        $reason = (string) ($_POST['reason'] ?? '');
        $description = trim((string) ($_POST['description'] ?? ''));

        $reportedUserId = (int) ($_POST['reported_user_id'] ?? 0) ?: null;
        $anuncioId = (int) ($_POST['anuncio_id'] ?? 0) ?: null;
        $propostaId = (int) ($_POST['proposta_id'] ?? 0) ?: null;

        if (!valid_choice($reason, config('constants.report_reasons')) || mb_strlen($description) > 1500 || (!$reportedUserId && !$anuncioId && !$propostaId)) {
            set_old($_POST);
            flash('error', 'Informe um motivo válido e um alvo para a denúncia.');
            redirect_back();
        }

        (new Denuncia())->create([
            'reporter_id' => auth_id(),
            'reported_user_id' => $reportedUserId,
            'anuncio_id' => $anuncioId,
            'proposta_id' => $propostaId,
            'reason' => $reason,
            'description' => $description,
        ]);

        flash('success', 'Denúncia enviada para moderação.');
        redirect('/anuncios');
    }
}
