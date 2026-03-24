<?php
declare(strict_types=1);

use App\Core\Auth;
use App\Core\Http;
use App\Core\Permissions;

$role = Auth::role();
$q = (string)($q ?? '');
$page = (int)($page ?? 1);
$pages = (int)($pages ?? 1);
$items = $items ?? [];
$total = (int)($total ?? 0);
?>

<section class="offers-hero">
  <div class="offers-hero-bg"></div>

  <div class="offers-hero-content">
    <div class="offers-hero-left">
      <span class="offers-hero-badge">Catalogue des opportunités</span>
      <h1 class="offers-hero-title">Explore les offres de stage</h1>
      <p class="offers-hero-subtitle">
        Recherche, compare et consulte les offres disponibles selon ton profil,
        l’entreprise, la durée ou la rémunération.
      </p>

      <div class="offers-hero-stats">
        <div class="offers-mini-stat">
          <span class="offers-mini-label">Total</span>
          <span class="offers-mini-value"><?= $total ?></span>
        </div>

        <div class="offers-mini-stat">
          <span class="offers-mini-label">Recherche</span>
          <span class="offers-mini-value"><?= $q !== '' ? htmlspecialchars($q) : 'Aucune' ?></span>
        </div>

        <div class="offers-mini-stat">
          <span class="offers-mini-label">Page</span>
          <span class="offers-mini-value"><?= $page ?>/<?= max(1, $pages) ?></span>
        </div>
      </div>
    </div>

    <div class="offers-hero-right">
      <div class="offers-dashboard-card">
        <div class="offers-dashboard-head">
          <span class="offers-dot red"></span>
          <span class="offers-dot yellow"></span>
          <span class="offers-dot green"></span>
        </div>

        <div class="offers-dashboard-body">
          <div class="offers-dashboard-box">
            <span>Offres actives</span>
            <strong><?= $total ?></strong>
          </div>
          <div class="offers-dashboard-box">
            <span>Vue</span>
            <strong>Catalogue</strong>
          </div>
          <div class="offers-dashboard-bars">
            <span style="height:45%"></span>
            <span style="height:70%"></span>
            <span style="height:55%"></span>
            <span style="height:88%"></span>
            <span style="height:64%"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="offers-toolbar">
  <div class="offers-toolbar-left">
    <h2>Résultats</h2>
    <p class="muted"><?= $total ?> offre(s) trouvée(s)</p>
  </div>

  <div class="offers-toolbar-actions">
    <?php if (Permissions::can($role, 'SFx11')): ?>
      <a class="btn" href="<?= Http::url('/offers/stats') ?>">📊 Statistiques</a>
    <?php endif; ?>

    <?php if (Permissions::can($role, 'SFx8')): ?>
      <a class="btn btn-primary" href="<?= Http::url('/offers/create') ?>">+ Créer une offre</a>
    <?php endif; ?>
  </div>
</div>

<div class="card offers-search-card">
  <form method="get" action="<?= Http::url('/offers') ?>" class="offers-search-form">
    <div class="offers-search-input-wrap">
      <span class="offers-search-icon">🔎</span>
      <input
        class="input offers-search-input"
        type="text"
        name="q"
        value="<?= htmlspecialchars($q) ?>"
        placeholder="Rechercher une offre (data, cyber, dev, entreprise...)"
      >
    </div>

    <button class="btn btn-primary" type="submit">Rechercher</button>

    <?php if ($q !== ''): ?>
      <a class="btn" href="<?= Http::url('/offers') ?>">Réinitialiser</a>
    <?php endif; ?>
  </form>
</div>

<?php if (empty($items)): ?>
  <div class="card offers-empty">
    <div class="offers-empty-icon">📭</div>
    <h2>Aucune offre trouvée</h2>
    <p class="muted">Essaie un autre mot-clé ou réinitialise la recherche.</p>
    <div style="margin-top:14px;">
      <a class="btn" href="<?= Http::url('/offers') ?>">Voir toutes les offres</a>
    </div>
  </div>
<?php else: ?>
  <div class="offers-grid">
    <?php foreach ($items as $o): ?>
      <article class="card offer-list-card">
        <div class="offer-list-top">
          <div class="offer-list-meta">
            <span class="offer-list-company"><?= htmlspecialchars((string)($o['company_name'] ?? '')) ?></span>
            <?php if (!empty($o['posted_at'])): ?>
              <span class="offer-list-date"><?= htmlspecialchars((string)$o['posted_at']) ?></span>
            <?php endif; ?>
          </div>

          <div class="offer-list-status">
            <span class="offer-status-pill">Active</span>
          </div>
        </div>

        <h3 class="offer-list-title">
          <a href="<?= Http::url('/offers/' . (int)$o['id']) ?>">
            <?= htmlspecialchars((string)($o['title'] ?? '')) ?>
          </a>
        </h3>

        <p class="offer-list-desc">
          <?= htmlspecialchars(mb_strimwidth((string)($o['description'] ?? ''), 0, 210, '…')) ?>
        </p>

        <div class="offer-list-tags">
          <?php if (!empty($o['duration_weeks'])): ?>
            <span class="offer-list-chip">⏳ <?= (int)$o['duration_weeks'] ?> semaines</span>
          <?php endif; ?>

          <?php if (!empty($o['remuneration_base'])): ?>
            <span class="offer-list-chip">💶 <?= htmlspecialchars((string)$o['remuneration_base']) ?></span>
          <?php endif; ?>

          <span class="offer-list-chip subtle">📨 <?= (int)($o['applications_count'] ?? 0) ?> candidature(s)</span>
        </div>

        <div class="offer-list-footer">
          <a class="btn btn-primary" href="<?= Http::url('/offers/' . (int)$o['id']) ?>">Voir l’offre</a>

          <?php if (Permissions::can($role, 'SFx9')): ?>
            <a class="btn" href="<?= Http::url('/offers/' . (int)$o['id'] . '/edit') ?>">✏️ Modifier</a>
          <?php endif; ?>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php if ($pages > 1): ?>
  <div class="offers-pagination-wrap">
    <div class="pagination offers-pagination">
      <?php for ($p = 1; $p <= $pages; $p++): ?>
        <a class="<?= ($p === $page) ? 'active' : '' ?>"
           href="<?= Http::url('/offers') . '?q=' . urlencode($q) . '&page=' . $p ?>">
          <?= $p ?>
        </a>
      <?php endfor; ?>
    </div>
  </div>
<?php endif; ?>