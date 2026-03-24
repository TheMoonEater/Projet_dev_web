<?php
declare(strict_types=1);

use App\Core\Http;

$pilot = $pilot ?? null;
$title = $title ?? ($pilot ? "Modifier un pilote" : "Créer un pilote");
$action = $action ?? Http::url('/pilots');
$isEdit = !empty($pilot);
?>

<section class="pilot-form-hero">
  <div class="pilot-form-hero-bg"></div>

  <div class="pilot-form-hero-content">
    <div>
      <span class="pilot-form-badge">
        <?= $isEdit ? 'Mise à jour du pilote' : 'Nouveau pilote de promotion' ?>
      </span>

      <h1 class="pilot-form-title"><?= htmlspecialchars($title) ?></h1>

      <p class="pilot-form-subtitle">
        Crée ou modifie un compte pilote afin de permettre le suivi des étudiants,
        l’accès aux candidatures et la gestion pédagogique sur la plateforme.
      </p>
    </div>

    <div class="pilot-form-hero-side">
      <div class="pilot-mini-panel">
        <div class="pilot-mini-line">
          <span class="pilot-mini-label">Mode</span>
          <strong><?= $isEdit ? 'Édition' : 'Création' ?></strong>
        </div>
        <div class="pilot-mini-line">
          <span class="pilot-mini-label">Type</span>
          <strong>Compte pilote</strong>
        </div>
        <div class="pilot-mini-line">
          <span class="pilot-mini-label">Accès</span>
          <strong>Suivi & supervision</strong>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="pilot-form-layout">
  <div class="pilot-form-main">
    <div class="card pilot-form-card">
      <div class="pilot-form-section-head">
        <span class="pilot-form-section-badge">Informations</span>
        <h2>Identité du pilote</h2>
      </div>

      <form method="post" action="<?= htmlspecialchars($action) ?>">
        <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::token() ?>">

        <div class="form-row">
          <div>
            <label class="pilot-label">Prénom *</label>
            <input
              class="input pilot-input"
              type="text"
              name="firstname"
              required
              value="<?= htmlspecialchars((string)($pilot['firstname'] ?? '')) ?>"
              placeholder="Ex : Ryu"
            >
          </div>

          <div>
            <label class="pilot-label">Nom *</label>
            <input
              class="input pilot-input"
              type="text"
              name="lastname"
              required
              value="<?= htmlspecialchars((string)($pilot['lastname'] ?? '')) ?>"
              placeholder="Ex : Dupont"
            >
          </div>
        </div>

        <div style="height:12px;"></div>

        <div class="form-row">
          <div>
            <label class="pilot-label">Email *</label>
            <input
              class="input pilot-input"
              type="email"
              name="email"
              required
              value="<?= htmlspecialchars((string)($pilot['email'] ?? '')) ?>"
              placeholder="pilote@cesi.local"
            >
          </div>

          <div>
            <label class="pilot-label">
              Mot de passe <?= $isEdit ? '(laisser vide pour ne pas changer)' : '*' ?>
            </label>
            <input
              class="input pilot-input"
              type="password"
              name="password"
              <?= $isEdit ? '' : 'required' ?>
              placeholder="<?= $isEdit ? '••••••••' : 'Choisir un mot de passe' ?>"
            >
          </div>
        </div>

        <div style="height:12px;"></div>

        <div class="form-row">
          <div>
            <label class="pilot-label">Statut</label>
            <div class="pilot-static-box">
              <?= $isEdit ? 'Compte pilote existant' : 'Nouveau compte pilote à créer' ?>
            </div>
          </div>

          <div>
            <label class="pilot-label">Rôle</label>
            <div class="pilot-static-box">Pilote de promotion</div>
          </div>
        </div>

        <div class="pilot-form-actions">
          <button class="btn btn-primary pilot-save-btn" type="submit">
            ✅ <?= $isEdit ? 'Enregistrer les modifications' : 'Créer le pilote' ?>
          </button>

          <a class="btn pilot-cancel-btn" href="<?= Http::url('/pilots') ?>">
            Annuler
          </a>
        </div>
      </form>
    </div>
  </div>

  <aside class="pilot-form-side">
    <div class="card pilot-help-card">
      <div class="pilot-form-section-head" style="margin-bottom:12px;">
        <span class="pilot-form-section-badge">Conseils</span>
        <h3 style="margin:0; font-size:22px;">Bonnes pratiques</h3>
      </div>

      <ul class="pilot-help-list">
        <li>Utilise les informations réelles du pilote.</li>
        <li>Renseigne un email clair et exploitable.</li>
        <li>En création, définis un mot de passe robuste.</li>
        <li>En modification, laisse vide si tu ne changes pas le mot de passe.</li>
      </ul>
    </div>

    <div class="card pilot-help-card">
      <div class="pilot-form-section-head" style="margin-bottom:12px;">
        <span class="pilot-form-section-badge">Workflow</span>
        <h3 style="margin:0; font-size:22px;">Étapes suivantes</h3>
      </div>

      <div class="pilot-flow">
        <div class="pilot-flow-step">
          <span class="pilot-flow-num">1</span>
          <div>Créer ou modifier le compte pilote</div>
        </div>
        <div class="pilot-flow-step">
          <span class="pilot-flow-num">2</span>
          <div>Associer l’usage du compte au suivi des promotions</div>
        </div>
        <div class="pilot-flow-step">
          <span class="pilot-flow-num">3</span>
          <div>Consulter les étudiants et les candidatures</div>
        </div>
      </div>

      <div style="margin-top:16px;">
        <a class="btn" href="<?= Http::url('/pilots') ?>">← Retour à la liste</a>
      </div>
    </div>
  </aside>
</div>