<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Db;
use PDO;

class PilotStudent
{
    public static function studentsWithPilot(): array
    {
        $pdo = Db::pdo();

        $sql = "
            SELECT
                s.id,
                s.firstname,
                s.lastname,
                s.email,
                ps.pilot_id,
                p.firstname AS pilot_firstname,
                p.lastname AS pilot_lastname,
                p.email AS pilot_email
            FROM users s
            LEFT JOIN pilot_students ps ON ps.student_id = s.id
            LEFT JOIN users p ON p.id = ps.pilot_id
            WHERE s.role = 'STUDENT'
            ORDER BY s.lastname ASC, s.firstname ASC
        ";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function allPilots(): array
    {
        $pdo = Db::pdo();

        $sql = "
            SELECT id, firstname, lastname, email
            FROM users
            WHERE role = 'PILOT'
            ORDER BY lastname ASC, firstname ASC
        ";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function assign(int $pilotId, int $studentId): void
    {
        $pdo = Db::pdo();

        $delete = $pdo->prepare("DELETE FROM pilot_students WHERE student_id = :student_id");
        $delete->execute([
            'student_id' => $studentId
        ]);

        $insert = $pdo->prepare("
            INSERT INTO pilot_students (pilot_id, student_id)
            VALUES (:pilot_id, :student_id)
        ");

        $insert->execute([
            'pilot_id' => $pilotId,
            'student_id' => $studentId
        ]);
    }

    public static function unassignByStudent(int $studentId): void
    {
        $pdo = Db::pdo();

        $stmt = $pdo->prepare("DELETE FROM pilot_students WHERE student_id = :student_id");
        $stmt->execute([
            'student_id' => $studentId
        ]);
    }
}