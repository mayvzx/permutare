<?php

namespace App\Models;

use Core\Model;

class Proposta extends Model
{
    public function create(array $data): int
    {
        return $this->insertAndReturnId(
            "INSERT INTO propostas (anuncio_id, owner_id, proposer_id, message, status, created_at, updated_at)
             VALUES (:anuncio_id, :owner_id, :proposer_id, :message, 'pending', NOW(), NOW())",
            [
                'anuncio_id' => $data['anuncio_id'],
                'owner_id' => $data['owner_id'],
                'proposer_id' => $data['proposer_id'],
                'message' => $data['message'],
            ]
        );
    }

    public function hasPendingDuplicate(int $anuncioId, int $proposerId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM propostas
             WHERE anuncio_id = :anuncio_id AND proposer_id = :proposer_id AND status = 'pending'"
        );
        $stmt->execute(['anuncio_id' => $anuncioId, 'proposer_id' => $proposerId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, a.title AS anuncio_title, a.image_path AS anuncio_image, a.status AS anuncio_status,
                    owner.name AS owner_name, proposer.name AS proposer_name
             FROM propostas p
             INNER JOIN anuncios a ON a.id = p.anuncio_id
             INNER JOIN users owner ON owner.id = p.owner_id
             INNER JOIN users proposer ON proposer.id = p.proposer_id
             WHERE p.id = :id
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $proposal = $stmt->fetch();

        return $proposal ?: null;
    }

    public function findSentByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, a.title AS anuncio_title, a.image_path AS anuncio_image, owner.name AS owner_name
             FROM propostas p
             INNER JOIN anuncios a ON a.id = p.anuncio_id
             INNER JOIN users owner ON owner.id = p.owner_id
             WHERE p.proposer_id = :user_id
             ORDER BY p.created_at DESC"
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function findReceivedByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, a.title AS anuncio_title, a.image_path AS anuncio_image, proposer.name AS proposer_name
             FROM propostas p
             INNER JOIN anuncios a ON a.id = p.anuncio_id
             INNER JOIN users proposer ON proposer.id = p.proposer_id
             WHERE p.owner_id = :user_id
             ORDER BY p.created_at DESC"
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): void
    {
        $extra = match ($status) {
            'accepted' => ', accepted_at = NOW()',
            'cancelled' => ', cancelled_at = NOW()',
            default => '',
        };

        $stmt = $this->db->prepare("UPDATE propostas SET status = :status, updated_at = NOW() {$extra} WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function rejectOtherPending(int $anuncioId, int $acceptedProposalId): void
    {
        $stmt = $this->db->prepare(
            "UPDATE propostas
             SET status = 'rejected', updated_at = NOW()
             WHERE anuncio_id = :anuncio_id AND id != :id AND status = 'pending'"
        );
        $stmt->execute(['anuncio_id' => $anuncioId, 'id' => $acceptedProposalId]);
    }

    public function userIsParticipant(int $propostaId, int $userId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM propostas
             WHERE id = :id AND (owner_id = :owner_user_id OR proposer_id = :proposer_user_id)"
        );
        $stmt->execute([
            'id' => $propostaId,
            'owner_user_id' => $userId,
            'proposer_user_id' => $userId,
        ]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function countAll(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM propostas")->fetchColumn();
    }
}
