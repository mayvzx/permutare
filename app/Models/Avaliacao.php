<?php

namespace App\Models;

use Core\Model;

class Avaliacao extends Model
{
    public function create(array $data): int
    {
        return $this->insertAndReturnId(
            "INSERT INTO avaliacoes (proposta_id, reviewer_id, reviewed_id, rating, comment, created_at, updated_at)
             VALUES (:proposta_id, :reviewer_id, :reviewed_id, :rating, :comment, NOW(), NOW())",
            [
                'proposta_id' => $data['proposta_id'],
                'reviewer_id' => $data['reviewer_id'],
                'reviewed_id' => $data['reviewed_id'],
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]
        );
    }

    public function findByReviewedUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, reviewer.name AS reviewer_name
             FROM avaliacoes a
             INNER JOIN users reviewer ON reviewer.id = a.reviewer_id
             WHERE a.reviewed_id = :user_id
             ORDER BY a.created_at DESC"
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function alreadyReviewed(int $propostaId, int $reviewerId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM avaliacoes
             WHERE proposta_id = :proposta_id AND reviewer_id = :reviewer_id"
        );
        $stmt->execute(['proposta_id' => $propostaId, 'reviewer_id' => $reviewerId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function calculateAverage(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(AVG(rating), 0) AS average_rating, COUNT(*) AS total_reviews
             FROM avaliacoes
             WHERE reviewed_id = :user_id"
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch();
    }
}
