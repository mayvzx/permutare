<?php

namespace Core;

use PDO;

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    protected function isPostgres(): bool
    {
        return Database::driver() === 'pgsql';
    }

    protected function insertAndReturnId(string $sql, array $params): int
    {
        if ($this->isPostgres()) {
            $sql .= ' RETURNING id';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $this->isPostgres()
            ? (int) $stmt->fetchColumn()
            : (int) $this->db->lastInsertId();
    }
}
