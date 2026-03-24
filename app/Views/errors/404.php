<?php
declare(strict_types=1);

use App\Core\Http;
use App\Core\Auth;

$user = Auth::user();
?>

<div class="container" style="max-width:780px;">
  <div class="card" style="margin-top:22px;">
    <h1>404 — Page introuvable</h1>
    <p class="muted">
      La page demandée n’existe pas ou a été déplacée.
    </p>

    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:14px;">
      <a class="btn btn-primary" href="<?= Http::url('/') ?>">Accueil</a>
      <a class="btn" href="<?= Http::url('/offers') ?>">Offres</a>
      <a class="btn" href="<?= Http::url('/companies') ?>">Entreprises</a>

      <?php if (!$user): ?>
        <a class="btn" href="<?= Http::url('/login') ?>">Connexion</a>
      <?php else: ?>
        <a class="btn" href="<?= Http::url('/wishlist') ?>">Wish-list</a>
      <?php endif; ?>
    </div>

    <div style="margin-top:16px;" class="muted">
      Astuce : vérifie l’URL, ou repasse par le menu.
    </div>
  </div>
</div>