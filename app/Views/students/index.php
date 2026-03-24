<?php
declare(strict_types=1);

use App\Core\Http;

$q = (string)($q ?? '');
$page = (int)($page ?? 1);
$pages = (int)($pages ?? 1);
$items = $items ?? [];
$total = (int)($total ?? 0);
?>

<section class="students-hero">
  <div class="students-hero-bg"></div>

  <div class="students-hero-content">
    <div class="students-hero-left">
      <span class="students-hero-badge">Gestion des étudiants</span>

      <h1 class="students-hero-title">
        Consulte et gère
        <span>les comptes étudiants</span>
      </h1>

      <p class="students-hero-subtitle">
        Recherche, consulte et administre les comptes étudiants afin de suivre
        leur présence sur la plateforme et leur parcours de recherche de stage.
      </p>

      <div class="students-hero-stats">
        <div class="students-mini-stat">
          <span class="students-mini-label">Total</span>
          <span class="students-mini-value"><?= $total ?></span>
        </div>

        <div class="students-mini-stat">
          <span class="students-mini-label">Recherche</span>
          <span class="students-mini-value"><?= $q !== '' ? htmlspecialchars($q) : 'Aucune' ?></span>
        </div>

        <div class="students-mini-stat">
          <span class="students-mini-label">Page</span>
          <span class="students-mini-value"><?= $page ?>/<?= max(1, $pages) ?></span>
        </div>
      </div>
    </div>

    <div class="students-hero-right">
      <div class="students-dashboard-card">
        <div class="students-dashboard-head">
          <span class="students-dot red"></span>
          <span class="students-dot yellow"></span>
          <span class="students-dot green"></span>
        </div>

        <div class="students-dashboard-grid">
          <div class="students-dashboard-box">
            <span>Étudiants</span>
            <strong><?= $total ?></strong>
          </div>

          <div class="students-dashboard-box">
            <span>Vue</span>
            <strong>Administration</strong>
          </div>

          <div class="students-dashboard-box wide">
            <span>Objectif</span>
            <strong>Suivi des comptes</strong>
          </div>

          <div class="students-dashboard-bars">
            <span style="height:46%"></span>
            <span style="height:64%"></span>
            <span style="height:58%"></span>
            <span style="height:88%"></span>
            <span style="height:70%"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="students-toolbar">
  <div class="students-toolbar-left">
    <h2>Comptes étudiants</h2>
    <p class="muted"><?= $total ?> étudiant(s) trouvé(s)</p>
  </div>

  <div class="students-toolbar-actions">
    <a class="btn btn-primary" href="<?= Http::url('/students/create') ?>">+ Créer un étudiant</a>
  </div>
</div>

<div class="card students-search-card">
  <form method="get" action="<?= Http::url('/students') ?>" class="students-search-form">
    <div class="students-search-input-wrap">
      <span class="students-search-icon">🎓</span>
      <input
        class="input students-search-input"
        type="text"
        name="q"
        value="<?= htmlspecialchars($q) ?>"
        placeholder="Rechercher un étudiant (nom, prénom, email...)"
      >
    </div>

    <button class="btn btn-primary" type="submit">Rechercher</button>

    <?php if ($q !== ''): ?>
      <a class="btn" href="<?= Http::url('/students') ?>">Réinitialiser</a>
    <?php endif; ?>
  </form>
</div>

<?php if (empty($items)): ?>
  <div class="card students-empty">
    <div class="students-empty-icon">🎓</div>
    <h2>Aucun résultat</h2>
    <p class="muted">Essaie un autre mot-clé ou crée un nouveau compte étudiant.</p>
    <div style="margin-top:14px;">
      <a class="btn" href="<?= Http::url('/students/create') ?>">Créer un étudiant</a>
    </div>
  </div>
<?php else: ?>
  <div class="students-grid">
    <?php foreach ($items as $s): ?>
      <?php
        $fullName = trim((string)(($s['lastname'] ?? '') . ' ' . ($s['firstname'] ?? '')));
        $initial = strtoupper(substr($fullName !== '' ? $fullName : '?', 0, 1));
      ?>
      <article class="card student-list-card">
        <div class="student-list-top">
          <div class="student-list-avatar"><?= $initial ?></div>

          <div class="student-list-head">
            <h3 class="student-list-title">
              <a href="<?= Http::url('/students/' . (int)$s['id']) ?>">
                <?= htmlspecialchars($fullName) ?>
              </a>
            </h3>

            <div class="student-list-contact">
              <?= htmlspecialchars((string)($s['email'] ?? '')) ?>
            </div>
          </div>
        </div>

        <div class="student-list-tags">
          <span class="student-list-chip">Compte étudiant</span>
          <span class="student-list-chip subtle">Accès candidature</span>
        </div>

        <div class="student-list-footer">
          <a class="btn btn-primary" href="<?= Http::url('/students/' . (int)$s['id']) ?>">Voir la fiche</a>
          <a class="btn" href="<?= Http::url('/students/' . (int)$s['id'] . '/edit') ?>">✏️ Modifier</a>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php if ($pages > 1): ?>
  <div class="students-pagination-wrap">
    <div class="pagination students-pagination">
      <?php for ($p = 1; $p <= $pages; $p++): ?>
        <a class="<?= ($p === $page) ? 'active' : '' ?>"
           href="<?= Http::url('/students') . '?q=' . urlencode($q) . '&page=' . $p ?>">
          <?= $p ?>
        </a>
      <?php endfor; ?>
    </div>
  </div>
<?php endif; ?>