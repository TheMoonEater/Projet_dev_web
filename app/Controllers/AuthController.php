<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\Db;
use App\Core\Http;
use App\Core\View;

final class AuthController
{
    public function showLogin(): void
    {
        View::render('auth/login', [
            'title' => 'Connexion',
            'metaDescription' => 'Connexion à la plateforme',
            'error' => '',
            'oldEmail' => ''
        ]);
    }

    public function login(): void
    {
        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        $stmt = Db::pdo()->prepare("
            SELECT id, email, password_hash, role
            FROM users
            WHERE email = :email
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            View::render('auth/login', [
                'title' => 'Connexion',
                'error' => 'Identifiants invalides',
            ]);
            return;
        }

        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'email' => (string)$user['email'],
            'role' => (string)$user['role'],
        ];

        Http::redirect('/'); // ✅ respecte BASE_PATH
    }

    public function logout(): void
    {
        // Optionnel: CSRF sur logout si tu veux (recommandé)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::check($_POST['_csrf'] ?? null)) {
                http_response_code(403);
                exit('CSRF invalide');
            }
        }

        unset($_SESSION['user']);
        session_destroy();

        Http::redirect('/login'); // ✅ respecte BASE_PATH
    }
}