<?php
declare(strict_types=1);

use App\Core\Http;
use App\Core\Csrf;

$pilot = $pilot ?? [];
$id = (int)($pilot['id'] ?? 0);

$fullName = trim((string)(($pilot['lastname'] ?? '') . ' ' . ($pilot['firstname'] ?? '')));
$initial = strtoupper(substr($fullName !== '' ? $fullName : '?', 0, 1));
?>

<section class="pilot-show-hero">
  <div class="pilot-show-hero-bg"></div>

  <div class="pilot-show-hero-content">
    <div class="pilot-show-hero-left">
      <div class="pilot-show-topline">
        <span class="pilot-show-top-pill">Compte pilote</span>
        <span class="pilot-show-top-sep">•</span>
        <span class="pilot-show-top-text">Suivi pédagogique & supervision</span>
      </div>

      <h1 class="pilot-show-title"><?= htmlspecialchars($fullName) ?></h1>

      <div class="pilot-show-badges">
        <span class="pilot-show-pill">📧 <?= htmlspecialchars((string)($pilot['email'] ?? '')) ?></span>
        <span class="pilot-show-pill subtle">Pilote de promotion</span>
      </div>
    </div>

    <div class="pilot-show-hero-right">
      <div class="pilot-show-avatar-card">
        <div class="pilot-show-avatar"><?= $initial ?></div>
        <div class="pilot-show-avatar-meta">
          <strong><?= htmlspecialchars($fullName) ?></strong>
          <span><?= htmlspecialchars((string)($pilot['email'] ?? '')) ?></span>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="pilot-show-layout">
  <div class="pilot-show-main">
    <div class="card pilot-show-card">
      <div class="pilot-show-section-head">
        <span class="pilot-show-section-badge">Informations</span>
        <h2>Identité du pilote</h2>
      </div>

      <div class="pilot-show-info-grid">
        <div class="pilot-show-info-item">
          <span class="pilot-show-info-label">Prénom</span>
          <strong><?= htmlspecialchars((string)($pilot['firstname'] ?? '')) ?></strong>
        </div>

        <div class="pilot-show-info-item">
          <span class="pilot-show-info-label">Nom</span>
          <strong><?= htmlspecialchars((string)($pilot['lastname'] ?? '')) ?></strong>
        </div>

        <div class="pilot-show-info-item full">
          <span class="pilot-show-info-label">Email</span>
          <strong><?= htmlspecialchars((string)($pilot['email'] ?? '')) ?></strong>
        </div>
      </div>
    </div>

    <div class="card pilot-show-card">
      <div class="pilot-show-section-head">
        <span class="pilot-show-section-badge">Rôle</span>
        <h2>Fonction sur la plateforme</h2>
      </div>

      <div class="pilot-show-role-box">
        <div class="pilot-show-role-icon">🎓</div>
        <div>
          <strong>Pilote de promotion</strong>
          <p class="muted" style="margin:6px 0 0;">
            Ce compte permet de suivre les étudiants, consulter les candidatures
            et participer à la supervision globale des recherches de stage.
          </p>
        </div>
      </div>
    </div>

    <div class="pilot-show-back-row">
      <a class="btn" href="<?= Http::url('/pilots') ?>">← Retour aux pilotes</a>
    </div>
  </div>

  <aside class="pilot-show-side">
    <div class="card pilot-show-side-card">
      <div class="pilot-show-side-top">
        <span class="pilot-show-side-badge">Actions</span>
        <h3>Gérer le compte</h3>
        <p class="muted">Modifie les informations ou supprime le compte pilote.</p>
      </div>

      <a class="btn pilot-show-action-btn" href="<?= Http::url('/pilots/' . $id . '/edit') ?>">
        ✏️ Modifier le pilote
      </a>

      <div class="pilot-show-divider"></div>

      <form method="post"
            action="<?= Http::url('/pilots/' . $id . '/delete') ?>"
            onsubmit="return confirm('Supprimer ce pilote ?');">
        <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">
        <button class="btn btn-danger pilot-show-action-btn" type="submit">🗑 Supprimer</button>
      </form>
    </div>
  </aside>
</div>