<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Db;
use App\Core\Http;
use App\Core\View;

final class PilotController
{
    // SFx12
    public function index(): void
    {
        Auth::requirePermission('SFx12');

        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $stmt = Db::pdo()->prepare("
            SELECT COUNT(*) AS c
            FROM users
            WHERE role = 'PILOT'
              AND (firstname LIKE :q OR lastname LIKE :q OR email LIKE :q)
        ");
        $stmt->execute(['q' => "%$q%"]);
        $total = (int)($stmt->fetch()['c'] ?? 0);
        $pages = (int)ceil($total / $perPage);

        $stmt = Db::pdo()->prepare("
            SELECT id, firstname, lastname, email, role
            FROM users
            WHERE role = 'PILOT'
              AND (firstname LIKE :q OR lastname LIKE :q OR email LIKE :q)
            ORDER BY lastname ASC, firstname ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':q', "%$q%");
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        View::render('pilots/index', [
            'title' => 'Pilotes',
            'q' => $q,
            'items' => $items,
            'page' => $page,
            'pages' => $pages,
            'total' => $total,
        ]);
    }

    // SFx12
    public function show(string $id): void
    {
        Auth::requirePermission('SFx12');

        $stmt = Db::pdo()->prepare("
            SELECT id, firstname, lastname, email, role
            FROM users
            WHERE id = :id AND role = 'PILOT'
        ");
        $stmt->execute(['id' => (int)$id]);
        $pilot = $stmt->fetch();

        if (!$pilot) {
            http_response_code(404);
            exit('Pilote introuvable');
        }

        View::render('pilots/show', [
            'title' => 'Pilote',
            'pilot' => $pilot,
        ]);
    }

    // SFx13
    public function create(): void
    {
        Auth::requirePermission('SFx13');

        View::render('pilots/form', [
            'title' => 'Créer un pilote',
            'action' => Http::url('/pilots'),
            'pilot' => null,
        ]);
    }

    // SFx13
    public function store(): void
    {
        Auth::requirePermission('SFx13');

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        $firstname = trim((string)($_POST['firstname'] ?? ''));
        $lastname  = trim((string)($_POST['lastname'] ?? ''));
        $email     = trim((string)($_POST['email'] ?? ''));
        $password  = (string)($_POST['password'] ?? '');

        if ($firstname === '' || $lastname === '' || $email === '' || $password === '') {
            exit('Champs obligatoires manquants');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = Db::pdo()->prepare("
            INSERT INTO users (firstname, lastname, email, password_hash, role)
            VALUES (:f, :l, :e, :p, 'PILOT')
        ");
        $stmt->execute([
            'f' => $firstname,
            'l' => $lastname,
            'e' => $email,
            'p' => $hash,
        ]);

        Http::redirect('/pilots');
    }

    // SFx14
    public function edit(string $id): void
    {
        Auth::requirePermission('SFx14');

        $stmt = Db::pdo()->prepare("
            SELECT id, firstname, lastname, email, role
            FROM users
            WHERE id = :id AND role = 'PILOT'
        ");
        $stmt->execute(['id' => (int)$id]);
        $pilot = $stmt->fetch();

        if (!$pilot) {
            http_response_code(404);
            exit('Pilote introuvable');
        }

        View::render('pilots/form', [
            'title' => 'Modifier un pilote',
            'action' => Http::url('/pilots/' . (int)$id . '/update'),
            'pilot' => $pilot,
        ]);
    }

    // SFx14
    public function update(string $id): void
    {
        Auth::requirePermission('SFx14');

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        $firstname = trim((string)($_POST['firstname'] ?? ''));
        $lastname  = trim((string)($_POST['lastname'] ?? ''));
        $email     = trim((string)($_POST['email'] ?? ''));
        $password  = (string)($_POST['password'] ?? '');

        if ($firstname === '' || $lastname === '' || $email === '') {
            exit('Champs obligatoires manquants');
        }

        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = Db::pdo()->prepare("
                UPDATE users
                SET firstname=:f, lastname=:l, email=:e, password_hash=:p
                WHERE id=:id AND role='PILOT'
            ");
            $stmt->execute([
                'f' => $firstname,
                'l' => $lastname,
                'e' => $email,
                'p' => $hash,
                'id' => (int)$id,
            ]);
        } else {
            $stmt = Db::pdo()->prepare("
                UPDATE users
                SET firstname=:f, lastname=:l, email=:e
                WHERE id=:id AND role='PILOT'
            ");
            $stmt->execute([
                'f' => $firstname,
                'l' => $lastname,
                'e' => $email,
                'id' => (int)$id,
            ]);
        }

        Http::redirect('/pilots/' . (int)$id);
    }

    // SFx15
    public function delete(string $id): void
    {
        Auth::requirePermission('SFx15');

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        Db::pdo()->prepare("DELETE FROM users WHERE id=:id AND role='PILOT'")
            ->execute(['id' => (int)$id]);

        Http::redirect('/pilots');
    }
}