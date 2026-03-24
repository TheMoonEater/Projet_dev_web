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

<section class="companies-hero">
  <div class="companies-hero-bg"></div>

  <div class="companies-hero-content">
    <div class="companies-hero-left">
      <span class="companies-hero-badge">Annuaire des entreprises</span>

      <h1 class="companies-hero-title">
        Explore les entreprises
        <span>référencées sur la plateforme</span>
      </h1>

      <p class="companies-hero-subtitle">
        Consulte les fiches entreprises, découvre leurs contacts, leurs évaluations
        et accède rapidement aux informations utiles pour la recherche de stage.
      </p>

      <div class="companies-hero-stats">
        <div class="companies-mini-stat">
          <span class="companies-mini-label">Total</span>
          <span class="companies-mini-value"><?= $total ?></span>
        </div>

        <div class="companies-mini-stat">
          <span class="companies-mini-label">Recherche</span>
          <span class="companies-mini-value"><?= $q !== '' ? htmlspecialchars($q) : 'Aucune' ?></span>
        </div>

        <div class="companies-mini-stat">
          <span class="companies-mini-label">Page</span>
          <span class="companies-mini-value"><?= $page ?>/<?= max(1, $pages) ?></span>
        </div>
      </div>
    </div>

    <div class="companies-hero-right">
      <div class="companies-dashboard-card">
        <div class="companies-dashboard-head">
          <span class="companies-dot red"></span>
          <span class="companies-dot yellow"></span>
          <span class="companies-dot green"></span>
        </div>

        <div class="companies-dashboard-grid">
          <div class="companies-dashboard-box">
            <span>Entreprises</span>
            <strong><?= $total ?></strong>
          </div>

          <div class="companies-dashboard-box">
            <span>Vue</span>
            <strong>Annuaire</strong>
          </div>

          <div class="companies-dashboard-box wide">
            <span>Contenu</span>
            <strong>Contacts + évaluations</strong>
          </div>

          <div class="companies-dashboard-bars">
            <span style="height:42%"></span>
            <span style="height:72%"></span>
            <span style="height:58%"></span>
            <span style="height:90%"></span>
            <span style="height:66%"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="companies-toolbar">
  <div class="companies-toolbar-left">
    <h2>Entreprises référencées</h2>
    <p class="muted"><?= $total ?> entreprise(s) trouvée(s)</p>
  </div>

  <div class="companies-toolbar-actions">
    <?php if (Permissions::can($role, 'SFx3')): ?>
      <a class="btn btn-primary" href="<?= Http::url('/companies/create') ?>">+ Créer une entreprise</a>
    <?php endif; ?>
  </div>
</div>

<div class="card companies-search-card">
  <form method="get" action="<?= Http::url('/companies') ?>" class="companies-search-form">
    <div class="companies-search-input-wrap">
      <span class="companies-search-icon">🏢</span>
      <input
        class="input companies-search-input"
        type="text"
        name="q"
        value="<?= htmlspecialchars($q) ?>"
        placeholder="Rechercher une entreprise (nom, email, téléphone...)"
      >
    </div>

    <button class="btn btn-primary" type="submit">Rechercher</button>

    <?php if ($q !== ''): ?>
      <a class="btn" href="<?= Http::url('/companies') ?>">Réinitialiser</a>
    <?php endif; ?>
  </form>
</div>

<?php if (empty($items)): ?>
  <div class="card companies-empty">
    <div class="companies-empty-icon">🏢</div>
    <h2>Aucune entreprise trouvée</h2>
    <p class="muted">Essaie un autre mot-clé ou réinitialise la recherche.</p>
    <div style="margin-top:14px;">
      <a class="btn" href="<?= Http::url('/companies') ?>">Voir toutes les entreprises</a>
    </div>
  </div>
<?php else: ?>
  <div class="companies-grid">
    <?php foreach ($items as $c): ?>
      <article class="card company-list-card">
        <div class="company-list-top">
          <div class="company-list-avatar">
            <?= strtoupper(substr((string)($c['name'] ?? '?'), 0, 1)) ?>
          </div>

          <div class="company-list-head">
            <h3 class="company-list-title">
              <a href="<?= Http::url('/companies/' . (int)$c['id']) ?>">
                <?= htmlspecialchars((string)($c['name'] ?? '')) ?>
              </a>
            </h3>

            <div class="company-list-contact">
              <?php if (!empty($c['contact_email'])): ?>
                <span><?= htmlspecialchars((string)$c['contact_email']) ?></span>
              <?php endif; ?>

              <?php if (!empty($c['contact_phone'])): ?>
                <span>• <?= htmlspecialchars((string)$c['contact_phone']) ?></span>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <?php if (!empty($c['description'])): ?>
          <p class="company-list-desc">
            <?= htmlspecialchars(mb_strimwidth((string)$c['description'], 0, 190, '…')) ?>
          </p>
        <?php else: ?>
          <p class="company-list-desc muted">Aucune description renseignée.</p>
        <?php endif; ?>

        <div class="company-list-tags">
          <?php if (!empty($c['rating_avg'])): ?>
            <span class="company-list-chip">⭐ <?= number_format((float)$c['rating_avg'], 1) ?>/5</span>
          <?php endif; ?>

          <?php if (!empty($c['rating_count'])): ?>
            <span class="company-list-chip subtle"><?= (int)$c['rating_count'] ?> avis</span>
          <?php endif; ?>

          <?php if (empty($c['rating_avg']) && empty($c['rating_count'])): ?>
            <span class="company-list-chip subtle">Pas encore d’évaluation</span>
          <?php endif; ?>
        </div>

        <div class="company-list-footer">
          <a class="btn btn-primary" href="<?= Http::url('/companies/' . (int)$c['id']) ?>">Voir la fiche</a>

          <?php if (Permissions::can($role, 'SFx4')): ?>
            <a class="btn" href="<?= Http::url('/companies/' . (int)$c['id'] . '/edit') ?>">✏️ Modifier</a>
          <?php endif; ?>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php if ($pages > 1): ?>
  <div class="companies-pagination-wrap">
    <div class="pagination companies-pagination">
      <?php for ($p = 1; $p <= $pages; $p++): ?>
        <a class="<?= ($p === $page) ? 'active' : '' ?>"
           href="<?= Http::url('/companies') . '?q=' . urlencode($q) . '&page=' . $p ?>">
          <?= $p ?>
        </a>
      <?php endfor; ?>
    </div>
  </div>
<?php endif; ?>