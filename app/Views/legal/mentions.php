<?php
declare(strict_types=1);

use App\Core\Http;
?>

<section class="legal-hero">
  <div class="legal-hero-bg"></div>

  <div class="legal-hero-content">
    <div class="legal-hero-left">
      <span class="legal-hero-badge">Conformité & informations du site</span>

      <h1 class="legal-hero-title">
        Mentions légales
        <span>et cadre d’utilisation</span>
      </h1>

      <p class="legal-hero-subtitle">
        Cette page présente les informations générales du projet, son cadre pédagogique,
        les principes de traitement des données et les éléments de conformité associés à la plateforme.
      </p>

      <div class="legal-hero-pills">
        <span class="legal-pill">📘 Projet pédagogique</span>
        <span class="legal-pill">🔐 Sécurité des accès</span>
        <span class="legal-pill">🍪 Cookies de session</span>
      </div>
    </div>

    <div class="legal-hero-right">
      <div class="legal-mini-panel">
        <div class="legal-mini-row">
          <span class="label">Projet</span>
          <strong>Plateforme Stages</strong>
        </div>
        <div class="legal-mini-row">
          <span class="label">Contexte</span>
          <strong>CESI / Web4All</strong>
        </div>
        <div class="legal-mini-row">
          <span class="label">Statut</span>
          <strong>Démonstration pédagogique</strong>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="legal-layout">
  <div class="legal-main">
    <div class="card legal-card">
      <div class="legal-section-head">
        <span class="legal-section-badge">Éditeur</span>
        <h2>Éditeur du site</h2>
      </div>

      <p class="legal-text">
        Site réalisé dans le cadre d’un projet pédagogique CESI.
      </p>

      <div class="legal-info-grid">
        <div class="legal-info-item">
          <span class="legal-info-label">Nom du projet</span>
          <strong>Plateforme Stages</strong>
        </div>

        <div class="legal-info-item">
          <span class="legal-info-label">Équipe</span>
          <strong>Web4All (groupe étudiant)</strong>
        </div>

        <div class="legal-info-item full">
          <span class="legal-info-label">Contact</span>
          <strong>contact@cesi.local</strong>
        </div>
      </div>
    </div>

    <div class="card legal-card">
      <div class="legal-section-head">
        <span class="legal-section-badge">Hébergement</span>
        <h2>Environnement technique</h2>
      </div>

      <p class="legal-text">
        L’application est hébergée dans un environnement local à des fins de démonstration
        et de développement, basé sur XAMPP, Apache, PHP et MySQL.
      </p>

      <div class="legal-stack-box">
        <span class="badge">Apache</span>
        <span class="badge">PHP</span>
        <span class="badge">MySQL</span>
        <span class="badge">XAMPP</span>
      </div>
    </div>

    <div class="card legal-card">
      <div class="legal-section-head">
        <span class="legal-section-badge">Données</span>
        <h2>Données personnelles</h2>
      </div>

      <p class="legal-text">
        Les données saisies dans l’application (comptes, entreprises, offres, candidatures)
        sont utilisées uniquement pour le fonctionnement de la plateforme.
        Les mots de passe sont stockés sous forme sécurisée (hash) et les accès sont protégés
        par un système de sessions et de permissions selon les rôles.
      </p>

      <div class="legal-highlight">
        <div class="legal-highlight-icon">🛡️</div>
        <div>
          <strong>Protection des données</strong>
          <p class="muted" style="margin:6px 0 0;">
            La plateforme applique des mécanismes de sécurité comme le hash des mots de passe,
            les contrôles d’accès, la gestion de session et la protection CSRF.
          </p>
        </div>
      </div>
    </div>

    <div class="card legal-card">
      <div class="legal-section-head">
        <span class="legal-section-badge">Cookies</span>
        <h2>Utilisation des cookies</h2>
      </div>

      <p class="legal-text">
        Le site utilise des cookies de session pour permettre l’authentification
        et le maintien des connexions utilisateurs. Ces cookies sont configurés avec
        des attributs de sécurité adaptés comme <strong>HttpOnly</strong> et <strong>SameSite</strong>.
      </p>
    </div>

    <div class="card legal-card">
      <div class="legal-section-head">
        <span class="legal-section-badge">Propriété</span>
        <h2>Propriété intellectuelle</h2>
      </div>

      <p class="legal-text">
        Le contenu, le design et le code de la plateforme ont été produits dans le cadre
        du projet pédagogique. Toute réutilisation doit mentionner la source et le contexte académique.
      </p>
    </div>

    <div class="legal-back-row">
      <a class="btn" href="<?= Http::url('/') ?>">← Retour à l’accueil</a>
    </div>
  </div>

  <aside class="legal-side">
    <div class="card legal-side-card">
      <div class="legal-section-head" style="margin-bottom:12px;">
        <span class="legal-section-badge">Résumé</span>
        <h3 style="margin:0; font-size:22px;">Ce qu’il faut retenir</h3>
      </div>

      <div class="legal-summary-list">
        <div class="legal-summary-item">
          <span class="legal-summary-num">1</span>
          <div>Projet pédagogique réalisé dans un cadre académique.</div>
        </div>

        <div class="legal-summary-item">
          <span class="legal-summary-num">2</span>
          <div>Utilisation de données nécessaires au fonctionnement de la plateforme.</div>
        </div>

        <div class="legal-summary-item">
          <span class="legal-summary-num">3</span>
          <div>Mesures de sécurité mises en place pour les comptes et les sessions.</div>
        </div>
      </div>
    </div>
  </aside>
</div>