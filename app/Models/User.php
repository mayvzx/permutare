<?php

namespace App\Models;

use Core\Model;
use PDO;

class User extends Model
{
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT u.*, p.institution, p.course, p.campus, p.bio, p.avatar_path,
                    COALESCE(s.average_rating, 0) AS average_rating,
                    COALESCE(s.total_reviews, 0) AS total_reviews,
                    COALESCE(s.reputation_points, 0) AS reputation_points,
                    COALESCE(s.reputation_level, 'beginner') AS reputation_level
             FROM users u
             LEFT JOIN user_profiles p ON p.user_id = u.id
             LEFT JOIN user_scores s ON s.user_id = u.id
             WHERE u.id = :id
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findPublicById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT u.id, u.name, u.role, u.status, u.created_at,
                    p.institution, p.course, p.campus, p.bio, p.avatar_path,
                    COALESCE(s.average_rating, 0) AS average_rating,
                    COALESCE(s.total_reviews, 0) AS total_reviews,
                    COALESCE(s.reputation_points, 0) AS reputation_points,
                    COALESCE(s.reputation_level, 'beginner') AS reputation_level
             FROM users u
             LEFT JOIN user_profiles p ON p.user_id = u.id
             LEFT JOIN user_scores s ON s.user_id = u.id
             WHERE u.id = :id AND u.status != 'deleted'
             LIMIT 1"
        );
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => mb_strtolower(trim($email))]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password_hash, role, status, created_at, updated_at)
             VALUES (:name, :email, :password_hash, :role, :status, NOW(), NOW())"
        );
        $stmt->execute([
            'name' => $data['name'],
            'email' => mb_strtolower(trim($data['email'])),
            'password_hash' => $data['password_hash'],
            'role' => $data['role'] ?? 'user',
            'status' => $data['status'] ?? 'active',
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function createProfile(int $userId, array $data): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO user_profiles (user_id, institution, course, campus, bio, avatar_path, created_at, updated_at)
             VALUES (:user_id, :institution, :course, :campus, :bio, :avatar_path, NOW(), NOW())"
        );
        $stmt->execute([
            'user_id' => $userId,
            'institution' => $data['institution'],
            'course' => $data['course'],
            'campus' => $data['campus'] ?? null,
            'bio' => $data['bio'] ?? null,
            'avatar_path' => $data['avatar_path'] ?? null,
        ]);
    }

    public function updateProfile(int $userId, array $data): void
    {
        $this->db->prepare("UPDATE users SET name = :name, updated_at = NOW() WHERE id = :id")
            ->execute(['name' => $data['name'], 'id' => $userId]);

        $stmt = $this->db->prepare(
            "UPDATE user_profiles
             SET institution = :institution, course = :course, campus = :campus,
                 bio = :bio, avatar_path = COALESCE(:avatar_path, avatar_path), updated_at = NOW()
             WHERE user_id = :user_id"
        );
        $stmt->execute([
            'institution' => $data['institution'],
            'course' => $data['course'],
            'campus' => $data['campus'] ?? null,
            'bio' => $data['bio'] ?? null,
            'avatar_path' => $data['avatar_path'] ?? null,
            'user_id' => $userId,
        ]);
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare("UPDATE users SET status = :status, updated_at = NOW() WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function updateRole(int $id, string $role): void
    {
        $stmt = $this->db->prepare("UPDATE users SET role = :role, updated_at = NOW() WHERE id = :id");
        $stmt->execute(['role' => $role, 'id' => $id]);
    }

    public function paginate(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $where = ["u.status != 'deleted'"];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = "(u.name LIKE :q_name OR u.email LIKE :q_email)";
            $params['q_name'] = '%' . $filters['q'] . '%';
            $params['q_email'] = '%' . $filters['q'] . '%';
        }

        $offset = max(0, ($page - 1) * $perPage);
        $sql = "SELECT u.*, p.institution, p.course
                FROM users u
                LEFT JOIN user_profiles p ON p.user_id = u.id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY u.created_at DESC
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

    public function latest(int $limit = 5): array
    {
        $stmt = $this->db->prepare("SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countAll(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM users WHERE status != 'deleted'")->fetchColumn();
    }
}
