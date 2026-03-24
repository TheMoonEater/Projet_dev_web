<?php
declare(strict_types=1);

use App\Core\Auth;
use App\Core\Http;
use App\Core\Permissions;

$user = Auth::user();
$role = Auth::role();
?>

<section class="hero-home">
  <div class="hero-overlay"></div>

  <div class="hero-content">
    <div class="hero-left">
      <span class="hero-badge">Plateforme intelligente de gestion des stages</span>

      <h1 class="hero-title">
        Trouve, gère et pilote
        <span>les opportunités de stage</span>
        en toute simplicité.
      </h1>

      <p class="hero-subtitle">
        Une plateforme complète pour centraliser les offres, suivre les candidatures,
        gérer les entreprises et offrir une expérience claire aux étudiants, pilotes et administrateurs.
      </p>

      <div class="hero-actions">
        <a class="btn btn-primary hero-btn" href="<?= Http::url('/offers') ?>">Explorer les offres</a>
        <a class="btn hero-btn-secondary" href="<?= Http::url('/companies') ?>">Découvrir les entreprises</a>

        <?php if (!$user): ?>
          <a class="btn hero-btn-secondary" href="<?= Http::url('/login') ?>">Se connecter</a>
        <?php endif; ?>
      </div>

      <div class="hero-badges">
        <?php if ($user): ?>
          <span class="badge">Connecté : <?= htmlspecialchars((string)$user['email']) ?></span>
          <span class="badge">Rôle : <?= htmlspecialchars((string)$role) ?></span>
        <?php else: ?>
          <span class="badge">Accès public</span>
          <span class="badge">Connexion requise pour postuler</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="hero-right">
      <div class="dashboard-mockup">
        <div class="mockup-top">
          <span class="dot red"></span>
          <span class="dot yellow"></span>
          <span class="dot green"></span>
        </div>

        <div class="mockup-card-grid">
          <div class="mini-card">
            <div class="mini-label">Offres actives</div>
            <div class="mini-value">128</div>
          </div>
          <div class="mini-card">
            <div class="mini-label">Candidatures</div>
            <div class="mini-value">342</div>
          </div>
          <div class="mini-card wide">
            <div class="mini-label">Top entreprise</div>
            <div class="mini-value small">TechNova</div>
          </div>
          <div class="mini-chart">
            <div class="chart-bar" style="height:40%"></div>
            <div class="chart-bar" style="height:65%"></div>
            <div class="chart-bar" style="height:85%"></div>
            <div class="chart-bar" style="height:58%"></div>
            <div class="chart-bar" style="height:92%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="home-kpis">
  <div class="home-kpi-card">
    <div class="home-kpi-value">3</div>
    <div class="home-kpi-label">Rôles utilisateurs</div>
  </div>
  <div class="home-kpi-card">
    <div class="home-kpi-value">25+</div>
    <div class="home-kpi-label">Fonctionnalités métier</div>
  </div>
  <div class="home-kpi-card">
    <div class="home-kpi-value">100%</div>
    <div class="home-kpi-label">Permissions conformes</div>
  </div>
  <div class="home-kpi-card">
    <div class="home-kpi-value">MVC</div>
    <div class="home-kpi-label">Architecture backend</div>
  </div>
</section>

<section class="home-section">
  <div class="section-header">
    <h2>Une plateforme pensée pour chaque profil</h2>
    <p class="muted">Chaque utilisateur dispose d’un espace et de fonctionnalités adaptées à son rôle.</p>
  </div>

  <div class="grid grid-3 home-feature-grid">
    <div class="feature-card">
      <div class="feature-icon">🎓</div>
      <h3>Étudiant</h3>
      <p>Consulte les offres, ajoute en wish-list, postule et suit ses candidatures.</p>
    </div>

    <div class="feature-card">
      <div class="feature-icon">📋</div>
      <h3>Pilote</h3>
      <p>Gère les comptes étudiants, suit les candidatures des élèves et supervise l’activité.</p>
    </div>

    <div class="feature-card">
      <div class="feature-icon">⚙️</div>
      <h3>Administrateur</h3>
      <p>Dispose d’un contrôle complet sur la plateforme, les comptes, les entreprises et les offres.</p>
    </div>
  </div>
</section>

<section class="home-section">
  <div class="section-header">
    <h2>Parcours utilisateur</h2>
    <p class="muted">Une expérience simple, fluide et logique.</p>
  </div>

  <div class="timeline">
    <div class="timeline-step">
      <div class="timeline-number">1</div>
      <div>
        <h3>Explorer</h3>
        <p>Consulter librement les entreprises et les offres de stage.</p>
      </div>
    </div>

    <div class="timeline-step">
      <div class="timeline-number">2</div>
      <div>
        <h3>Se connecter</h3>
        <p>Accéder à un espace personnalisé selon le rôle utilisateur.</p>
      </div>
    </div>

    <div class="timeline-step">
      <div class="timeline-number">3</div>
      <div>
        <h3>Interagir</h3>
        <p>Créer, postuler, gérer, suivre, analyser… selon les permissions accordées.</p>
      </div>
    </div>
  </div>
</section>

<section class="home-section">
  <div class="section-header">
    <h2>Accès rapide</h2>
    <p class="muted">Navigue directement vers les sections principales.</p>
  </div>

  <div class="grid grid-3">
    <a class="quick-card" href="<?= Http::url('/offers') ?>">
      <div class="quick-title">Offres</div>
      <div class="quick-text">Recherche, pagination, détail, candidature</div>
    </a>

    <a class="quick-card" href="<?= Http::url('/companies') ?>">
      <div class="quick-title">Entreprises</div>
      <div class="quick-text">Fiches, évaluations, gestion complète</div>
    </a>

    <a class="quick-card" href="<?= Http::url('/offers/stats') ?>">
      <div class="quick-title">Statistiques</div>
      <div class="quick-text">Dashboard visuel, tendances et indicateurs</div>
    </a>
  </div>
</section>

<section class="home-cta">
  <div class="home-cta-card">
    <h2>Une solution complète pour piloter la recherche de stage</h2>
    <p>
      Architecture MVC, permissions par rôle, sécurité, UX fluide, statistiques visuelles :
      la plateforme a été conçue pour être à la fois fonctionnelle, claire et professionnelle.
    </p>

    <div class="hero-actions" style="justify-content:center;">
      <a class="btn btn-primary hero-btn" href="<?= Http::url('/offers') ?>">Commencer maintenant</a>
      <?php if (!$user): ?>
        <a class="btn hero-btn-secondary" href="<?= Http::url('/login') ?>">Connexion</a>
      <?php endif; ?>
    </div>
  </div>
</section>