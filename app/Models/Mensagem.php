<?php

namespace App\Models;

use Core\Model;

class Mensagem extends Model
{
    public function create(array $data): int
    {
        return $this->insertAndReturnId(
            "INSERT INTO mensagens (proposta_id, sender_id, message, created_at)
             VALUES (:proposta_id, :sender_id, :message, NOW())",
            [
                'proposta_id' => $data['proposta_id'],
                'sender_id' => $data['sender_id'],
                'message' => $data['message'],
            ]
        );
    }

    public function findByProposta(int $propostaId): array
    {
        $stmt = $this->db->prepare(
            "SELECT m.*, u.name AS sender_name
             FROM mensagens m
             INNER JOIN users u ON u.id = m.sender_id
             WHERE m.proposta_id = :proposta_id
             ORDER BY m.created_at ASC"
        );
        $stmt->execute(['proposta_id' => $propostaId]);

        return $stmt->fetchAll();
    }

    public function markAsRead(int $propostaId, int $userId): void
    {
        $stmt = $this->db->prepare(
            "UPDATE mensagens
             SET read_at = NOW()
             WHERE proposta_id = :proposta_id AND sender_id != :user_id AND read_at IS NULL"
        );
        $stmt->execute(['proposta_id' => $propostaId, 'user_id' => $userId]);
    }

    public function countUnread(int $userId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*)
             FROM mensagens m
             INNER JOIN propostas p ON p.id = m.proposta_id
             WHERE m.read_at IS NULL
               AND m.sender_id != :sender_user_id
               AND (p.owner_id = :owner_user_id OR p.proposer_id = :proposer_user_id)
               AND p.status IN ('accepted', 'completed')"
        );
        $stmt->execute([
            'sender_user_id' => $userId,
            'owner_user_id' => $userId,
            'proposer_user_id' => $userId,
        ]);

        return (int) $stmt->fetchColumn();
    }
}
