<?php

namespace App\Models;

use Core\Model;

class UserScore extends Model
{
    public function createInitial(int $userId): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO user_scores (user_id, average_rating, total_reviews, reputation_points, reputation_level, updated_at)
             VALUES (:user_id, 0, 0, 0, 'beginner', NOW())"
        );
        $stmt->execute(['user_id' => $userId]);
    }

    public function upsert(int $userId, float $average, int $total, int $points, string $level): void
    {
        $sql = $this->isPostgres()
            ? "INSERT INTO user_scores (user_id, average_rating, total_reviews, reputation_points, reputation_level, updated_at)
               VALUES (:user_id, :average_rating, :total_reviews, :reputation_points, :reputation_level, NOW())
               ON CONFLICT (user_id) DO UPDATE SET
                  average_rating = EXCLUDED.average_rating,
                  total_reviews = EXCLUDED.total_reviews,
                  reputation_points = EXCLUDED.reputation_points,
                  reputation_level = EXCLUDED.reputation_level,
                  updated_at = NOW()"
            : "INSERT INTO user_scores (user_id, average_rating, total_reviews, reputation_points, reputation_level, updated_at)
               VALUES (:user_id, :average_rating, :total_reviews, :reputation_points, :reputation_level, NOW())
               ON DUPLICATE KEY UPDATE
                  average_rating = VALUES(average_rating),
                  total_reviews = VALUES(total_reviews),
                  reputation_points = VALUES(reputation_points),
                  reputation_level = VALUES(reputation_level),
                  updated_at = NOW()";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'average_rating' => $average,
            'total_reviews' => $total,
            'reputation_points' => $points,
            'reputation_level' => $level,
        ]);
    }
}
