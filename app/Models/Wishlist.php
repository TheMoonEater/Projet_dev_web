<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Db;

final class Wishlist
{
    public static function add(int $studentId, int $offerId): void
    {
        $stmt = Db::pdo()->prepare("
            INSERT IGNORE INTO wishlists (student_id, offer_id)
            VALUES (:s, :o)
        ");
        $stmt->execute(['s' => $studentId, 'o' => $offerId]);
    }

    public static function remove(int $studentId, int $offerId): void
    {
        $stmt = Db::pdo()->prepare("
            DELETE FROM wishlists
            WHERE student_id = :s AND offer_id = :o
        ");
        $stmt->execute(['s' => $studentId, 'o' => $offerId]);
    }

    public static function exists(int $studentId, int $offerId): bool
    {
        $stmt = Db::pdo()->prepare("
            SELECT 1 FROM wishlists WHERE student_id = :s AND offer_id = :o
        ");
        $stmt->execute(['s' => $studentId, 'o' => $offerId]);
        return (bool)$stmt->fetch();
    }

    public static function list(int $studentId): array
    {
        $stmt = Db::pdo()->prepare("
            SELECT o.id, o.title, o.posted_at, c.name AS company_name
            FROM wishlists w
            JOIN offers o ON o.id = w.offer_id
            JOIN companies c ON c.id = o.company_id
            WHERE w.student_id = :s
            ORDER BY w.created_at DESC
        ");
        $stmt->execute(['s' => $studentId]);
        return $stmt->fetchAll();
    }

    public static function countByOffer(): array
    {
        // utile plus tard pour SFx11 “top wish-list”
        $stmt = Db::pdo()->query("
            SELECT offer_id, COUNT(*) AS cnt
            FROM wishlists
            GROUP BY offer_id
            ORDER BY cnt DESC
            LIMIT 10
        ");
        return $stmt->fetchAll();
    }
}