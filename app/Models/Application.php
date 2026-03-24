<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Db;

final class Application
{
    public static function alreadyApplied(int $offerId, int $studentId): bool
    {
        $stmt = Db::pdo()->prepare("SELECT id FROM applications WHERE offer_id=:o AND student_id=:s LIMIT 1");
        $stmt->execute(['o' => $offerId, 's' => $studentId]);
        return (bool)$stmt->fetch();
    }

    public static function create(int $offerId, int $studentId, string $lmText, string $cvPath): int
    {
        $stmt = Db::pdo()->prepare("
            INSERT INTO applications (offer_id, student_id, lm_text, cv_path)
            VALUES (:o, :s, :lm, :cv)
        ");
        $stmt->execute([
            'o' => $offerId,
            's' => $studentId,
            'lm' => $lmText,
            'cv' => $cvPath,
        ]);

        // compteur (optionnel)
        Db::pdo()->prepare("UPDATE offers SET applications_count = applications_count + 1 WHERE id = :id")
            ->execute(['id' => $offerId]);

        return (int)Db::pdo()->lastInsertId();
    }

    public static function mine(int $studentId): array
    {
        $stmt = Db::pdo()->prepare("
            SELECT a.id, a.applied_at, a.lm_text, a.cv_path,
                   o.id AS offer_id, o.title, c.name AS company_name
            FROM applications a
            JOIN offers o ON o.id = a.offer_id
            JOIN companies c ON c.id = o.company_id
            WHERE a.student_id = :sid
            ORDER BY a.applied_at DESC
        ");
        $stmt->execute(['sid' => $studentId]);
        return $stmt->fetchAll();
    }

    public static function find(int $appId): ?array
    {
        $stmt = Db::pdo()->prepare("
            SELECT a.*, o.title, o.id AS offer_id, c.name AS company_name
            FROM applications a
            JOIN offers o ON o.id = a.offer_id
            JOIN companies c ON c.id = o.company_id
            WHERE a.id = :id
        ");
        $stmt->execute(['id' => $appId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function pilotList(int $pilotId): array
    {
        // candidatures des étudiants reliés au pilote
        $stmt = Db::pdo()->prepare("
            SELECT a.id, a.applied_at,
                   u.firstname, u.lastname, u.email,
                   o.title, c.name AS company_name
            FROM pilot_students ps
            JOIN users u ON u.id = ps.student_id
            JOIN applications a ON a.student_id = u.id
            JOIN offers o ON o.id = a.offer_id
            JOIN companies c ON c.id = o.company_id
            WHERE ps.pilot_id = :pid
            ORDER BY a.applied_at DESC
        ");
        $stmt->execute(['pid' => $pilotId]);
        return $stmt->fetchAll();
    }
}