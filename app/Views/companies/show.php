<?php
declare(strict_types=1);

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Http;
use App\Core\Permissions;

$role = Auth::role();
$companyId = (int)$company['id'];

$ratingAvg = (float)($company['rating_avg'] ?? 0);
$ratingCount = (int)($company['rating_count'] ?? 0);
?>

<section class="company-show-hero">
  <div class="company-show-hero-bg"></div>

  <div class="company-show-hero-content">
    <div class="company-show-hero-left">
      <div class="company-show-topline">
        <?php if (!empty($company['contact_email'])): ?>
          <span class="company-show-top-pill"><?= htmlspecialchars((string)$company['contact_email']) ?></span>
        <?php endif; ?>

        <?php if (!empty($company['contact_phone'])): ?>
          <span class="company-show-top-sep">•</span>
          <span class="company-show-top-text"><?= htmlspecialchars((string)$company['contact_phone']) ?></span>
        <?php endif; ?>
      </div>

      <h1 class="company-show-title"><?= htmlspecialchars((string)($company['name'] ?? '')) ?></h1>

      <div class="company-show-badges">
        <?php if ($ratingCount > 0): ?>
          <span class="company-show-pill">⭐ <?= number_format($ratingAvg, 1) ?>/5</span>
          <span class="company-show-pill"><?= $ratingCount ?> avis</span>
        <?php else: ?>
          <span class="company-show-pill subtle">Aucune évaluation pour le moment</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="company-show-hero-right">
      <div class="company-show-mini-panel">
        <div class="company-show-mini-row">
          <span class="label">Type</span>
          <strong>Entreprise</strong>
        </div>
        <div class="company-show-mini-row">
          <span class="label">Réputation</span>
          <strong><?= $ratingCount > 0 ? number_format($ratingAvg, 1) . '/5' : 'Non notée' ?></strong>
        </div>
        <div class="company-show-mini-row">
          <span class="label">Offres liées</span>
          <strong><?= !empty($offers) ? count($offers) : 0 ?></strong>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="company-show-layout">
  <!-- CONTENU PRINCIPAL -->
  <div class="company-show-main">
    <div class="card company-show-card">
      <div class="company-show-section-head">
        <span class="company-show-section-badge">Présentation</span>
        <h2>À propos de l’entreprise</h2>
      </div>

      <?php if (!empty($company['description'])): ?>
        <div class="company-show-description">
          <?= nl2br(htmlspecialchars((string)$company['description'])) ?>
        </div>
      <?php else: ?>
        <p class="muted">Aucune description disponible.</p>
      <?php endif; ?>
    </div>

    <div class="card company-show-card">
      <div class="company-show-section-head">
        <span class="company-show-section-badge">Coordonnées</span>
        <h2>Informations de contact</h2>
      </div>

      <div class="company-contact-grid">
        <div class="company-contact-item">
          <span class="company-contact-label">Email</span>
          <strong><?= !empty($company['contact_email']) ? htmlspecialchars((string)$company['contact_email']) : 'Non renseigné' ?></strong>
        </div>

        <div class="company-contact-item">
          <span class="company-contact-label">Téléphone</span>
          <strong><?= !empty($company['contact_phone']) ? htmlspecialchars((string)$company['contact_phone']) : 'Non renseigné' ?></strong>
        </div>
      </div>
    </div>

    <?php if (!empty($offers)): ?>
      <div class="card company-show-card">
        <div class="company-show-section-head">
          <span class="company-show-section-badge">Offres liées</span>
          <h2>Offres publiées par cette entreprise</h2>
        </div>

        <div class="company-offers-list">
          <?php foreach ($offers as $o): ?>
            <div class="company-offer-row">
              <div class="company-offer-main">
                <div class="company-offer-title">
                  <?= htmlspecialchars((string)($o['title'] ?? '')) ?>
                </div>
                <div class="company-offer-meta">
                  <?= htmlspecialchars((string)($o['posted_at'] ?? '')) ?>
                </div>
              </div>

              <div class="company-offer-actions">
                <a class="btn" href="<?= Http::url('/offers/' . (int)$o['id']) ?>">Voir l’offre</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="company-show-back-row">
      <a class="btn" href="<?= Http::url('/companies') ?>">← Retour aux entreprises</a>
    </div>
  </div>

  <!-- SIDEBAR -->
  <aside class="company-show-side">
    <div class="card company-show-side-card">
      <div class="company-show-side-top">
        <span class="company-show-side-badge">Actions</span>
        <h3>Gérer la fiche</h3>
        <p class="muted">Les actions disponibles dépendent des permissions du compte connecté.</p>
      </div>

      <?php if (Permissions::can($role, 'SFx4')): ?>
        <a class="btn company-show-action-btn"
           href="<?= Http::url('/companies/' . $companyId . '/edit') ?>">
          ✏️ Modifier l’entreprise
        </a>
      <?php endif; ?>

      <?php if (Permissions::can($role, 'SFx6')): ?>
        <form method="post"
              action="<?= Http::url('/companies/' . $companyId . '/delete') ?>"
              onsubmit="return confirm('Supprimer cette entreprise ?');">
          <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">
          <button class="btn btn-danger company-show-action-btn" type="submit">🗑 Supprimer</button>
        </form>
      <?php endif; ?>

      <?php if (Permissions::can($role, 'SFx5')): ?>
        <div class="company-show-divider"></div>

        <div class="company-show-rate-box">
          <div class="company-show-side-badge alt">Évaluation</div>
          <h3 style="margin-top:0;">Noter l’entreprise</h3>
          <p class="muted">Donne une appréciation pour enrichir la plateforme.</p>

          <form method="post" action="<?= Http::url('/companies/' . $companyId . '/rate') ?>">
            <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">

            <select name="rating" class="input" required>
              <option value="">Choisir une note</option>
              <option value="1">1 - Mauvais</option>
              <option value="2">2</option>
              <option value="3">3 - Correct</option>
              <option value="4">4</option>
              <option value="5">5 - Excellent</option>
            </select>

            <button class="btn btn-primary company-show-action-btn" type="submit" style="margin-top:10px;">
              Envoyer la note
            </button>
          </form>
        </div>
      <?php endif; ?>
    </div>
  </aside>
</div>