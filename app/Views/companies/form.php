<?php
declare(strict_types=1);

use App\Core\Http;

$company = $company ?? null;

$title = $title ?? ($company ? "Modifier une entreprise" : "Créer une entreprise");
$action = $action ?? Http::url('/companies');
$isEdit = !empty($company);
?>

<section class="company-form-hero">
  <div class="company-form-hero-bg"></div>

  <div class="company-form-hero-content">
    <div>
      <span class="company-form-badge">
        <?= $isEdit ? 'Mise à jour entreprise' : 'Nouvelle entreprise' ?>
      </span>

      <h1 class="company-form-title">
        <?= htmlspecialchars($title) ?>
      </h1>

      <p class="company-form-subtitle">
        Renseigne les informations essentielles de l’entreprise afin de l’intégrer
        proprement à la plateforme et de la relier aux futures offres de stage.
      </p>
    </div>

    <div class="company-form-hero-side">
      <div class="company-mini-panel">
        <div class="company-mini-line">
          <span class="company-mini-label">Mode</span>
          <strong><?= $isEdit ? 'Édition' : 'Création' ?></strong>
        </div>
        <div class="company-mini-line">
          <span class="company-mini-label">Type</span>
          <strong>Fiche entreprise</strong>
        </div>
        <div class="company-mini-line">
          <span class="company-mini-label">Champs clés</span>
          <strong>Nom, contact, description</strong>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="company-form-layout">
  <div class="company-form-main">
    <div class="card company-form-card">
      <div class="company-form-section-head">
        <span class="company-form-section-badge">Informations</span>
        <h2>Identité de l’entreprise</h2>
      </div>

      <form method="post" action="<?= htmlspecialchars($action) ?>">
        <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::token() ?>">

        <div class="form-row">
          <div>
            <label class="company-label">Nom *</label>
            <input
              class="input company-input"
              type="text"
              name="name"
              required
              value="<?= htmlspecialchars((string)($company['name'] ?? '')) ?>"
              placeholder="Ex : Capgemini"
            >
          </div>

          <div>
            <label class="company-label">Email de contact</label>
            <input
              class="input company-input"
              type="email"
              name="email"
              value="<?= htmlspecialchars((string)($company['contact_email'] ?? '')) ?>"
              placeholder="contact@entreprise.fr"
            >
          </div>
        </div>

        <div style="height:12px;"></div>

        <div class="form-row">
          <div>
            <label class="company-label">Téléphone</label>
            <input
              class="input company-input"
              type="text"
              name="phone"
              value="<?= htmlspecialchars((string)($company['contact_phone'] ?? '')) ?>"
              placeholder="+213 ..."
            >
          </div>

          <div>
            <label class="company-label">Statut</label>
            <div class="company-static-box">
              <?= $isEdit ? 'Entreprise déjà enregistrée' : 'Nouvelle fiche à créer' ?>
            </div>
          </div>
        </div>

        <div style="height:12px;"></div>

        <div>
          <label class="company-label">Description</label>
          <textarea
            class="input company-textarea"
            name="description"
            placeholder="Décris l’activité, le secteur, l’environnement, les points forts de l’entreprise..."
          ><?= htmlspecialchars((string)($company['description'] ?? '')) ?></textarea>
        </div>

        <div class="company-form-actions">
          <button class="btn btn-primary company-save-btn" type="submit">
            ✅ <?= $isEdit ? 'Enregistrer les modifications' : 'Créer l’entreprise' ?>
          </button>

          <a class="btn company-cancel-btn" href="<?= Http::url('/companies') ?>">
            Annuler
          </a>
        </div>
      </form>
    </div>
  </div>

  <aside class="company-form-side">
    <div class="card company-help-card">
      <div class="company-form-section-head" style="margin-bottom:12px;">
        <span class="company-form-section-badge">Conseils</span>
        <h3 style="margin:0; font-size:22px;">Bonnes pratiques</h3>
      </div>

      <ul class="company-help-list">
        <li>Le nom doit être clair et officiel.</li>
        <li>Renseigne un email de contact exploitable.</li>
        <li>Ajoute une description courte mais informative.</li>
        <li>Cette fiche servira à relier les offres et les évaluations.</li>
      </ul>
    </div>

    <div class="card company-help-card">
      <div class="company-form-section-head" style="margin-bottom:12px;">
        <span class="company-form-section-badge">Workflow</span>
        <h3 style="margin:0; font-size:22px;">Étapes suivantes</h3>
      </div>

      <div class="company-flow">
        <div class="company-flow-step">
          <span class="company-flow-num">1</span>
          <div>Créer ou modifier la fiche entreprise</div>
        </div>
        <div class="company-flow-step">
          <span class="company-flow-num">2</span>
          <div>Associer des offres à cette entreprise</div>
        </div>
        <div class="company-flow-step">
          <span class="company-flow-num">3</span>
          <div>Suivre les candidatures et évaluations</div>
        </div>
      </div>

      <div style="margin-top:16px;">
        <a class="btn" href="<?= Http::url('/companies') ?>">← Retour à la liste</a>
      </div>
    </div>
  </aside>
</div>