<?php
declare(strict_types=1);

use App\Core\Http;
use App\Core\Csrf;

$items = $items ?? [];
$total = count($items);
?>

<section class="wishlist-hero">
  <div class="wishlist-hero-bg"></div>

  <div class="wishlist-hero-content">
    <div class="wishlist-hero-left">
      <span class="wishlist-hero-badge">Liste d’intérêts</span>

      <h1 class="wishlist-hero-title">
        Ma wish-list
        <span>des offres favorites</span>
      </h1>

      <p class="wishlist-hero-subtitle">
        Retrouve les offres que tu as mises de côté pour les consulter plus tard,
        comparer les opportunités et candidater au bon moment.
      </p>

      <div class="wishlist-hero-stats">
        <div class="wishlist-mini-stat">
          <span class="wishlist-mini-label">Total</span>
          <span class="wishlist-mini-value"><?= $total ?></span>
        </div>

        <div class="wishlist-mini-stat">
          <span class="wishlist-mini-label">Statut</span>
          <span class="wishlist-mini-value"><?= $total > 0 ? 'Active' : 'Vide' ?></span>
        </div>

        <div class="wishlist-mini-stat">
          <span class="wishlist-mini-label">Usage</span>
          <span class="wishlist-mini-value">Suivi personnel</span>
        </div>
      </div>
    </div>

    <div class="wishlist-hero-right">
      <div class="wishlist-dashboard-card">
        <div class="wishlist-dashboard-head">
          <span class="wishlist-dot red"></span>
          <span class="wishlist-dot yellow"></span>
          <span class="wishlist-dot green"></span>
        </div>

        <div class="wishlist-dashboard-grid">
          <div class="wishlist-dashboard-box">
            <span>Offres sauvegardées</span>
            <strong><?= $total ?></strong>
          </div>

          <div class="wishlist-dashboard-box">
            <span>Vue</span>
            <strong>Favoris</strong>
          </div>

          <div class="wishlist-dashboard-box wide">
            <span>Objectif</span>
            <strong>Comparer avant de postuler</strong>
          </div>

          <div class="wishlist-dashboard-bars">
            <span style="height:42%"></span>
            <span style="height:72%"></span>
            <span style="height:56%"></span>
            <span style="height:88%"></span>
            <span style="height:64%"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="wishlist-toolbar">
  <div class="wishlist-toolbar-left">
    <h2>Offres enregistrées</h2>
    <p class="muted"><?= $total ?> offre(s) dans ta wish-list</p>
  </div>

  <div class="wishlist-toolbar-actions">
    <a class="btn btn-primary" href="<?= Http::url('/offers') ?>">+ Ajouter des offres</a>
  </div>
</div>

<?php if (empty($items)): ?>
  <div class="card wishlist-empty">
    <div class="wishlist-empty-icon">⭐</div>
    <h2>Liste vide</h2>
    <p class="muted">Ajoute des offres à ta wish-list pour les retrouver facilement.</p>
    <div style="margin-top:14px;">
      <a class="btn btn-primary" href="<?= Http::url('/offers') ?>">Explorer les offres</a>
    </div>
  </div>
<?php else: ?>
  <div class="wishlist-grid">
    <?php foreach ($items as $o): ?>
      <?php $oid = (int)($o['id'] ?? 0); ?>
      <article class="card wishlist-card">
        <div class="wishlist-card-top">
          <div class="wishlist-company-badge">
            <?= htmlspecialchars((string)($o['company_name'] ?? '')) ?>
          </div>

          <div class="wishlist-status-pill">
            Enregistrée
          </div>
        </div>

        <h3 class="wishlist-card-title">
          <a href="<?= Http::url('/offers/' . $oid) ?>">
            <?= htmlspecialchars((string)($o['title'] ?? '')) ?>
          </a>
        </h3>

        <p class="wishlist-card-desc">
          <?= htmlspecialchars(mb_strimwidth((string)($o['description'] ?? ''), 0, 170, '…')) ?>
        </p>

        <div class="wishlist-card-footer">
          <a class="btn btn-primary" href="<?= Http::url('/offers/' . $oid) ?>">Voir l’offre</a>

          <form method="post"
                action="<?= Http::url('/offers/' . $oid . '/unwishlist') ?>"
                onsubmit="return confirm('Retirer de la wish-list ?');"
                style="margin:0;">
            <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">
            <button class="btn btn-danger" type="submit">✖ Retirer</button>
          </form>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>