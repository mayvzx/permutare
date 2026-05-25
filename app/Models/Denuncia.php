<?php

namespace App\Models;

use Core\Model;

class Denuncia extends Model
{
    public function create(array $data): int
    {
        return $this->insertAndReturnId(
            "INSERT INTO denuncias
                (reporter_id, reported_user_id, anuncio_id, proposta_id, reason, description, status, created_at)
             VALUES
                (:reporter_id, :reported_user_id, :anuncio_id, :proposta_id, :reason, :description, 'pending', NOW())",
            [
                'reporter_id' => $data['reporter_id'],
                'reported_user_id' => $data['reported_user_id'] ?? null,
                'anuncio_id' => $data['anuncio_id'] ?? null,
                'proposta_id' => $data['proposta_id'] ?? null,
                'reason' => $data['reason'],
                'description' => $data['description'] ?? null,
            ]
        );
    }

    public function paginate(array $filters = []): array
    {
        $where = ['1 = 1'];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = 'd.status = :status';
            $params['status'] = $filters['status'];
        }

        $stmt = $this->db->prepare(
            "SELECT d.*, reporter.name AS reporter_name, reported.name AS reported_user_name, a.title AS anuncio_title
             FROM denuncias d
             INNER JOIN users reporter ON reporter.id = d.reporter_id
             LEFT JOIN users reported ON reported.id = d.reported_user_id
             LEFT JOIN anuncios a ON a.id = d.anuncio_id
             WHERE " . implode(' AND ', $where) . "
             ORDER BY d.created_at DESC
             LIMIT 100"
        );
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status, ?string $notes = null): void
    {
        $resolvedAt = in_array($status, ['resolved', 'archived'], true) ? ', resolved_at = NOW()' : '';
        $stmt = $this->db->prepare(
            "UPDATE denuncias
             SET status = :status,
                 admin_notes = COALESCE(:admin_notes, admin_notes)
                 {$resolvedAt}
             WHERE id = :id"
        );
        $stmt->execute([
            'status' => $status,
            'admin_notes' => $notes,
            'id' => $id,
        ]);
    }

    public function latest(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT d.*, reporter.name AS reporter_name
             FROM denuncias d
             INNER JOIN users reporter ON reporter.id = d.reporter_id
             ORDER BY d.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countPending(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM denuncias WHERE status = 'pending'")->fetchColumn();
    }
}
