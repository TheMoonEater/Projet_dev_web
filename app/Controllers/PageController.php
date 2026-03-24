<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

final class PageController
{
    public function home(): void
    {
        View::render('pages/home', [
            'title' => 'Accueil'
        ]);
    }
}