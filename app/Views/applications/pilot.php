<?php
declare(strict_types=1);

use App\Core\Http;

$items = $items ?? [];
$total = count($items);
?>

<section class="pilotapps-hero">
  <div class="pilotapps-hero-bg"></div>

  <div class="pilotapps-hero-content">
    <div class="pilotapps-hero-left">
      <span class="pilotapps-hero-badge">Suivi pédagogique</span>

      <h1 class="pilotapps-hero-title">
        Candidatures
        <span>de mes élèves</span>
      </h1>

      <p class="pilotapps-hero-subtitle">
        Visualise les candidatures soumises par les étudiants que tu encadres,
        consulte les offres ciblées et récupère les CV associés.
      </p>

      <div class="pilotapps-hero-stats">
        <div class="pilotapps-mini-stat">
          <span class="pilotapps-mini-label">Total</span>
          <span class="pilotapps-mini-value"><?= $total ?></span>
        </div>

        <div class="pilotapps-mini-stat">
          <span class="pilotapps-mini-label">Vue</span>
          <span class="pilotapps-mini-value">Suivi pilote</span>
        </div>

        <div class="pilotapps-mini-stat">
          <span class="pilotapps-mini-label">Accès</span>
          <span class="pilotapps-mini-value">Encadrement</span>
        </div>
      </div>
    </div>

    <div class="pilotapps-hero-right">
      <div class="pilotapps-dashboard-card">
        <div class="pilotapps-dashboard-head">
          <span class="pilotapps-dot red"></span>
          <span class="pilotapps-dot yellow"></span>
          <span class="pilotapps-dot green"></span>
        </div>

        <div class="pilotapps-dashboard-grid">
          <div class="pilotapps-dashboard-box">
            <span>Candidatures</span>
            <strong><?= $total ?></strong>
          </div>

          <div class="pilotapps-dashboard-box">
            <span>Rôle</span>
            <strong>Pilote</strong>
          </div>

          <div class="pilotapps-dashboard-box wide">
            <span>Objectif</span>
            <strong>Suivre les candidatures élèves</strong>
          </div>

          <div class="pilotapps-dashboard-bars">
            <span style="height:45%"></span>
            <span style="height:70%"></span>
            <span style="height:56%"></span>
            <span style="height:88%"></span>
            <span style="height:64%"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="pilotapps-toolbar">
  <div class="pilotapps-toolbar-left">
    <h2>Liste des candidatures</h2>
    <p class="muted"><?= $total ?> candidature(s) consultable(s)</p>
  </div>
</div>

<?php if (empty($items)): ?>
  <div class="card pilotapps-empty">
    <div class="pilotapps-empty-icon">📭</div>
    <h2>Aucune candidature</h2>
    <p class="muted">Aucune candidature n’est encore associée à tes élèves.</p>
  </div>
<?php else: ?>
  <div class="pilotapps-grid">
    <?php foreach ($items as $a): ?>
      <article class="card pilotapp-card">
        <div class="pilotapp-top">
          <div class="pilotapp-student-badge">
            <?= htmlspecialchars((string)($a['firstname'] . ' ' . $a['lastname'])) ?>
          </div>

          <div class="pilotapp-status-pill">
            Candidature envoyée
          </div>
        </div>

        <h3 class="pilotapp-title">
          <?= htmlspecialchars((string)($a['title'] ?? '')) ?>
        </h3>

        <div class="pilotapp-meta">
          <span>📧 <?= htmlspecialchars((string)($a['email'] ?? '')) ?></span>
          <span>🏢 <?= htmlspecialchars((string)($a['company_name'] ?? '')) ?></span>
          <span>📅 <?= htmlspecialchars((string)($a['applied_at'] ?? '')) ?></span>
        </div>

        <div class="pilotapp-footer">
          <a class="btn btn-primary" href="<?= Http::url('/applications/' . (int)$a['id'] . '/cv') ?>">
            Télécharger le CV
          </a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>