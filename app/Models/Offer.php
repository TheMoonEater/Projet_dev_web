<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Db;

final class Offer
{
    public static function count(string $q): int
    {
        $stmt = Db::pdo()->prepare("
            SELECT COUNT(*) AS c
            FROM offers o
            JOIN companies c ON c.id = o.company_id
            WHERE o.title LIKE :q OR o.description LIKE :q OR c.name LIKE :q
        ");
        $stmt->execute(['q' => '%' . $q . '%']);
        return (int)($stmt->fetch()['c'] ?? 0);
    }

    public static function search(string $q, int $limit, int $offset): array
    {
        $stmt = Db::pdo()->prepare("
            SELECT o.*, c.name AS company_name
            FROM offers o
            JOIN companies c ON c.id = o.company_id
            WHERE o.title LIKE :q OR o.description LIKE :q OR c.name LIKE :q
            ORDER BY o.posted_at DESC, o.id DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':q', '%' . $q . '%');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = Db::pdo()->prepare("
            SELECT o.*, c.name AS company_name
            FROM offers o
            JOIN companies c ON c.id = o.company_id
            WHERE o.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function skillsForOffer(int $offerId): array
    {
        $stmt = Db::pdo()->prepare("
            SELECT s.id, s.label
            FROM offer_skill os
            JOIN skills s ON s.id = os.skill_id
            WHERE os.offer_id = :id
            ORDER BY s.label ASC
        ");
        $stmt->execute(['id' => $offerId]);
        return $stmt->fetchAll();
    }

    public static function setSkills(int $offerId, array $skillIds): void
    {
        $pdo = Db::pdo();
        $pdo->prepare("DELETE FROM offer_skill WHERE offer_id = :id")->execute(['id' => $offerId]);

        $stmt = $pdo->prepare("INSERT INTO offer_skill (offer_id, skill_id) VALUES (:o, :s)");
        foreach ($skillIds as $sid) {
            $sid = (int)$sid;
            if ($sid > 0) {
                $stmt->execute(['o' => $offerId, 's' => $sid]);
            }
        }
    }

    public static function statsByDuration(): array
{
    $stmt = Db::pdo()->query("
        SELECT 
            CASE
                WHEN duration_weeks < 8 THEN 'Court (<2 mois)'
                WHEN duration_weeks BETWEEN 8 AND 16 THEN 'Moyen (2-4 mois)'
                ELSE 'Long (>4 mois)'
            END AS category,
            COUNT(*) AS total
        FROM offers
        GROUP BY category
    ");
    return $stmt->fetchAll();
}

public static function totalOffers(): int
{
    $stmt = Db::pdo()->query("SELECT COUNT(*) AS c FROM offers");
    return (int)$stmt->fetch()['c'];
}

public static function averageApplications(): float
{
    $stmt = Db::pdo()->query("
        SELECT AVG(app_count) AS avg_app
        FROM (
            SELECT COUNT(a.id) AS app_count
            FROM offers o
            LEFT JOIN applications a ON a.offer_id = o.id
            GROUP BY o.id
        ) t
    ");
    return (float)($stmt->fetch()['avg_app'] ?? 0);
}

    public static function topWishlist(): array
{
    $stmt = Db::pdo()->query("
        SELECT o.title, COUNT(*) AS total
        FROM offers o
        JOIN wishlists w ON w.offer_id = o.id
        GROUP BY o.id, o.title
        ORDER BY total DESC
        LIMIT 5
    ");
    return $stmt->fetchAll();
}
}