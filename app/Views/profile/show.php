<?php
declare(strict_types=1);

use App\Core\Http;
use App\Core\Csrf;

$userRow = $userRow ?? [];
$error = (string)($error ?? '');
$success = (string)($success ?? '');

$photo = $userRow['profile_photo'] ?? null;
$firstname = (string)($userRow['firstname'] ?? '');
$lastname = (string)($userRow['lastname'] ?? '');
$fullName = trim($firstname . ' ' . $lastname);
$displayName = $fullName !== '' ? $fullName : (string)($userRow['email'] ?? 'Utilisateur');
$initial = strtoupper(substr($displayName, 0, 1));
?>

<section class="profile-hero">
  <div class="profile-hero-bg"></div>

  <div class="profile-hero-content">
    <div class="profile-hero-left">
      <div class="profile-topline">
        <span class="profile-top-pill">Espace personnel</span>
        <span class="profile-top-sep">•</span>
        <span class="profile-top-text">Gestion du compte et de la photo</span>
      </div>

      <h1 class="profile-title">Mon profil</h1>

      <div class="profile-badges">
        <span class="profile-pill">👤 <?= htmlspecialchars($displayName) ?></span>
        <span class="profile-pill">📧 <?= htmlspecialchars((string)($userRow['email'] ?? '')) ?></span>
        <span class="profile-pill subtle">🔐 <?= htmlspecialchars((string)($userRow['role'] ?? '')) ?></span>
      </div>
    </div>

    <div class="profile-hero-right">
      <div class="profile-avatar-card">
        <div class="profile-avatar-wrap">
          <?php if ($photo): ?>
            <img src="<?= Http::url((string)$photo) ?>" alt="Photo de profil" class="profile-avatar-img">
          <?php else: ?>
            <div class="profile-avatar-fallback"><?= $initial ?></div>
          <?php endif; ?>
        </div>

        <div class="profile-avatar-meta">
          <strong><?= htmlspecialchars($displayName) ?></strong>
          <span><?= htmlspecialchars((string)($userRow['email'] ?? '')) ?></span>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if (!empty($_GET['success'])): ?>
  <div class="card profile-alert success">
    <strong>OK</strong>
    <div class="muted">Photo mise à jour avec succès.</div>
  </div>
<?php endif; ?>

<?php if ($error !== ''): ?>
  <div class="card profile-alert error">
    <strong>Erreur</strong>
    <div class="muted"><?= htmlspecialchars($error) ?></div>
  </div>
<?php endif; ?>

<div class="profile-layout">
  <div class="profile-main">
    <div class="card profile-card">
      <div class="profile-section-head">
        <span class="profile-section-badge">Compte</span>
        <h2>Informations du profil</h2>
      </div>

      <div class="profile-info-grid">
        <div class="profile-info-item">
          <span class="profile-info-label">Nom complet</span>
          <strong><?= htmlspecialchars($displayName) ?></strong>
        </div>

        <div class="profile-info-item">
          <span class="profile-info-label">Email</span>
          <strong><?= htmlspecialchars((string)($userRow['email'] ?? '')) ?></strong>
        </div>

        <div class="profile-info-item full">
          <span class="profile-info-label">Rôle</span>
          <strong><?= htmlspecialchars((string)($userRow['role'] ?? '')) ?></strong>
        </div>
      </div>

      <div class="profile-security-tags">
        <span class="badge">CSRF actif</span>
        <span class="badge">Cookies session</span>
        <span class="badge">Upload sécurisé</span>
        <span class="badge">JPG / PNG / WEBP</span>
      </div>
    </div>

    <div class="card profile-card">
      <div class="profile-section-head">
        <span class="profile-section-badge">Sécurité</span>
        <h2>Protection du compte</h2>
      </div>

      <div class="profile-security-box">
        <div class="profile-security-icon">🛡️</div>
        <div>
          <strong>Compte protégé</strong>
          <p class="muted" style="margin:6px 0 0;">
            Le profil utilise des protections côté serveur comme les tokens CSRF,
            la gestion de session et le contrôle des accès selon les permissions.
          </p>
        </div>
      </div>
    </div>

    <div class="profile-back-row">
      <a class="btn" href="<?= Http::url('/') ?>">← Retour à l’accueil</a>
    </div>
  </div>

  <aside class="profile-side">
    <div class="card profile-side-card">
      <div class="profile-side-top">
        <span class="profile-side-badge">Photo</span>
        <h3>Mettre à jour l’avatar</h3>
        <p class="muted">Ajoute une photo de profil pour personnaliser ton espace.</p>
      </div>

      <div class="profile-upload-preview">
        <?php if ($photo): ?>
          <img src="<?= Http::url((string)$photo) ?>" alt="Photo de profil" class="profile-upload-img">
        <?php else: ?>
          <div class="profile-upload-placeholder"><?= $initial ?></div>
        <?php endif; ?>
      </div>

      <div class="profile-upload-help">
        Formats acceptés : JPG / PNG / WEBP<br>
        Taille maximale : 2 Mo
      </div>

      <form method="post" action="<?= Http::url('/profile/photo') ?>" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">

        <input class="input" type="file" name="photo" accept="image/jpeg,image/png,image/webp" required>

        <button class="btn btn-primary profile-upload-btn" type="submit">
          📷 Mettre à jour la photo
        </button>
      </form>
    </div>
  </aside>
</div>