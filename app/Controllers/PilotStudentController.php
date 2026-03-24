<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Http;
use App\Core\View;
use App\Models\PilotStudent;

class PilotStudentController
{
    private function ensureAllowed(): void
    {
        $role = Auth::role();

        if (!in_array($role, ['ADMIN', 'PILOT'], true)) {
            http_response_code(403);
            exit('Accès interdit');
        }
    }

    public function index(): void
    {
        $this->ensureAllowed();

        $students = PilotStudent::studentsWithPilot();
        $pilots = PilotStudent::allPilots();

        View::render('pilot_students/index', [
            'title' => 'Affectation étudiants / pilotes',
            'students' => $students,
            'pilots' => $pilots,
        ]);
    }

    public function assign(): void
    {
        $this->ensureAllowed();

        if (!Csrf::check($_POST['_csrf'] ?? '')) {
            http_response_code(419);
            exit('CSRF invalide');
        }

        $studentId = (int)($_POST['student_id'] ?? 0);
        $pilotId = (int)($_POST['pilot_id'] ?? 0);

        if ($studentId <= 0 || $pilotId <= 0) {
            Http::redirect('/pilot-students');
        }

        PilotStudent::assign($pilotId, $studentId);
        Http::redirect('/pilot-students');
    }

    public function unassign(): void
    {
        $this->ensureAllowed();

        if (!Csrf::check($_POST['_csrf'] ?? '')) {
            http_response_code(419);
            exit('CSRF invalide');
        }

        $studentId = (int)($_POST['student_id'] ?? 0);

        if ($studentId <= 0) {
            Http::redirect('/pilot-students');
        }

        PilotStudent::unassignByStudent($studentId);
        Http::redirect('/pilot-students');
    }
}