<?php
declare(strict_types=1);

use App\Core\Http;

$items = $items ?? [];
$total = count($items);
?>

<section class="myapps-hero">
  <div class="myapps-hero-bg"></div>

  <div class="myapps-hero-content">
    <div class="myapps-hero-left">
      <span class="myapps-hero-badge">Suivi personnel</span>

      <h1 class="myapps-hero-title">
        Mes candidatures
        <span>et leur historique</span>
      </h1>

      <p class="myapps-hero-subtitle">
        Retrouve toutes les offres auxquelles tu as postulé,
        consulte rapidement l’entreprise concernée et accède à chaque fiche détail.
      </p>

      <div class="myapps-hero-stats">
        <div class="myapps-mini-stat">
          <span class="myapps-mini-label">Total</span>
          <span class="myapps-mini-value"><?= $total ?></span>
        </div>

        <div class="myapps-mini-stat">
          <span class="myapps-mini-label">Statut</span>
          <span class="myapps-mini-value"><?= $total > 0 ? 'Actif' : 'Aucune' ?></span>
        </div>

        <div class="myapps-mini-stat">
          <span class="myapps-mini-label">Vue</span>
          <span class="myapps-mini-value">Historique</span>
        </div>
      </div>
    </div>

    <div class="myapps-hero-right">
      <div class="myapps-dashboard-card">
        <div class="myapps-dashboard-head">
          <span class="myapps-dot red"></span>
          <span class="myapps-dot yellow"></span>
          <span class="myapps-dot green"></span>
        </div>

        <div class="myapps-dashboard-grid">
          <div class="myapps-dashboard-box">
            <span>Candidatures</span>
            <strong><?= $total ?></strong>
          </div>

          <div class="myapps-dashboard-box">
            <span>Accès</span>
            <strong>Étudiant</strong>
          </div>

          <div class="myapps-dashboard-box wide">
            <span>Suivi</span>
            <strong>Offres postulées</strong>
          </div>

          <div class="myapps-dashboard-bars">
            <span style="height:42%"></span>
            <span style="height:68%"></span>
            <span style="height:54%"></span>
            <span style="height:88%"></span>
            <span style="height:62%"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="myapps-toolbar">
  <div class="myapps-toolbar-left">
    <h2>Historique de candidatures</h2>
    <p class="muted"><?= $total ?> candidature(s) enregistrée(s)</p>
  </div>

  <div class="myapps-toolbar-actions">
    <a class="btn btn-primary" href="<?= Http::url('/offers') ?>">Explorer les offres</a>
  </div>
</div>

<?php if (empty($items)): ?>
  <div class="card myapps-empty">
    <div class="myapps-empty-icon">📭</div>
    <h2>Aucune candidature</h2>
    <p class="muted">Tu n’as pas encore postulé à une offre.</p>
    <div style="margin-top:14px;">
      <a class="btn btn-primary" href="<?= Http::url('/offers') ?>">Voir les offres</a>
    </div>
  </div>
<?php else: ?>
  <div class="myapps-grid">
    <?php foreach ($items as $a): ?>
      <article class="card myapp-card">
        <div class="myapp-top">
          <div class="myapp-company-badge">
            <?= htmlspecialchars((string)($a['company_name'] ?? 'Entreprise')) ?>
          </div>

          <div class="myapp-status-pill">
            Candidature envoyée
          </div>
        </div>

        <h3 class="myapp-title">
          <a href="<?= Http::url('/offers/' . (int)($a['offer_id'] ?? 0)) ?>">
            <?= htmlspecialchars((string)($a['title'] ?? '')) ?>
          </a>
        </h3>

        <div class="myapp-meta">
          <span>📅 <?= htmlspecialchars((string)($a['applied_at'] ?? '')) ?></span>
          <span>🏢 <?= htmlspecialchars((string)($a['company_name'] ?? '')) ?></span>
        </div>

        <div class="myapp-footer">
          <a class="btn btn-primary" href="<?= Http::url('/offers/' . (int)($a['offer_id'] ?? 0)) ?>">
            Voir l’offre
          </a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>