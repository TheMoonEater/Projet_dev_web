<?php
declare(strict_types=1);

use App\Core\Http;
use App\Core\Csrf;

$error = (string)($error ?? '');
$oldEmail = (string)($oldEmail ?? '');
?>

<div class="login-page">
  <div class="login-page-bg"></div>

  <div class="login-shell">
    <div class="login-left">
      <div class="login-brand">
        <span class="login-brand-icon">🎓</span>
        <div>
          <div class="login-brand-title">Stages CESI</div>
          <div class="login-brand-subtitle">Plateforme intelligente de gestion des stages</div>
        </div>
      </div>

      <span class="login-badge">Accès sécurisé</span>

      <h1 class="login-title">
        Connecte-toi à
        <span>ton espace</span>
      </h1>

      <p class="login-subtitle">
        Accède à ton tableau de bord selon ton rôle, consulte les offres,
        suis les candidatures et gère la plateforme dans un environnement sécurisé.
      </p>

      <div class="login-features">
        <div class="login-feature-card">
          <strong>Étudiant</strong>
          <span>Offres, wish-list, candidatures</span>
        </div>

        <div class="login-feature-card">
          <strong>Pilote</strong>
          <span>Suivi des élèves et supervision</span>
        </div>

        <div class="login-feature-card">
          <strong>Admin</strong>
          <span>Gestion complète de la plateforme</span>
        </div>
      </div>
    </div>

    <div class="login-right">
      <div class="card login-card">
        <div class="login-card-head">
          <span class="login-section-badge">Authentification</span>
          <h2>Connexion</h2>
          <p class="muted">Connecte-toi pour accéder aux fonctionnalités liées à ton rôle.</p>
        </div>

        <?php if ($error !== ''): ?>
          <div class="login-error-box">
            <strong>Erreur</strong>
            <div><?= htmlspecialchars($error) ?></div>
          </div>
        <?php endif; ?>

        <form method="post" action="<?= Http::url('/login') ?>" class="login-form">
          <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">

          <div class="login-field">
            <label class="login-label">Email</label>
            <input
              class="input login-input"
              type="email"
              name="email"
              required
              value="<?= htmlspecialchars($oldEmail) ?>"
              placeholder="ex: test@gmail.com"
            >
          </div>

          <div class="login-field">
            <label class="login-label">Mot de passe</label>
            <input
              class="input login-input"
              type="password"
              name="password"
              required
              placeholder="••••••••"
            >
          </div>

          <button class="btn btn-primary login-submit-btn" type="submit">
            Se connecter
          </button>
        </form>

        <div class="login-demo-box">
          <strong>Astuce démo</strong>
          <p class="muted">Teste la connexion avec des comptes ADMIN, PILOT ou STUDENT selon les utilisateurs créés.</p>
        </div>
      </div>
    </div>
  </div>
</div>