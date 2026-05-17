<?php

namespace App\Services;

use App\Models\Anuncio;
use App\Models\Proposta;
use Core\Database;
use PDO;

class ProposalService
{
    private PDO $db;
    private Proposta $propostas;
    private Anuncio $anuncios;

    public function __construct()
    {
        $this->db = Database::connection();
        $this->propostas = new Proposta();
        $this->anuncios = new Anuncio();
    }

    public function accept(int $proposalId, int $ownerId): bool
    {
        $proposal = $this->propostas->findById($proposalId);

        if (!$proposal || (int) $proposal['owner_id'] !== $ownerId || $proposal['status'] !== 'pending') {
            return false;
        }

        $this->db->beginTransaction();
        try {
            $this->propostas->updateStatus($proposalId, 'accepted');
            $this->propostas->rejectOtherPending((int) $proposal['anuncio_id'], $proposalId);
            $this->anuncios->updateStatus((int) $proposal['anuncio_id'], 'paused');
            $this->db->commit();

            return true;
        } catch (\Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    public function complete(int $proposalId, int $ownerId): bool
    {
        $proposal = $this->propostas->findById($proposalId);

        if (!$proposal || (int) $proposal['owner_id'] !== $ownerId || $proposal['status'] !== 'accepted') {
            return false;
        }

        $this->db->beginTransaction();
        try {
            $this->propostas->updateStatus($proposalId, 'completed');
            $this->anuncios->updateStatus((int) $proposal['anuncio_id'], 'completed');
            $this->db->commit();

            return true;
        } catch (\Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }
}
