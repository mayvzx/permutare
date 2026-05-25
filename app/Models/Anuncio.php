<?php

namespace App\Models;

use Core\Model;
use PDO;

class Anuncio extends Model
{
    public function create(array $data): int
    {
        return $this->insertAndReturnId(
            "INSERT INTO anuncios
                (user_id, title, description, category, item_condition, desired_item, image_path, status, views_count, created_at, updated_at)
             VALUES
                (:user_id, :title, :description, :category, :item_condition, :desired_item, :image_path, 'active', 0, NOW(), NOW())",
            [
                'user_id' => $data['user_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'category' => $data['category'],
                'item_condition' => $data['item_condition'],
                'desired_item' => $data['desired_item'],
                'image_path' => $data['image_path'] ?? null,
            ]
        );
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*,
                    u.name AS owner_name, u.status AS owner_status,
                    p.institution AS owner_institution, p.course AS owner_course, p.avatar_path AS owner_avatar,
                    COALESCE(s.average_rating, 0) AS owner_average_rating,
                    COALESCE(s.total_reviews, 0) AS owner_total_reviews,
                    COALESCE(s.reputation_level, 'beginner') AS owner_reputation_level
             FROM anuncios a
             INNER JOIN users u ON u.id = a.user_id
             LEFT JOIN user_profiles p ON p.user_id = u.id
             LEFT JOIN user_scores s ON s.user_id = u.id
             WHERE a.id = :id
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $anuncio = $stmt->fetch();

        return $anuncio ?: null;
    }

    public function findPublicById(int $id): ?array
    {
        $anuncio = $this->findById($id);

        if (!$anuncio || $anuncio['status'] === 'removed' || $anuncio['owner_status'] === 'deleted') {
            return null;
        }

        return $anuncio;
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE anuncios SET views_count = views_count + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function paginateActive(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        $where = ["a.status = 'active'", "u.status = 'active'"];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = "(a.title LIKE :q_title OR a.description LIKE :q_description OR a.desired_item LIKE :q_desired)";
            $params['q_title'] = '%' . $filters['q'] . '%';
            $params['q_description'] = '%' . $filters['q'] . '%';
            $params['q_desired'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['category']) && valid_choice($filters['category'], config('constants.categories'))) {
            $where[] = 'a.category = :category';
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['condition']) && valid_choice($filters['condition'], config('constants.conditions'))) {
            $where[] = 'a.item_condition = :item_condition';
            $params['item_condition'] = $filters['condition'];
        }

        $orderBy = "a.created_at DESC";
        if (($filters['sort'] ?? '') === 'reputation') {
            $orderBy = "s.average_rating DESC, s.total_reviews DESC, a.created_at DESC";
        }

        $offset = max(0, ($page - 1) * $perPage);
        $sql = "SELECT a.*, u.name AS owner_name, p.institution AS owner_institution,
                       COALESCE(s.average_rating, 0) AS owner_average_rating,
                       COALESCE(s.total_reviews, 0) AS owner_total_reviews,
                       COALESCE(s.reputation_level, 'beginner') AS owner_reputation_level
                FROM anuncios a
                INNER JOIN users u ON u.id = a.user_id
                LEFT JOIN user_profiles p ON p.user_id = u.id
                LEFT JOIN user_scores s ON s.user_id = u.id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY {$orderBy}
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findRecent(int $limit = 6): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.name AS owner_name, p.institution AS owner_institution,
                    COALESCE(s.average_rating, 0) AS owner_average_rating,
                    COALESCE(s.total_reviews, 0) AS owner_total_reviews,
                    COALESCE(s.reputation_level, 'beginner') AS owner_reputation_level
             FROM anuncios a
             INNER JOIN users u ON u.id = a.user_id
             LEFT JOIN user_profiles p ON p.user_id = u.id
             LEFT JOIN user_scores s ON s.user_id = u.id
             WHERE a.status = 'active' AND u.status = 'active'
             ORDER BY a.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM anuncios WHERE user_id = :user_id AND status != 'removed' ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function findActiveByUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM anuncios WHERE user_id = :user_id AND status = 'active' ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            "UPDATE anuncios
             SET title = :title, description = :description, category = :category,
                 item_condition = :item_condition, desired_item = :desired_item,
                 image_path = COALESCE(:image_path, image_path), updated_at = NOW()
             WHERE id = :id"
        );
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'item_condition' => $data['item_condition'],
            'desired_item' => $data['desired_item'],
            'image_path' => $data['image_path'] ?? null,
            'id' => $id,
        ]);
    }

    public function updateStatus(int $id, string $status): void
    {
        $completedAt = $status === 'completed' ? ', completed_at = NOW()' : '';
        $stmt = $this->db->prepare("UPDATE anuncios SET status = :status, updated_at = NOW() {$completedAt} WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function belongsToUser(int $anuncioId, int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM anuncios WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $anuncioId, 'user_id' => $userId]);

        return (int) $stmt->fetchColumn() > 0;
    }

    public function latest(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.id, a.title, a.status, a.created_at, u.name AS owner_name
             FROM anuncios a
             INNER JOIN users u ON u.id = a.user_id
             ORDER BY a.created_at DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countByStatus(?string $status = null): int
    {
        if ($status) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM anuncios WHERE status = :status");
            $stmt->execute(['status' => $status]);
            return (int) $stmt->fetchColumn();
        }

        return (int) $this->db->query("SELECT COUNT(*) FROM anuncios")->fetchColumn();
    }

    public function adminList(array $filters = []): array
    {
        $where = ['1 = 1'];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = 'a.title LIKE :q';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['status']) && valid_choice($filters['status'], config('constants.anuncio_statuses'))) {
            $where[] = 'a.status = :status';
            $params['status'] = $filters['status'];
        }

        $stmt = $this->db->prepare(
            "SELECT a.*, u.name AS owner_name
             FROM anuncios a
             INNER JOIN users u ON u.id = a.user_id
             WHERE " . implode(' AND ', $where) . "
             ORDER BY a.created_at DESC
             LIMIT 100"
        );
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
