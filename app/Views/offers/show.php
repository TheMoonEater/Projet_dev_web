<?php
declare(strict_types=1);

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Http;
use App\Core\Permissions;

$user = Auth::user();
$role = Auth::role();

$offerId = (int)$offer['id'];
$inWish = (bool)($inWish ?? false);
?>

<section class="offer-hero">
  <div class="offer-hero-bg"></div>

  <div class="offer-hero-content">
    <div class="offer-hero-meta">
      <span class="offer-hero-company"><?= htmlspecialchars($offer['company_name'] ?? '') ?></span>

      <?php if (!empty($offer['posted_at'])): ?>
        <span class="offer-hero-separator">•</span>
        <span class="offer-hero-date"><?= htmlspecialchars((string)$offer['posted_at']) ?></span>
      <?php endif; ?>
    </div>

    <h1 class="offer-hero-title"><?= htmlspecialchars($offer['title'] ?? '') ?></h1>

    <div class="offer-hero-badges">
      <?php if (!empty($offer['duration_weeks'])): ?>
        <span class="offer-pill">⏳ <?= (int)$offer['duration_weeks'] ?> semaines</span>
      <?php endif; ?>

      <?php if (!empty($offer['remuneration_base'])): ?>
        <span class="offer-pill">💶 <?= htmlspecialchars((string)$offer['remuneration_base']) ?></span>
      <?php endif; ?>

      <span class="offer-pill">📨 <?= (int)($offer['applications_count'] ?? 0) ?> candidature(s)</span>
    </div>
  </div>
</section>

<div class="offer-layout">
  <!-- CONTENU PRINCIPAL -->
  <div class="offer-main">
    <div class="card offer-card">
      <div class="offer-section-head">
        <span class="offer-section-kicker">Description</span>
        <h2>Mission & contexte</h2>
      </div>

      <div class="offer-description">
        <?= nl2br(htmlspecialchars((string)($offer['description'] ?? ''))) ?>
      </div>
    </div>

    <?php if (!empty($skills)): ?>
      <div class="card offer-card">
        <div class="offer-section-head">
          <span class="offer-section-kicker">Compétences</span>
          <h2>Profil recherché</h2>
        </div>

        <div class="offer-skills-grid">
          <?php foreach ($skills as $s): ?>
            <span class="offer-skill-chip"><?= htmlspecialchars((string)($s['label'] ?? '')) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="card offer-card company-mini-card">
      <div class="offer-section-head">
        <span class="offer-section-kicker">Entreprise</span>
        <h2><?= htmlspecialchars($offer['company_name'] ?? '') ?></h2>
      </div>

      <p class="muted" style="line-height:1.7;">
        Cette offre est publiée par <strong><?= htmlspecialchars($offer['company_name'] ?? '') ?></strong>.
        Consulte la fiche entreprise pour découvrir d’autres opportunités, les évaluations
        et le contexte global.
      </p>

      <div style="margin-top:14px;">
        <a class="btn" href="<?= Http::url('/companies') ?>">Voir les entreprises</a>
      </div>
    </div>

    <div class="offer-back-row">
      <a class="btn" href="<?= Http::url('/offers') ?>">← Retour aux offres</a>
    </div>
  </div>

  <!-- SIDEBAR ACTIONS -->
  <aside class="offer-side">
    <div class="card offer-side-card">
      <div class="offer-side-top">
        <span class="offer-side-badge">Actions</span>
        <h3>Que veux-tu faire ?</h3>
        <p class="muted">Les options affichées dépendent du rôle connecté.</p>
      </div>

      <?php if ($user && $role === 'STUDENT'): ?>

        <?php if (Permissions::can($role, 'SFx20')): ?>
          <a class="btn btn-primary offer-action-btn"
             href="<?= Http::url('/offers/' . $offerId . '/apply') ?>">
            📩 Postuler à cette offre
          </a>
        <?php endif; ?>

        <?php if (!$inWish): ?>
          <form method="post" action="<?= Http::url('/offers/' . $offerId . '/wishlist') ?>">
            <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">
            <button class="btn offer-action-btn" type="submit">⭐ Ajouter à ma wish-list</button>
          </form>
        <?php else: ?>
          <form method="post" action="<?= Http::url('/offers/' . $offerId . '/unwishlist') ?>">
            <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">
            <button class="btn btn-danger offer-action-btn" type="submit">✖ Retirer de ma wish-list</button>
          </form>
        <?php endif; ?>

      <?php else: ?>

        <div class="offer-login-box">
          <div class="offer-login-icon">🔐</div>
          <div>
            <strong>Accès étudiant requis</strong>
            <p class="muted" style="margin:6px 0 0;">
              Connecte-toi avec un compte étudiant pour postuler ou gérer ta wish-list.
            </p>
          </div>
        </div>

        <div style="margin-top:14px;">
          <a class="btn offer-action-btn" href="<?= Http::url('/login') ?>">Se connecter</a>
        </div>

      <?php endif; ?>

      <?php if (Permissions::can($role, 'SFx9') || Permissions::can($role, 'SFx10')): ?>
        <div class="offer-side-divider"></div>
      <?php endif; ?>

      <?php if (Permissions::can($role, 'SFx9')): ?>
        <a class="btn offer-action-btn"
           href="<?= Http::url('/offers/' . $offerId . '/edit') ?>">
          ✏️ Modifier l’offre
        </a>
      <?php endif; ?>

      <?php if (Permissions::can($role, 'SFx10')): ?>
        <form method="post"
              action="<?= Http::url('/offers/' . $offerId . '/delete') ?>"
              onsubmit="return confirm('Supprimer cette offre ?');">
          <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">
          <button class="btn btn-danger offer-action-btn" type="submit">🗑 Supprimer l’offre</button>
        </form>
      <?php endif; ?>
    </div>
  </aside>
</div>