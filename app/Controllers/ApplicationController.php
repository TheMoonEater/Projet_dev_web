<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Http;
use App\Core\View;
use App\Models\Application;
use App\Models\Offer;

final class ApplicationController
{
    /** SFx20 - Form postuler */
    public function create(string $offerId): void
    {
        Auth::requirePermission('SFx20');

        $user = Auth::user();
        $offer = Offer::find((int)$offerId);
        if (!$offer) {
            http_response_code(404);
            exit('Offre introuvable');
        }

        if (Application::alreadyApplied((int)$offerId, (int)$user['id'])) {
            exit('Tu as déjà postulé à cette offre.');
        }

        View::render('applications/form', [
            'title' => 'Postuler',
            'offer' => $offer,
            'action' => Http::url("/offers/$offerId/apply"),
            'errors' => [],
            'old' => []
        ]);
    }

    /** SFx20 - Submit candidature + upload CV */
    public function store(string $offerId): void
    {
        Auth::requirePermission('SFx20');

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        $user = Auth::user();
        $offer = Offer::find((int)$offerId);
        if (!$offer) {
            http_response_code(404);
            exit('Offre introuvable');
        }

        if (Application::alreadyApplied((int)$offerId, (int)$user['id'])) {
            exit('Déjà postulé.');
        }

        $lm = trim((string)($_POST['lm_text'] ?? ''));
        if ($lm === '') {
            exit('Lettre de motivation obligatoire.');
        }

        // Upload CV
        if (empty($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
            exit('CV obligatoire (upload).');
        }

        $tmp = $_FILES['cv']['tmp_name'];
        $original = (string)$_FILES['cv']['name'];

        // Sécurité : extension autorisée
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx'];
        if (!in_array($ext, $allowed, true)) {
            exit('CV: formats autorisés = PDF/DOC/DOCX');
        }

        // Nom de fichier safe
        $safeName = 'cv_offer' . (int)$offerId . '_student' . (int)$user['id'] . '_' . bin2hex(random_bytes(8)) . '.' . $ext;

        $storageDir = dirname(__DIR__, 2) . '/storage/cvs';
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0777, true);
        }

        $dest = $storageDir . '/' . $safeName;

        if (!move_uploaded_file($tmp, $dest)) {
            exit('Erreur upload CV');
        }

        // On stocke seulement le nom (pas le chemin absolu)
        $appId = Application::create((int)$offerId, (int)$user['id'], $lm, $safeName);

        Http::redirect('/my-applications');
    }

    /** SFx21 - Mes candidatures */
    public function mine(): void
    {
        Auth::requirePermission('SFx21');

        $user = Auth::user();
        $items = Application::mine((int)$user['id']);

        View::render('applications/mine', [
            'title' => 'Mes candidatures',
            'items' => $items,
        ]);
    }

    /** SFx22 - Candidatures des élèves du pilote */
    public function pilot(): void
    {
        Auth::requirePermission('SFx22');

        $user = Auth::user();
        $items = Application::pilotList((int)$user['id']);

        View::render('applications/pilot', [
            'title' => 'Candidatures de mes élèves',
            'items' => $items,
        ]);
    }

    /** Download sécurisé du CV */
    public function downloadCv(string $appId): void
    {
        $app = Application::find((int)$appId);
        if (!$app) {
            http_response_code(404);
            exit('Candidature introuvable');
        }

        $user = Auth::user();
        if (!$user) {
            http_response_code(401);
            exit('Non connecté');
        }

        $role = Auth::role();

        // Autorisations :
        // - Étudiant : seulement son CV
        // - Pilote : seulement CV des élèves liés (pilot_students)
        // - Admin : accès ok
        if ($role === 'STUDENT' && (int)$app['student_id'] !== (int)$user['id']) {
            http_response_code(403);
            exit('Accès refusé');
        }

        if ($role === 'PILOT') {
            // vérifier pilot_students
            $pdo = \App\Core\Db::pdo();
            $stmt = $pdo->prepare("SELECT 1 FROM pilot_students WHERE pilot_id=:p AND student_id=:s");
            $stmt->execute(['p' => (int)$user['id'], 's' => (int)$app['student_id']]);
            if (!$stmt->fetch()) {
                http_response_code(403);
                exit('Accès refusé');
            }
        }

        // ADMIN ok
        $storageDir = dirname(__DIR__, 2) . '/storage/cvs';
        $file = $storageDir . '/' . $app['cv_path'];

        if (!is_file($file)) {
            http_response_code(404);
            exit('Fichier CV introuvable');
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="cv_' . (int)$appId . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}