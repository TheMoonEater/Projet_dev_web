<?php
declare(strict_types=1);

use App\Core\Http;

$q = (string)($q ?? '');
$page = (int)($page ?? 1);
$pages = (int)($pages ?? 1);
$items = $items ?? [];
$total = (int)($total ?? 0);
?>

<section class="pilots-hero">
  <div class="pilots-hero-bg"></div>

  <div class="pilots-hero-content">
    <div class="pilots-hero-left">
      <span class="pilots-hero-badge">Gestion des pilotes</span>

      <h1 class="pilots-hero-title">
        Supervise les comptes
        <span>pilotes de promotion</span>
      </h1>

      <p class="pilots-hero-subtitle">
        Consulte, recherche et gère les comptes pilotes responsables
        du suivi des étudiants et des candidatures sur la plateforme.
      </p>

      <div class="pilots-hero-stats">
        <div class="pilots-mini-stat">
          <span class="pilots-mini-label">Total</span>
          <span class="pilots-mini-value"><?= $total ?></span>
        </div>

        <div class="pilots-mini-stat">
          <span class="pilots-mini-label">Recherche</span>
          <span class="pilots-mini-value"><?= $q !== '' ? htmlspecialchars($q) : 'Aucune' ?></span>
        </div>

        <div class="pilots-mini-stat">
          <span class="pilots-mini-label">Page</span>
          <span class="pilots-mini-value"><?= $page ?>/<?= max(1, $pages) ?></span>
        </div>
      </div>
    </div>

    <div class="pilots-hero-right">
      <div class="pilots-dashboard-card">
        <div class="pilots-dashboard-head">
          <span class="pilots-dot red"></span>
          <span class="pilots-dot yellow"></span>
          <span class="pilots-dot green"></span>
        </div>

        <div class="pilots-dashboard-grid">
          <div class="pilots-dashboard-box">
            <span>Pilotes</span>
            <strong><?= $total ?></strong>
          </div>

          <div class="pilots-dashboard-box">
            <span>Vue</span>
            <strong>Administration</strong>
          </div>

          <div class="pilots-dashboard-box wide">
            <span>Mission</span>
            <strong>Suivi & encadrement</strong>
          </div>

          <div class="pilots-dashboard-bars">
            <span style="height:45%"></span>
            <span style="height:68%"></span>
            <span style="height:58%"></span>
            <span style="height:86%"></span>
            <span style="height:62%"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="pilots-toolbar">
  <div class="pilots-toolbar-left">
    <h2>Comptes pilotes</h2>
    <p class="muted"><?= $total ?> pilote(s) trouvé(s)</p>
  </div>

  <div class="pilots-toolbar-actions">
    <a class="btn btn-primary" href="<?= Http::url('/pilots/create') ?>">+ Créer un pilote</a>
  </div>
</div>

<div class="card pilots-search-card">
  <form method="get" action="<?= Http::url('/pilots') ?>" class="pilots-search-form">
    <div class="pilots-search-input-wrap">
      <span class="pilots-search-icon">👤</span>
      <input
        class="input pilots-search-input"
        type="text"
        name="q"
        value="<?= htmlspecialchars($q) ?>"
        placeholder="Rechercher un pilote (nom, prénom, email...)"
      >
    </div>

    <button class="btn btn-primary" type="submit">Rechercher</button>

    <?php if ($q !== ''): ?>
      <a class="btn" href="<?= Http::url('/pilots') ?>">Réinitialiser</a>
    <?php endif; ?>
  </form>
</div>

<?php if (empty($items)): ?>
  <div class="card pilots-empty">
    <div class="pilots-empty-icon">👨‍🏫</div>
    <h2>Aucun résultat</h2>
    <p class="muted">Essaie un autre mot-clé ou crée un nouveau pilote.</p>
    <div style="margin-top:14px;">
      <a class="btn" href="<?= Http::url('/pilots/create') ?>">Créer un pilote</a>
    </div>
  </div>
<?php else: ?>
  <div class="pilots-grid">
    <?php foreach ($items as $p): ?>
      <?php
        $fullName = trim((string)(($p['lastname'] ?? '') . ' ' . ($p['firstname'] ?? '')));
        $initial = strtoupper(substr($fullName !== '' ? $fullName : '?', 0, 1));
      ?>
      <article class="card pilot-list-card">
        <div class="pilot-list-top">
          <div class="pilot-list-avatar"><?= $initial ?></div>

          <div class="pilot-list-head">
            <h3 class="pilot-list-title">
              <a href="<?= Http::url('/pilots/' . (int)$p['id']) ?>">
                <?= htmlspecialchars($fullName) ?>
              </a>
            </h3>

            <div class="pilot-list-contact">
              <?= htmlspecialchars((string)($p['email'] ?? '')) ?>
            </div>
          </div>
        </div>

        <div class="pilot-list-tags">
          <span class="pilot-list-chip">Pilote de promotion</span>
          <span class="pilot-list-chip subtle">Compte encadrant</span>
        </div>

        <div class="pilot-list-footer">
          <a class="btn btn-primary" href="<?= Http::url('/pilots/' . (int)$p['id']) ?>">Voir la fiche</a>
          <a class="btn" href="<?= Http::url('/pilots/' . (int)$p['id'] . '/edit') ?>">✏️ Modifier</a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php if ($pages > 1): ?>
  <div class="pilots-pagination-wrap">
    <div class="pagination pilots-pagination">
      <?php for ($p = 1; $p <= $pages; $p++): ?>
        <a class="<?= ($p === $page) ? 'active' : '' ?>"
           href="<?= Http::url('/pilots') . '?q=' . urlencode($q) . '&page=' . $p ?>">
          <?= $p ?>
        </a>
      <?php endfor; ?>
    </div>
  </div>
<?php endif; ?>