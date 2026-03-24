<?php
declare(strict_types=1);

use App\Core\Http;

$byDuration = $byDuration ?? [];
$totalOffers = (int)($totalOffers ?? 0);
$avgApplications = (float)($avgApplications ?? 0);
$topWishlist = $topWishlist ?? [];

$maxDuration = 0;
foreach ($byDuration as $r) {
    $maxDuration = max($maxDuration, (int)$r['total']);
}

$maxWish = 0;
foreach ($topWishlist as $r) {
    $maxWish = max($maxWish, (int)$r['total']);
}

$sumDuration = 0;
foreach ($byDuration as $r) {
    $sumDuration += (int)$r['total'];
}

$topCategory = null;
$topCategoryValue = 0;
foreach ($byDuration as $r) {
    if ((int)$r['total'] > $topCategoryValue) {
        $topCategoryValue = (int)$r['total'];
        $topCategory = (string)$r['category'];
    }
}

$dominantPercent = $sumDuration > 0 ? round(($topCategoryValue / $sumDuration) * 100) : 0;
?>

<section class="stats-hero">
  <div class="stats-hero-bg"></div>

  <div class="stats-hero-content">
    <div class="stats-hero-left">
      <span class="stats-hero-badge">Dashboard analytique</span>

      <h1 class="stats-hero-title">
        Analyse visuelle
        <span>des offres de stage</span>
      </h1>

      <p class="stats-hero-subtitle">
        Une vue synthétique pour comprendre la répartition des offres,
        l’attractivité des annonces et les préférences des étudiants.
      </p>

      <div class="stats-hero-pills">
        <span class="stats-pill">📊 KPI dynamiques</span>
        <span class="stats-pill">📈 Graphiques natifs</span>
        <span class="stats-pill">🧠 Insights automatiques</span>
      </div>
    </div>

    <div class="stats-hero-right">
      <div class="stats-panel">
        <div class="stats-panel-head">
          <span class="stats-dot red"></span>
          <span class="stats-dot yellow"></span>
          <span class="stats-dot green"></span>
        </div>

        <div class="stats-panel-grid">
          <div class="stats-panel-box">
            <span>Total</span>
            <strong><?= $totalOffers ?></strong>
          </div>

          <div class="stats-panel-box">
            <span>Moyenne</span>
            <strong><?= number_format($avgApplications, 2) ?></strong>
          </div>

          <div class="stats-panel-box wide">
            <span>Catégorie dominante</span>
            <strong><?= htmlspecialchars((string)($topCategory ?? '—')) ?></strong>
          </div>

          <div class="stats-panel-bars">
            <span style="height:42%"></span>
            <span style="height:60%"></span>
            <span style="height:78%"></span>
            <span style="height:54%"></span>
            <span style="height:90%"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="stats-kpis">
  <div class="stats-kpi-card">
    <div class="stats-kpi-top">
      <span class="stats-kpi-label">Total offres</span>
      <span class="stats-kpi-icon">📦</span>
    </div>
    <div class="stats-kpi-value"><?= $totalOffers ?></div>
    <div class="stats-kpi-text">Nombre total d’offres disponibles en base</div>
  </div>

  <div class="stats-kpi-card">
    <div class="stats-kpi-top">
      <span class="stats-kpi-label">Moyenne candidatures</span>
      <span class="stats-kpi-icon">📨</span>
    </div>
    <div class="stats-kpi-value"><?= number_format($avgApplications, 2) ?></div>
    <div class="stats-kpi-text">Attractivité moyenne des offres publiées</div>
  </div>

  <div class="stats-kpi-card">
    <div class="stats-kpi-top">
      <span class="stats-kpi-label">Catégorie dominante</span>
      <span class="stats-kpi-icon">🏆</span>
    </div>
    <div class="stats-kpi-value" style="font-size:28px;">
      <?= htmlspecialchars((string)($topCategory ?? '—')) ?>
    </div>
    <div class="stats-kpi-text"><?= $dominantPercent ?>% des offres</div>
  </div>

  <div class="stats-kpi-card">
    <div class="stats-kpi-top">
      <span class="stats-kpi-label">Top wish-list</span>
      <span class="stats-kpi-icon">⭐</span>
    </div>
    <div class="stats-kpi-value" style="font-size:22px;">
      <?= !empty($topWishlist) ? htmlspecialchars((string)$topWishlist[0]['title']) : '—' ?>
    </div>
    <div class="stats-kpi-text">
      <?= !empty($topWishlist) ? (int)$topWishlist[0]['total'] . ' ajout(s)' : 'Aucune donnée' ?>
    </div>
  </div>
</section>

