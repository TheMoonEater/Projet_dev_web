<?php
declare(strict_types=1);

use App\Core\Http;
use App\Core\Csrf;

$student = $student ?? [];
$applicationsCount = (int)($applicationsCount ?? 0);
$id = (int)($student['id'] ?? 0);

$fullName = trim((string)(($student['lastname'] ?? '') . ' ' . ($student['firstname'] ?? '')));
$initial = strtoupper(substr($fullName !== '' ? $fullName : '?', 0, 1));
?>

<section class="student-show-hero">
  <div class="student-show-hero-bg"></div>

  <div class="student-show-hero-content">
    <div class="student-show-hero-left">
      <div class="student-show-topline">
        <span class="student-show-top-pill">Compte étudiant</span>
        <span class="student-show-top-sep">•</span>
        <span class="student-show-top-text">Accès offres, candidatures et suivi</span>
      </div>

      <h1 class="student-show-title"><?= htmlspecialchars($fullName) ?></h1>

      <div class="student-show-badges">
        <span class="student-show-pill">📧 <?= htmlspecialchars((string)($student['email'] ?? '')) ?></span>
        <span class="student-show-pill">📨 <?= $applicationsCount ?> candidature(s)</span>
      </div>
    </div>

    <div class="student-show-hero-right">
      <div class="student-show-avatar-card">
        <div class="student-show-avatar"><?= $initial ?></div>
        <div class="student-show-avatar-meta">
          <strong><?= htmlspecialchars($fullName) ?></strong>
          <span><?= htmlspecialchars((string)($student['email'] ?? '')) ?></span>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="student-show-layout">
  <div class="student-show-main">
    <div class="card student-show-card">
      <div class="student-show-section-head">
        <span class="student-show-section-badge">Informations</span>
        <h2>Identité de l’étudiant</h2>
      </div>

      <div class="student-show-info-grid">
        <div class="student-show-info-item">
          <span class="student-show-info-label">Prénom</span>
          <strong><?= htmlspecialchars((string)($student['firstname'] ?? '')) ?></strong>
        </div>

        <div class="student-show-info-item">
          <span class="student-show-info-label">Nom</span>
          <strong><?= htmlspecialchars((string)($student['lastname'] ?? '')) ?></strong>
        </div>

        <div class="student-show-info-item full">
          <span class="student-show-info-label">Email</span>
          <strong><?= htmlspecialchars((string)($student['email'] ?? '')) ?></strong>
        </div>
      </div>
    </div>

    <div class="card student-show-card">
      <div class="student-show-section-head">
        <span class="student-show-section-badge">Suivi</span>
        <h2>État de la recherche</h2>
      </div>

      <div class="student-show-progress-box">
        <div class="student-show-progress-icon">📊</div>
        <div>
          <strong><?= $applicationsCount ?> candidature(s) enregistrée(s)</strong>
          <p class="muted" style="margin:6px 0 0;">
            Ce compteur permet de suivre rapidement l’activité de l’étudiant
            sur la plateforme et l’évolution de sa recherche de stage.
          </p>
        </div>
      </div>
    </div>

    <div class="student-show-back-row">
      <a class="btn" href="<?= Http::url('/students') ?>">← Retour aux étudiants</a>
    </div>
  </div>

  <aside class="student-show-side">
    <div class="card student-show-side-card">
      <div class="student-show-side-top">
        <span class="student-show-side-badge">Actions</span>
        <h3>Gérer le compte</h3>
        <p class="muted">Modifie les informations ou supprime le compte étudiant.</p>
      </div>

      <a class="btn student-show-action-btn" href="<?= Http::url('/students/' . $id . '/edit') ?>">
        ✏️ Modifier l’étudiant
      </a>

      <div class="student-show-divider"></div>

      <form method="post"
            action="<?= Http::url('/students/' . $id . '/delete') ?>"
            onsubmit="return confirm('Supprimer cet étudiant ?');">
        <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">
        <button class="btn btn-danger student-show-action-btn" type="submit">🗑 Supprimer</button>
      </form>
    </div>
  </aside>
</div>