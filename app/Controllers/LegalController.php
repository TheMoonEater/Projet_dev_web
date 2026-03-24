<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

final class LegalController
{
    public function mentions(): void
    {
        View::render('legal/mentions', [
            'title' => 'Mentions légales'
        ]);
    }
}