<?php
declare(strict_types=1);

use App\Core\Http;
use App\Core\Csrf;

$offer = $offer ?? null;
$errors = $errors ?? [];
$old = $old ?? [];
$offerId = $offer ? (int)$offer['id'] : 0;
?>

<section class="apply-hero">
  <div class="apply-hero-bg"></div>

  <div class="apply-hero-content">
    <div class="apply-hero-left">
      <span class="apply-hero-badge">Candidature</span>

      <h1 class="apply-hero-title">
        Postuler à une offre
        <span>de manière claire et professionnelle</span>
      </h1>

      <p class="apply-hero-subtitle">
        Envoie ton CV et ta lettre de motivation pour transmettre une candidature
        structurée, lisible et prête à être consultée par l’encadrement.
      </p>

      <div class="apply-hero-pills">
        <span class="apply-pill">📄 CV PDF</span>
        <span class="apply-pill">✍️ Lettre de motivation</span>
        <span class="apply-pill">🔐 Envoi sécurisé</span>
      </div>
    </div>

    <div class="apply-hero-right">
      <div class="apply-mini-panel">
        <div class="apply-mini-row">
          <span class="label">Action</span>
          <strong>Nouvelle candidature</strong>
        </div>
        <div class="apply-mini-row">
          <span class="label">Format CV</span>
          <strong>PDF</strong>
        </div>
        <div class="apply-mini-row">
          <span class="label">Contenu</span>
          <strong>CV + motivation</strong>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if ($offer): ?>
  <div class="card apply-offer-card">
    <div class="apply-offer-head">
      <div>
        <span class="apply-section-badge">Offre ciblée</span>
        <h2><?= htmlspecialchars((string)($offer['title'] ?? '')) ?></h2>
      </div>
      <a class="btn" href="<?= Http::url('/offers/' . $offerId) ?>">← Retour offre</a>
    </div>

    <div class="apply-offer-meta">
      <span class="apply-offer-company"><?= htmlspecialchars((string)($offer['company_name'] ?? '')) ?></span>
    </div>

    <?php if (!empty($offer['description'])): ?>
      <p class="apply-offer-desc">
        <?= htmlspecialchars(mb_strimwidth((string)$offer['description'], 0, 180, '…')) ?>
      </p>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
  <div class="card apply-error-card">
    <div class="apply-error-title">Erreur de validation</div>
    <ul class="apply-error-list">
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars((string)$e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="apply-layout">
  <div class="apply-main">
    <div class="card apply-form-card">
      <div class="apply-form-head">
        <span class="apply-section-badge">Formulaire</span>
        <h2>Préparer ma candidature</h2>
      </div>

      <form method="post" action="<?= Http::url('/offers/' . $offerId . '/apply') ?>" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">

        <div>
          <label class="apply-label">Lettre de motivation *</label>
          <textarea
            class="input apply-textarea"
            name="lm_text"
            required
            placeholder="Présente ton profil, tes motivations, ce que tu peux apporter à l’entreprise, et ta disponibilité..."
          ><?= htmlspecialchars((string)($old['lm'] ?? '')) ?></textarea>

          <div class="apply-help-text">
            Conseil : 6 à 10 lignes suffisent. Va à l’essentiel : profil, motivation, compétences, disponibilité.
          </div>
        </div>

        <div style="height:14px;"></div>

        <div>
          <label class="apply-label">CV (PDF) *</label>
          <input class="input apply-file-input" type="file" name="cv" accept="application/pdf" required>

          <div class="apply-help-text">
            Format recommandé : PDF uniquement, avec un nom de fichier clair.
          </div>
        </div>

        <div class="apply-form-actions">
          <button class="btn btn-primary apply-submit-btn" type="submit">✅ Envoyer ma candidature</button>
          <a class="btn" href="<?= Http::url('/offers/' . $offerId) ?>">Annuler</a>
        </div>
      </form>
    </div>
  </div>

  <aside class="apply-side">
    <div class="card apply-side-card">
      <div class="apply-form-head" style="margin-bottom:12px;">
        <span class="apply-section-badge">Conseils</span>
        <h3 style="margin:0; font-size:22px;">Bien postuler</h3>
      </div>

      <ul class="apply-tips-list">
        <li>Adapte la lettre à l’offre visée.</li>
        <li>Garde un ton clair, sérieux et direct.</li>
        <li>Vérifie que ton CV est à jour avant l’envoi.</li>
        <li>Évite les textes trop longs ou trop vagues.</li>
      </ul>
    </div>

    <div class="card apply-side-card">
      <div class="apply-form-head" style="margin-bottom:12px;">
        <span class="apply-section-badge">Étapes</span>
        <h3 style="margin:0; font-size:22px;">Workflow</h3>
      </div>

      <div class="apply-flow">
        <div class="apply-flow-step">
          <span class="apply-flow-num">1</span>
          <div>Rédiger la motivation</div>
        </div>
        <div class="apply-flow-step">
          <span class="apply-flow-num">2</span>
          <div>Ajouter le CV au format PDF</div>
        </div>
        <div class="apply-flow-step">
          <span class="apply-flow-num">3</span>
          <div>Envoyer la candidature</div>
        </div>
      </div>
    </div>
  </aside>
</div>