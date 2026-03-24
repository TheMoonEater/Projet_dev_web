<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Db;
use App\Core\Http;
use App\Core\View;

final class StudentController
{
    // SFx16
    public function index(): void
    {
        Auth::requirePermission('SFx16');

        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $stmt = Db::pdo()->prepare("
            SELECT COUNT(*) AS c
            FROM users
            WHERE role = 'STUDENT'
              AND (firstname LIKE :q OR lastname LIKE :q OR email LIKE :q)
        ");
        $stmt->execute(['q' => "%$q%"]);
        $total = (int)($stmt->fetch()['c'] ?? 0);
        $pages = (int)ceil($total / $perPage);

        $stmt = Db::pdo()->prepare("
            SELECT id, firstname, lastname, email, role
            FROM users
            WHERE role = 'STUDENT'
              AND (firstname LIKE :q OR lastname LIKE :q OR email LIKE :q)
            ORDER BY lastname ASC, firstname ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':q', "%$q%");
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        View::render('students/index', [
            'title' => 'Étudiants',
            'q' => $q,
            'items' => $items,
            'page' => $page,
            'pages' => $pages,
            'total' => $total,
        ]);
    }

    // SFx16
    public function show(string $id): void
    {
        Auth::requirePermission('SFx16');

        $stmt = Db::pdo()->prepare("
            SELECT id, firstname, lastname, email, role
            FROM users
            WHERE id = :id AND role = 'STUDENT'
        ");
        $stmt->execute(['id' => (int)$id]);
        $student = $stmt->fetch();

        if (!$student) {
            http_response_code(404);
            exit('Étudiant introuvable');
        }

        // (Optionnel) stats stage de l'étudiant (candidatures)
        $stmt = Db::pdo()->prepare("
            SELECT COUNT(*) AS c FROM applications WHERE student_id = :sid
        ");
        $stmt->execute(['sid' => (int)$id]);
        $applicationsCount = (int)($stmt->fetch()['c'] ?? 0);

        View::render('students/show', [
            'title' => 'Étudiant',
            'student' => $student,
            'applicationsCount' => $applicationsCount,
        ]);
    }

    // SFx17
    public function create(): void
    {
        Auth::requirePermission('SFx17');

        View::render('students/form', [
            'title' => 'Créer un étudiant',
            'action' => Http::url('/students'),
            'student' => null,
        ]);
    }

    // SFx17
    public function store(): void
    {
        Auth::requirePermission('SFx17');

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
            VALUES (:f, :l, :e, :p, 'STUDENT')
        ");
        $stmt->execute([
            'f' => $firstname,
            'l' => $lastname,
            'e' => $email,
            'p' => $hash,
        ]);

        Http::redirect('/students');
    }

    // SFx18
    public function edit(string $id): void
    {
        Auth::requirePermission('SFx18');

        $stmt = Db::pdo()->prepare("
            SELECT id, firstname, lastname, email, role
            FROM users
            WHERE id = :id AND role = 'STUDENT'
        ");
        $stmt->execute(['id' => (int)$id]);
        $student = $stmt->fetch();

        if (!$student) {
            http_response_code(404);
            exit('Étudiant introuvable');
        }

        View::render('students/form', [
            'title' => 'Modifier un étudiant',
            'action' => Http::url('/students/' . (int)$id . '/update'),
            'student' => $student,
        ]);
    }

    // SFx18
    public function update(string $id): void
    {
        Auth::requirePermission('SFx18');

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
                WHERE id=:id AND role='STUDENT'
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
                WHERE id=:id AND role='STUDENT'
            ");
            $stmt->execute([
                'f' => $firstname,
                'l' => $lastname,
                'e' => $email,
                'id' => (int)$id,
            ]);
        }

        Http::redirect('/students/' . (int)$id);
    }

    // SFx19
    public function delete(string $id): void
    {
        Auth::requirePermission('SFx19');

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        Db::pdo()->prepare("DELETE FROM users WHERE id=:id AND role='STUDENT'")
            ->execute(['id' => (int)$id]);

        Http::redirect('/students');
    }
}