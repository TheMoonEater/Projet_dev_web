<?php
declare(strict_types=1);

use App\Core\Http;
use App\Core\Csrf;

$students = $students ?? [];
$pilots = $pilots ?? [];
$total = count($students);
?>

<section class="assign-hero">
  <div class="assign-hero-bg"></div>

  <div class="assign-hero-content">
    <div class="assign-hero-left">
      <span class="assign-hero-badge">Affectation pédagogique</span>

      <h1 class="assign-hero-title">
        Lier les étudiants
        <span>à leurs pilotes</span>
      </h1>

      <p class="assign-hero-subtitle">
        Associe chaque étudiant à un pilote de promotion pour permettre
        le suivi des candidatures, l’encadrement et la supervision pédagogique.
      </p>

      <div class="assign-hero-stats">
        <div class="assign-mini-stat">
          <span class="assign-mini-label">Étudiants</span>
          <span class="assign-mini-value"><?= $total ?></span>
        </div>

        <div class="assign-mini-stat">
          <span class="assign-mini-label">Pilotes</span>
          <span class="assign-mini-value"><?= count($pilots) ?></span>
        </div>

        <div class="assign-mini-stat">
          <span class="assign-mini-label">Usage</span>
          <span class="assign-mini-value">Suivi</span>
        </div>
      </div>
    </div>

    <div class="assign-hero-right">
      <div class="assign-dashboard-card">
        <div class="assign-dashboard-head">
          <span class="assign-dot red"></span>
          <span class="assign-dot yellow"></span>
          <span class="assign-dot green"></span>
        </div>

        <div class="assign-dashboard-grid">
          <div class="assign-dashboard-box">
            <span>Étudiants</span>
            <strong><?= $total ?></strong>
          </div>

          <div class="assign-dashboard-box">
            <span>Pilotes</span>
            <strong><?= count($pilots) ?></strong>
          </div>

          <div class="assign-dashboard-box wide">
            <span>Fonction</span>
            <strong>Affectation & supervision</strong>
          </div>

          <div class="assign-dashboard-bars">
            <span style="height:42%"></span>
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

<div class="assign-toolbar">
  <div class="assign-toolbar-left">
    <h2>Affectations étudiants / pilotes</h2>
    <p class="muted"><?= $total ?> étudiant(s) gérable(s)</p>
  </div>
</div>

<?php if (empty($students)): ?>
  <div class="card assign-empty">
    <div class="assign-empty-icon">🎓</div>
    <h2>Aucun étudiant</h2>
    <p class="muted">Aucun compte étudiant disponible pour l’affectation.</p>
  </div>
<?php else: ?>
  <div class="assign-grid">
    <?php foreach ($students as $s): ?>
      <?php
        $studentName = trim((string)(($s['lastname'] ?? '') . ' ' . ($s['firstname'] ?? '')));
        $pilotName = trim((string)(($s['pilot_lastname'] ?? '') . ' ' . ($s['pilot_firstname'] ?? '')));
      ?>
      <article class="card assign-card">
        <div class="assign-card-top">
          <div>
            <h3 class="assign-student-name"><?= htmlspecialchars($studentName) ?></h3>
            <div class="assign-student-email"><?= htmlspecialchars((string)($s['email'] ?? '')) ?></div>
          </div>

          <div>
            <?php if (!empty($s['pilot_id'])): ?>
              <span class="assign-current-pill">
                Pilote : <?= htmlspecialchars($pilotName) ?>
              </span>
            <?php else: ?>
              <span class="assign-current-pill subtle">Aucun pilote affecté</span>
            <?php endif; ?>
          </div>
        </div>

        <form method="post" action="<?= Http::url('/pilot-students/assign') ?>" class="assign-form">
          <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">
          <input type="hidden" name="student_id" value="<?= (int)$s['id'] ?>">

          <label class="assign-label">Choisir un pilote</label>
          <select name="pilot_id" class="input" required>
            <option value="">— Sélectionner —</option>
            <?php foreach ($pilots as $p): ?>
              <option
                value="<?= (int)$p['id'] ?>"
                <?= ((int)($s['pilot_id'] ?? 0) === (int)$p['id']) ? 'selected' : '' ?>
              >
                <?= htmlspecialchars((string)(($p['lastname'] ?? '') . ' ' . ($p['firstname'] ?? '') . ' — ' . ($p['email'] ?? ''))) ?>
              </option>
            <?php endforeach; ?>
          </select>

          <div class="assign-actions">
            <button class="btn btn-primary" type="submit">Enregistrer l’affectation</button>
          </div>
        </form>

        <?php if (!empty($s['pilot_id'])): ?>
          <form method="post"
                action="<?= Http::url('/pilot-students/unassign') ?>"
                class="assign-unassign-form"
                onsubmit="return confirm('Retirer cette affectation ?');">
            <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">
            <input type="hidden" name="student_id" value="<?= (int)$s['id'] ?>">
            <button class="btn btn-danger" type="submit">Retirer l’affectation</button>
          </form>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>