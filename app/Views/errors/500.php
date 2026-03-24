<?php
declare(strict_types=1);

use App\Core\Http;

$message = (string)($message ?? 'Une erreur interne est survenue.');
?>

<div class="container" style="max-width:780px;">
  <div class="card" style="margin-top:22px;">
    <h1>500 — Erreur serveur</h1>
    <p class="muted"><?= htmlspecialchars($message) ?></p>

    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:14px;">
      <a class="btn btn-primary" href="<?= Http::url('/') ?>">Accueil</a>
      <a class="btn" href="<?= Http::url('/offers') ?>">Offres</a>
      <a class="btn" href="<?= Http::url('/companies') ?>">Entreprises</a>
    </div>

    <div style="margin-top:16px;" class="muted">
      Si le problème persiste, vérifier les logs Apache/PHP.
    </div>
  </div>
</div>