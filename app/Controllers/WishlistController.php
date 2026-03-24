<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Db;
use App\Core\View;

final class WishlistController
{
    public function index(): void
    {
        Auth::requirePermission('SFx23');

        $user = Auth::user();
        $studentId = (int)$user['id'];

        $stmt = Db::pdo()->prepare("
            SELECT o.id, o.title, o.description, c.name AS company_name
            FROM wishlists w
            JOIN offers o ON o.id = w.offer_id
            JOIN companies c ON c.id = o.company_id
            WHERE w.student_id = :sid
            ORDER BY w.offer_id DESC
        ");
        $stmt->execute(['sid' => $studentId]);
        $items = $stmt->fetchAll();

        View::render('wishlist/index', [
            'title' => 'Ma wish-list',
            'items' => $items,
        ]);
    }
}