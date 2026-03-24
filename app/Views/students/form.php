<?php
declare(strict_types=1);

use App\Core\Http;

$student = $student ?? null;
$title = $title ?? ($student ? "Modifier un étudiant" : "Créer un étudiant");
$action = $action ?? Http::url('/students');
$isEdit = !empty($student);
?>

<section class="student-form-hero">
  <div class="student-form-hero-bg"></div>

  <div class="student-form-hero-content">
    <div>
      <span class="student-form-badge">
        <?= $isEdit ? 'Mise à jour du compte étudiant' : 'Nouveau compte étudiant' ?>
      </span>

      <h1 class="student-form-title"><?= htmlspecialchars($title) ?></h1>

      <p class="student-form-subtitle">
        Crée ou modifie un compte étudiant afin de permettre l’accès aux offres,
        aux candidatures, à la wish-list et au suivi du parcours de stage.
      </p>
    </div>

    <div class="student-form-hero-side">
      <div class="student-mini-panel">
        <div class="student-mini-line">
          <span class="student-mini-label">Mode</span>
          <strong><?= $isEdit ? 'Édition' : 'Création' ?></strong>
        </div>
        <div class="student-mini-line">
          <span class="student-mini-label">Type</span>
          <strong>Compte étudiant</strong>
        </div>
        <div class="student-mini-line">
          <span class="student-mini-label">Accès</span>
          <strong>Offres & candidatures</strong>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="student-form-layout">
  <div class="student-form-main">
    <div class="card student-form-card">
      <div class="student-form-section-head">
        <span class="student-form-section-badge">Informations</span>
        <h2>Identité de l’étudiant</h2>
      </div>

      <form method="post" action="<?= htmlspecialchars($action) ?>">
        <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::token() ?>">

        <div class="form-row">
          <div>
            <label class="student-label">Prénom *</label>
            <input
              class="input student-input"
              type="text"
              name="firstname"
              required
              value="<?= htmlspecialchars((string)($student['firstname'] ?? '')) ?>"
              placeholder="Ex : Adam"
            >
          </div>

          <div>
            <label class="student-label">Nom *</label>
            <input
              class="input student-input"
              type="text"
              name="lastname"
              required
              value="<?= htmlspecialchars((string)($student['lastname'] ?? '')) ?>"
              placeholder="Ex : Benali"
            >
          </div>
        </div>

        <div style="height:12px;"></div>

        <div class="form-row">
          <div>
            <label class="student-label">Email *</label>
            <input
              class="input student-input"
              type="email"
              name="email"
              required
              value="<?= htmlspecialchars((string)($student['email'] ?? '')) ?>"
              placeholder="etudiant@cesi.local"
            >
          </div>

          <div>
            <label class="student-label">
              Mot de passe <?= $isEdit ? '(laisser vide pour ne pas changer)' : '*' ?>
            </label>
            <input
              class="input student-input"
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
            <label class="student-label">Statut</label>
            <div class="student-static-box">
              <?= $isEdit ? 'Compte étudiant existant' : 'Nouveau compte étudiant à créer' ?>
            </div>
          </div>

          <div>
            <label class="student-label">Rôle</label>
            <div class="student-static-box">Étudiant</div>
          </div>
        </div>

        <div class="student-form-actions">
          <button class="btn btn-primary student-save-btn" type="submit">
            ✅ <?= $isEdit ? 'Enregistrer les modifications' : 'Créer l’étudiant' ?>
          </button>

          <a class="btn student-cancel-btn" href="<?= Http::url('/students') ?>">
            Annuler
          </a>
        </div>
      </form>
    </div>
  </div>

  <aside class="student-form-side">
    <div class="card student-help-card">
      <div class="student-form-section-head" style="margin-bottom:12px;">
        <span class="student-form-section-badge">Conseils</span>
        <h3 style="margin:0; font-size:22px;">Bonnes pratiques</h3>
      </div>

      <ul class="student-help-list">
        <li>Renseigne le vrai prénom et le vrai nom.</li>
        <li>Utilise une adresse email claire et unique.</li>
        <li>En création, choisis un mot de passe robuste.</li>
        <li>En modification, laisse vide si tu ne veux pas le changer.</li>
      </ul>
    </div>

    <div class="card student-help-card">
      <div class="student-form-section-head" style="margin-bottom:12px;">
        <span class="student-form-section-badge">Workflow</span>
        <h3 style="margin:0; font-size:22px;">Étapes suivantes</h3>
      </div>

      <div class="student-flow">
        <div class="student-flow-step">
          <span class="student-flow-num">1</span>
          <div>Créer ou modifier le compte étudiant</div>
        </div>
        <div class="student-flow-step">
          <span class="student-flow-num">2</span>
          <div>Permettre l’accès aux offres et aux candidatures</div>
        </div>
        <div class="student-flow-step">
          <span class="student-flow-num">3</span>
          <div>Suivre la progression sur la plateforme</div>
        </div>
      </div>

      <div style="margin-top:16px;">
        <a class="btn" href="<?= Http::url('/students') ?>">← Retour à la liste</a>
      </div>
    </div>
  </aside>
</div>