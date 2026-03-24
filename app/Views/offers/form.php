<?php
declare(strict_types=1);

use App\Core\Http;

$offer = $offer ?? null;
$companies = $companies ?? [];
$skills = $skills ?? [];
$selectedSkills = $selectedSkills ?? [];

$title = $title ?? ($offer ? "Modifier une offre" : "Créer une offre");
$action = $action ?? Http::url('/offers');
$isEdit = !empty($offer);
?>

<section class="offer-form-hero">
  <div class="offer-form-hero-bg"></div>

  <div class="offer-form-hero-content">
    <div>
      <span class="offer-form-badge">
        <?= $isEdit ? 'Mise à jour de l’offre' : 'Nouvelle opportunité' ?>
      </span>

      <h1 class="offer-form-title"><?= htmlspecialchars($title) ?></h1>

      <p class="offer-form-subtitle">
        Renseigne les informations essentielles de l’offre afin de la publier
        proprement sur la plateforme et de la rendre visible aux étudiants.
      </p>
    </div>

    <div class="offer-form-hero-side">
      <div class="offer-mini-panel">
        <div class="offer-mini-line">
          <span class="offer-mini-label">Mode</span>
          <strong><?= $isEdit ? 'Édition' : 'Création' ?></strong>
        </div>
        <div class="offer-mini-line">
          <span class="offer-mini-label">Type</span>
          <strong>Offre de stage</strong>
        </div>
        <div class="offer-mini-line">
          <span class="offer-mini-label">Champs clés</span>
          <strong>Titre, entreprise, description</strong>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="offer-form-layout">
  <div class="offer-form-main">
    <div class="card offer-form-card">
      <div class="offer-form-section-head">
        <span class="offer-form-section-badge">Informations</span>
        <h2>Détails de l’offre</h2>
      </div>

      <form method="post" action="<?= htmlspecialchars($action) ?>">
        <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::token() ?>">

        <div class="form-row">
          <div>
            <label class="offer-label">Titre *</label>
            <input
              class="input offer-input"
              type="text"
              name="title"
              required
              value="<?= htmlspecialchars((string)($offer['title'] ?? '')) ?>"
              placeholder="Ex : Stage Data Analyst"
            >
          </div>

          <div>
            <label class="offer-label">Entreprise *</label>
            <select class="input offer-input" name="company_id" required>
              <option value="">— Choisir —</option>
              <?php foreach ($companies as $c): ?>
                <?php
                  $cid = (int)$c['id'];
                  $selected = (!empty($offer['company_id']) && (int)$offer['company_id'] === $cid) ? 'selected' : '';
                ?>
                <option value="<?= $cid ?>" <?= $selected ?>>
                  <?= htmlspecialchars((string)$c['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div style="height:12px;"></div>

        <div>
          <label class="offer-label">Description *</label>
          <textarea
            class="input offer-textarea"
            name="description"
            required
            placeholder="Décris la mission, les tâches, le contexte, l’environnement, les objectifs..."
          ><?= htmlspecialchars((string)($offer['description'] ?? '')) ?></textarea>
        </div>

        <div style="height:12px;"></div>

        <div class="form-row">
          <div>
            <label class="offer-label">Durée (semaines)</label>
            <input
              class="input offer-input"
              type="number"
              min="1"
              max="52"
              name="duration_weeks"
              value="<?= htmlspecialchars((string)($offer['duration_weeks'] ?? '')) ?>"
              placeholder="Ex : 12"
            >
          </div>

          <div>
            <label class="offer-label">Base de rémunération</label>
            <input
              class="input offer-input"
              type="text"
              name="remuneration_base"
              value="<?= htmlspecialchars((string)($offer['remuneration_base'] ?? '')) ?>"
              placeholder="Ex : 600€/mois"
            >
          </div>
        </div>

        <div style="height:16px;"></div>

        <div class="offer-form-section-head" style="margin-bottom:12px;">
          <span class="offer-form-section-badge">Compétences</span>
          <h2 style="font-size:24px;">Profil recherché</h2>
        </div>

        <div class="offer-skills-box">
          <div class="offer-skills-help">
            Sélectionne les compétences liées à cette offre.
          </div>

          <div class="offer-skills-grid">
            <?php foreach ($skills as $s): ?>
              <?php
                $sid = (int)$s['id'];
                $checked = in_array($sid, $selectedSkills, true) ? 'checked' : '';
              ?>
              <label class="offer-skill-option">
                <input type="checkbox" name="skills[]" value="<?= $sid ?>" <?= $checked ?>>
                <span><?= htmlspecialchars((string)$s['label']) ?></span>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="offer-form-actions">
          <button class="btn btn-primary offer-save-btn" type="submit">
            ✅ <?= $isEdit ? 'Enregistrer les modifications' : 'Créer l’offre' ?>
          </button>

          <a class="btn offer-cancel-btn" href="<?= Http::url('/offers') ?>">
            Annuler
          </a>
        </div>
      </form>
    </div>
  </div>

  <aside class="offer-form-side">
    <div class="card offer-help-card">
      <div class="offer-form-section-head" style="margin-bottom:12px;">
        <span class="offer-form-section-badge">Conseils</span>
        <h3 style="margin:0; font-size:22px;">Bonnes pratiques</h3>
      </div>

      <ul class="offer-help-list">
        <li>Choisis un titre clair et explicite.</li>
        <li>Associe l’offre à la bonne entreprise.</li>
        <li>Soigne la description pour attirer les bons profils.</li>
        <li>Ajoute les compétences les plus pertinentes.</li>
      </ul>
    </div>

    <div class="card offer-help-card">
      <div class="offer-form-section-head" style="margin-bottom:12px;">
        <span class="offer-form-section-badge">Workflow</span>
        <h3 style="margin:0; font-size:22px;">Étapes suivantes</h3>
      </div>

      <div class="offer-flow">
        <div class="offer-flow-step">
          <span class="offer-flow-num">1</span>
          <div>Créer ou modifier l’offre</div>
        </div>
        <div class="offer-flow-step">
          <span class="offer-flow-num">2</span>
          <div>Associer les compétences recherchées</div>
        </div>
        <div class="offer-flow-step">
          <span class="offer-flow-num">3</span>
          <div>Publier et suivre les candidatures</div>
        </div>
      </div>

      <div style="margin-top:16px;">
        <a class="btn" href="<?= Http::url('/offers') ?>">← Retour à la liste</a>
      </div>
    </div>
  </aside>
</div>