<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Db;

final class Company
{
    public static function find(int $id): ?array
    {
        $stmt = Db::pdo()->prepare("SELECT * FROM companies WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function count(string $q): int
    {
        $stmt = Db::pdo()->prepare("
            SELECT COUNT(*) AS c
            FROM companies
            WHERE name LIKE :q OR description LIKE :q
        ");
        $stmt->execute(['q' => '%' . $q . '%']);
        return (int)($stmt->fetch()['c'] ?? 0);
    }

    public static function search(string $q, int $limit, int $offset): array
    {
        $stmt = Db::pdo()->prepare("
            SELECT *
            FROM companies
            WHERE name LIKE :q OR description LIKE :q
            ORDER BY name ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':q', '%' . $q . '%');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}