<div class="stats-grid-two">
  <!-- Répartition -->
  <div class="card stats-card">
    <div class="stats-card-head">
      <div>
        <span class="stats-section-badge">Répartition</span>
        <h2>Offres par durée</h2>
      </div>
      <span class="stats-card-mini">Comparaison par catégorie</span>
    </div>

    <?php if (empty($byDuration)): ?>
      <p class="muted">Aucune donnée disponible.</p>
    <?php else: ?>
      <div class="stats-bars">
        <?php foreach ($byDuration as $row):
          $val = (int)$row['total'];
          $w = ($maxDuration > 0) ? round(($val / $maxDuration) * 100) : 0;
        ?>
          <div class="stats-bar-row">
            <div class="stats-bar-label"><?= htmlspecialchars((string)$row['category']) ?></div>
            <div class="stats-bar-track">
              <div class="stats-bar-fill" style="width: <?= $w ?>%"></div>
            </div>
            <div class="stats-bar-value"><?= $val ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Donut -->
  <div class="card stats-card">
    <div class="stats-card-head">
      <div>
        <span class="stats-section-badge">Distribution</span>
        <h2>Part des catégories</h2>
      </div>
      <span class="stats-card-mini">Vue globale</span>
    </div>

    <?php if (empty($byDuration) || $sumDuration === 0): ?>
      <p class="muted">Aucune donnée disponible.</p>
    <?php else: ?>
      <?php $offset = 25; ?>

      <div class="stats-donut-layout">
        <div class="stats-donut-wrap">
          <svg class="stats-donut" viewBox="0 0 42 42" width="230" height="230" aria-label="Donut catégories">
            <circle class="stats-donut-ring" cx="21" cy="21" r="15.915" fill="transparent" stroke-width="6"></circle>

            <?php foreach ($byDuration as $i => $row):
              $val = (int)$row['total'];
              $pct = ($sumDuration > 0) ? ($val / $sumDuration) * 100 : 0;
              $hue = ($i * 65) % 360;
            ?>
              <circle
                class="stats-donut-segment"
                cx="21" cy="21" r="15.915"
                fill="transparent"
                stroke="hsl(<?= (int)$hue ?>, 78%, 58%)"
                stroke-width="6"
                stroke-dasharray="<?= $pct ?> <?= 100 - $pct ?>"
                stroke-dashoffset="<?= $offset ?>"
              ></circle>
              <?php $offset -= $pct; ?>
            <?php endforeach; ?>
          </svg>

          <div class="stats-donut-center">
            <span class="muted" style="font-size:12px;">Dominante</span>
            <strong><?= htmlspecialchars((string)($topCategory ?? '—')) ?></strong>
            <span class="muted" style="font-size:12px;"><?= $dominantPercent ?>%</span>
          </div>
        </div>

        <div class="stats-legend">
          <?php foreach ($byDuration as $i => $row):
            $val = (int)$row['total'];
            $pct = ($sumDuration > 0) ? round(($val / $sumDuration) * 100) : 0;
            $hue = ($i * 65) % 360;
          ?>
            <div class="stats-legend-item">
              <span class="stats-legend-dot" style="background:hsl(<?= (int)$hue ?>,78%,58%)"></span>
              <span><?= htmlspecialchars((string)$row['category']) ?></span>
              <strong><?= $pct ?>%</strong>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<div class="card stats-card" style="margin-top:18px;">
  <div class="stats-card-head">
    <div>
      <span class="stats-section-badge">Classement</span>
      <h2>Top offres ajoutées en wish-list</h2>
    </div>
    <span class="stats-card-mini">Préférences des étudiants</span>
  </div>

  <?php if (empty($topWishlist)): ?>
    <p class="muted">Pas encore de données wish-list.</p>
  <?php else: ?>
    <div class="stats-rank-list">
      <?php foreach ($topWishlist as $idx => $row):
        $val = (int)$row['total'];
        $w = ($maxWish > 0) ? round(($val / $maxWish) * 100) : 0;
      ?>
        <div class="stats-rank-row">
          <div class="stats-rank-pos">#<?= $idx + 1 ?></div>
          <div class="stats-rank-title"><?= htmlspecialchars((string)$row['title']) ?></div>
          <div class="stats-rank-track">
            <div class="stats-rank-fill" style="width: <?= $w ?>%"></div>
          </div>
          <div class="stats-rank-value"><?= $val ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<div class="stats-insights-grid">
  <div class="card stats-insight-card">
    <span class="stats-section-badge">Insight #1</span>
    <h3>Catégorie dominante</h3>
    <p>
      La catégorie la plus fréquente est
      <strong><?= htmlspecialchars((string)($topCategory ?? '—')) ?></strong>
      avec <strong><?= $topCategoryValue ?></strong> offre(s).
    </p>
  </div>

  <div class="card stats-insight-card">
    <span class="stats-section-badge">Insight #2</span>
    <h3>Volume global</h3>
    <p>
      La plateforme contient actuellement
      <strong><?= $totalOffers ?></strong> offre(s) de stage disponibles.
    </p>
  </div>

  <div class="card stats-insight-card">
    <span class="stats-section-badge">Insight #3</span>
    <h3>Attractivité</h3>
    <p>
      L’attractivité moyenne observée est de
      <strong><?= number_format($avgApplications, 2) ?></strong>
      candidature(s) par offre.
    </p>
  </div>
</div>

<div class="stats-back-row">
  <a class="btn" href="<?= Http::url('/offers') ?>">← Retour aux offres</a>
</div>