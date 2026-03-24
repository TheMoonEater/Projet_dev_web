<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Db;
use App\Core\Http;
use App\Core\View;
use App\Models\Company;

final class CompanyController
{
    /** SFx2 - Liste + recherche + pagination */
    public function index(): void
    {
        Auth::requirePermission('SFx2');

        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $total = Company::count($q);
        $items = Company::search($q, $perPage, $offset);
        $pages = (int)ceil($total / $perPage);

        View::render('companies/index', [
            'title' => 'Entreprises',
            'q' => $q,
            'items' => $items,
            'page' => $page,
            'pages' => $pages,
            'total' => $total,
        ]);
    }

public function show(string $id): void
{
    \App\Core\Auth::requirePermission('SFx2');

    $companyId = (int)$id;

    $company = \App\Models\Company::find($companyId);
    if (!$company) {
        http_response_code(404);
        exit('Entreprise introuvable');
    }

    // ✅ OFFRES DE CETTE ENTREPRISE
    $stmt = \App\Core\Db::pdo()->prepare("
        SELECT id, title, posted_at
        FROM offers
        WHERE company_id = :cid
        ORDER BY posted_at DESC, id DESC
        LIMIT 50
    ");
    $stmt->execute(['cid' => $companyId]);
    $offers = $stmt->fetchAll();

    \App\Core\View::render('companies/show', [
        'title' => $company['name'] ?? 'Entreprise',
        'company' => $company,
        'offers' => $offers, // ✅ IMPORTANT
    ]);
}

    /** SFx3 - Form création */
    public function create(): void
    {
        Auth::requirePermission('SFx3');

        View::render('companies/form', [
            'title' => 'Créer entreprise',
            'action' => Http::url('/companies'),
        ]);
    }

    /** SFx3 - Création */
    public function store(): void
    {
        Auth::requirePermission('SFx3');

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        $name  = trim((string)($_POST['name'] ?? ''));
        $desc  = trim((string)($_POST['description'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $phone = trim((string)($_POST['phone'] ?? ''));

        if ($name === '') {
            exit('Nom obligatoire');
        }

        $stmt = Db::pdo()->prepare("
            INSERT INTO companies (name, description, contact_email, contact_phone)
            VALUES (:n, :d, :e, :p)
        ");

        $stmt->execute([
            'n' => $name,
            'd' => $desc ?: null,
            'e' => $email ?: null,
            'p' => $phone ?: null,
        ]);

        Http::redirect('/companies');
    }

    /** SFx4 - Form édition */
    public function edit(string $id): void
    {
        Auth::requirePermission('SFx4');

        $company = Company::find((int)$id);
        if (!$company) {
            http_response_code(404);
            exit('Entreprise introuvable');
        }

        View::render('companies/form', [
            'title' => 'Modifier entreprise',
            'company' => $company,
            'action' => Http::url("/companies/$id/update"),
        ]);
    }

    /** SFx4 - Update */
    public function update(string $id): void
    {
        Auth::requirePermission('SFx4');

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        $name  = trim((string)($_POST['name'] ?? ''));
        $desc  = trim((string)($_POST['description'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $phone = trim((string)($_POST['phone'] ?? ''));

        if ($name === '') {
            exit('Nom obligatoire');
        }

        $stmt = Db::pdo()->prepare("
            UPDATE companies
            SET name = :n,
                description = :d,
                contact_email = :e,
                contact_phone = :p
            WHERE id = :id
        ");

        $stmt->execute([
            'n' => $name,
            'd' => $desc ?: null,
            'e' => $email ?: null,
            'p' => $phone ?: null,
            'id' => (int)$id,
        ]);

        Http::redirect('/companies/' . (int)$id);
    }

    /** SFx6 - Delete */
    public function delete(string $id): void
    {
        Auth::requirePermission('SFx6');

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        $stmt = Db::pdo()->prepare("DELETE FROM companies WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);

        Http::redirect('/companies');
    }

    /** SFx5 - Noter entreprise */
    public function rate(string $id): void
    {
        Auth::requirePermission('SFx5');

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        $rating = (int)($_POST['rating'] ?? 0);
        $user = Auth::user();

        if (!$user) {
            http_response_code(401);
            exit('Non connecté');
        }
        if ($rating < 1 || $rating > 5) {
            exit('Note invalide');
        }

        $pdo = Db::pdo();

        $stmt = $pdo->prepare("
            INSERT INTO company_reviews (company_id, author_user_id, rating)
            VALUES (:c, :u, :r)
            ON DUPLICATE KEY UPDATE rating = :r
        ");

        $stmt->execute([
            'c' => (int)$id,
            'u' => (int)$user['id'],
            'r' => $rating,
        ]);

        $pdo->prepare("
            UPDATE companies
            SET rating_avg = (SELECT AVG(rating) FROM company_reviews WHERE company_id = :id),
                rating_count = (SELECT COUNT(*) FROM company_reviews WHERE company_id = :id)
            WHERE id = :id
        ")->execute(['id' => (int)$id]);

        Http::redirect('/companies/' . (int)$id);
    }
}