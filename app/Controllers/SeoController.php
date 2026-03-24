<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Db;

final class SeoController
{
    public function sitemap(): void
    {
        header('Content-Type: application/xml');

        $offers = Db::pdo()->query("SELECT id FROM offers")->fetchAll();
        $companies = Db::pdo()->query("SELECT id FROM companies")->fetchAll();

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        echo '<url><loc>http://localhost/Projet_MaPrincesse/public/</loc></url>';
        echo '<url><loc>http://localhost/Projet_MaPrincesse/public/offers</loc></url>';
        echo '<url><loc>http://localhost/Projet_MaPrincesse/public/companies</loc></url>';

        foreach ($offers as $o) {
            echo '<url><loc>http://localhost/Projet_MaPrincesse/public/offers/'.$o['id'].'</loc></url>';
        }

        foreach ($companies as $c) {
            echo '<url><loc>http://localhost/Projet_MaPrincesse/public/companies/'.$c['id'].'</loc></url>';
        }

        echo '</urlset>';
        exit;
    }
}