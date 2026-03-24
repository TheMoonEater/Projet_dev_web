<?php
declare(strict_types=1);

use App\Core\Http;
use App\Core\Auth;

$user = Auth::user();
$role = Auth::role();
?>

<div class="container" style="max-width:720px;">
  <div class="card" style="margin-top:22px;">
    <h1>403 — Accès refusé</h1>
    <p class="muted">
      Tu n'as pas la permission d’accéder à cette page.
      <?php if ($user): ?>
        (Rôle actuel : <strong><?= htmlspecialchars((string)$role) ?></strong>)
      <?php endif; ?>
    </p>

    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:14px;">
      <a class="btn" href="<?= Http::url('/') ?>">Accueil</a>
      <a class="btn" href="<?= Http::url('/offers') ?>">Offres</a>

      <?php if (!$user): ?>
        <a class="btn btn-primary" href="<?= Http::url('/login') ?>">Se connecter</a>
      <?php else: ?>
        <form method="post" action="<?= Http::url('/logout') ?>">
          <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::token() ?>">
          <button class="btn btn-danger" type="submit">Se déconnecter</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>