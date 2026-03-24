<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Db;

final class Skill
{
    public static function all(): array
    {
        $stmt = Db::pdo()->query("SELECT id, label FROM skills ORDER BY label ASC");
        return $stmt->fetchAll();
    }
}