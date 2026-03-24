<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Db;
use App\Core\Http;
use App\Core\View;

final class ProfileController
{
    public function show(): void
    {
        Auth::requireLogin();

        $user = Auth::user();
        $stmt = Db::pdo()->prepare("SELECT id, email, role, profile_photo FROM users WHERE id = :id");
        $stmt->execute(['id' => (int)$user['id']]);
        $fresh = $stmt->fetch();

        View::render('profile/show', [
            'title' => 'Mon profil',
            'userRow' => $fresh,
            'error' => '',
            'success' => '',
        ]);
    }

    public function updatePhoto(): void
    {
        Auth::requireLogin();

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        $user = Auth::user();
        $uid = (int)$user['id'];

        if (empty($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $this->renderError('Aucune photo reçue.');
            return;
        }

        $file = $_FILES['photo'];

        // ✅ Sécurité: taille max 2 Mo
        if ($file['size'] > 2 * 1024 * 1024) {
            $this->renderError('Photo trop lourde (max 2 Mo).');
            return;
        }

        // ✅ Sécurité: vérifier le mime réel
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($allowed[$mime])) {
            $this->renderError('Format non accepté (jpg, png, webp).');
            return;
        }

        // ✅ Dossier upload
        $uploadDir = __DIR__ . '/../../public/uploads/avatars';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // ✅ Nom safe et unique
        $ext = $allowed[$mime];
        $filename = 'u' . $uid . '_' . bin2hex(random_bytes(10)) . '.' . $ext;
        $destPath = $uploadDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            $this->renderError('Erreur lors de l’enregistrement du fichier.');
            return;
        }

        // ✅ (optionnel) supprimer ancienne photo
        $stmt = Db::pdo()->prepare("SELECT profile_photo FROM users WHERE id = :id");
        $stmt->execute(['id' => $uid]);
        $old = $stmt->fetchColumn();

        if ($old) {
            $oldPath = __DIR__ . '/../../public' . $old;
            if (is_file($oldPath)) @unlink($oldPath);
        }

        // ✅ Enregistrer en DB (chemin public)
        $publicPath = '/uploads/avatars/' . $filename;
        $upd = Db::pdo()->prepare("UPDATE users SET profile_photo = :p WHERE id = :id");
        $upd->execute(['p' => $publicPath, 'id' => $uid]);

        // (Option) recharger user en session si tu stockes profile_photo en session
        Http::redirect('/profile?success=1');
    }

    private function renderError(string $msg): void
    {
        $user = Auth::user();
        $stmt = Db::pdo()->prepare("SELECT id, email, role, profile_photo FROM users WHERE id = :id");
        $stmt->execute(['id' => (int)$user['id']]);
        $fresh = $stmt->fetch();

        View::render('profile/show', [
            'title' => 'Mon profil',
            'userRow' => $fresh,
            'error' => $msg,
            'success' => '',
        ]);
    }
}