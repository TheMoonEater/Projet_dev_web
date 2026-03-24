<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Db;
use App\Core\Http;
use App\Core\View;
use App\Models\Offer;
use App\Models\Skill;
use App\Models\Company;

final class OfferController
{
    public function index(): void
    {
        Auth::requirePermission('SFx7');

        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $total = Offer::count($q);
        $items = Offer::search($q, $perPage, $offset);
        $pages = (int)ceil($total / $perPage);

        View::render('offers/index', [
            'title' => 'Offres',
            'q' => $q,
            'items' => $items,
            'page' => $page,
            'pages' => $pages,
            'total' => $total,
        ]);
    }

    public function show(string $id): void
{
    Auth::requirePermission('SFx7');

    $offerId = (int)$id;

    $offer = Offer::find($offerId);
    if (!$offer) {
        http_response_code(404);
        exit('Offre introuvable');
    }

    $skills = Offer::skillsForOffer($offerId);

    // ✅ Wish-list calculée côté controller (MVC clean)
    $inWish = false;
    $user = Auth::user();
    if ($user && Auth::role() === 'STUDENT') {
        $stmt = Db::pdo()->prepare("
            SELECT 1 FROM wishlists WHERE student_id = :s AND offer_id = :o LIMIT 1
        ");
        $stmt->execute(['s' => (int)$user['id'], 'o' => $offerId]);
        $inWish = (bool)$stmt->fetch();
    }

    View::render('offers/show', [
        'title' => (string)$offer['title'],
        'offer' => $offer,
        'skills' => $skills,
        'inWish' => $inWish, // ✅ envoyé à la vue
    ]);
}

    public function create(): void
    {
        Auth::requirePermission('SFx8');

        // Liste entreprises pour select
        $companies = Db::pdo()->query("SELECT id, name FROM companies ORDER BY name ASC")->fetchAll();
        $skills = Skill::all();

        View::render('offers/form', [
            'title' => 'Créer une offre',
            'action' => Http::url('/offers'),
            'companies' => $companies,
            'skills' => $skills,
            'selectedSkills' => [],
        ]);
    }

    public function store(): void
    {
        Auth::requirePermission('SFx8');

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        $companyId = (int)($_POST['company_id'] ?? 0);
        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $duration = (int)($_POST['duration_weeks'] ?? 0);
        $rem = trim((string)($_POST['remuneration_base'] ?? ''));
        $postedAt = (string)($_POST['posted_at'] ?? date('Y-m-d'));
        $skillIds = $_POST['skills'] ?? [];

        if ($companyId <= 0 || $title === '' || $description === '') {
            exit('Champs obligatoires manquants');
        }

        $stmt = Db::pdo()->prepare("
            INSERT INTO offers (company_id, title, description, duration_weeks, remuneration_base, posted_at)
            VALUES (:c, :t, :d, :dur, :r, :p)
        ");
        $stmt->execute([
            'c' => $companyId,
            't' => $title,
            'd' => $description,
            'dur' => $duration > 0 ? $duration : null,
            'r' => $rem ?: null,
            'p' => $postedAt,
        ]);

        $offerId = (int)Db::pdo()->lastInsertId();
        Offer::setSkills($offerId, is_array($skillIds) ? $skillIds : []);

        Http::redirect('/offers/' . $offerId);
    }

    public function edit(string $id): void
    {
        Auth::requirePermission('SFx9');

        $offer = Offer::find((int)$id);
        if (!$offer) {
            http_response_code(404);
            exit('Offre introuvable');
        }

        $companies = Db::pdo()->query("SELECT id, name FROM companies ORDER BY name ASC")->fetchAll();
        $skills = Skill::all();
        $selected = array_map(fn($s) => (int)$s['id'], Offer::skillsForOffer((int)$id));

        View::render('offers/form', [
            'title' => 'Modifier offre',
            'action' => Http::url('/offers/' . (int)$id . '/update'),
            'offer' => $offer,
            'companies' => $companies,
            'skills' => $skills,
            'selectedSkills' => $selected,
        ]);
    }

    public function update(string $id): void
    {
        Auth::requirePermission('SFx9');

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        $offerId = (int)$id;
        $companyId = (int)($_POST['company_id'] ?? 0);
        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $duration = (int)($_POST['duration_weeks'] ?? 0);
        $rem = trim((string)($_POST['remuneration_base'] ?? ''));
        $postedAt = (string)($_POST['posted_at'] ?? date('Y-m-d'));
        $skillIds = $_POST['skills'] ?? [];

        if ($companyId <= 0 || $title === '' || $description === '') {
            exit('Champs obligatoires manquants');
        }

        $stmt = Db::pdo()->prepare("
            UPDATE offers
            SET company_id = :c,
                title = :t,
                description = :d,
                duration_weeks = :dur,
                remuneration_base = :r,
                posted_at = :p
            WHERE id = :id
        ");
        $stmt->execute([
            'c' => $companyId,
            't' => $title,
            'd' => $description,
            'dur' => $duration > 0 ? $duration : null,
            'r' => $rem ?: null,
            'p' => $postedAt,
            'id' => $offerId,
        ]);

        Offer::setSkills($offerId, is_array($skillIds) ? $skillIds : []);

        Http::redirect('/offers/' . $offerId);
    }

    public function delete(string $id): void
    {
        Auth::requirePermission('SFx10');

        if (!Csrf::check($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            exit('CSRF invalide');
        }

        Db::pdo()->prepare("DELETE FROM offers WHERE id = :id")->execute(['id' => (int)$id]);
        Http::redirect('/offers');
    }

    public function stats(): void
{
    Auth::requirePermission('SFx11');

    View::render('offers/stats', [
        'title' => 'Statistiques des offres',
        'byDuration' => Offer::statsByDuration(),
        'totalOffers' => Offer::totalOffers(),
        'avgApplications' => Offer::averageApplications(),
        'topWishlist' => Offer::topWishlist(),
    ]);
}


public function wishlist(string $id): void
{
    \App\Core\Auth::requirePermission('SFx24');

    if (!\App\Core\Csrf::check($_POST['_csrf'] ?? null)) {
        http_response_code(403);
        exit('CSRF invalide');
    }

    $offerId = (int)$id;
    $user = \App\Core\Auth::user();
    $studentId = (int)$user['id'];

    $stmt = \App\Core\Db::pdo()->prepare("
        INSERT IGNORE INTO wishlists (student_id, offer_id)
        VALUES (:sid, :oid)
    ");
    $stmt->execute(['sid' => $studentId, 'oid' => $offerId]);

    \App\Core\Http::redirect('/offers/' . $offerId);
}



public function unwishlist(string $id): void
{
    \App\Core\Auth::requirePermission('SFx25');

    if (!\App\Core\Csrf::check($_POST['_csrf'] ?? null)) {
        http_response_code(403);
        exit('CSRF invalide');
    }

    $offerId = (int)$id;
    $user = \App\Core\Auth::user();
    $studentId = (int)$user['id'];

    $stmt = \App\Core\Db::pdo()->prepare("
        DELETE FROM wishlists
        WHERE student_id = :sid AND offer_id = :oid
    ");
    $stmt->execute(['sid' => $studentId, 'oid' => $offerId]);

    \App\Core\Http::redirect('/offers/' . $offerId);
}
}