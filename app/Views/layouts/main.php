<?php
declare(strict_types=1);

use App\Core\Auth;
use App\Core\Http;
use App\Core\Csrf;
use App\Core\Permissions;

$user = Auth::user();
$role = Auth::role();

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

if (defined('BASE_PATH') && BASE_PATH !== '' && str_starts_with($currentPath, BASE_PATH)) {
    $currentPath = substr($currentPath, strlen(BASE_PATH));
    if ($currentPath === '') {
        $currentPath = '/';
    }
}

$isAuthPage = in_array($currentPath, ['/login'], true);

function nb_active(string $needle, string $currentPath): string {
    return str_contains($currentPath, $needle) ? 'nb-link-active' : '';
}
?>

<?php
$isAuthPage = in_array($currentPath, ['/login'], true);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Plateforme Stages') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Plateforme de gestion des stages CESI">
    <meta name="keywords" content="stage, cesi, entreprise, offre, candidature">

    <link rel="stylesheet" href="<?= Http::url('/assets/css/app.css') ?>?v=500">
</head>

<body>

<?php if (!$isAuthPage): ?>
<header class="nb-shell">
    <div class="container nb-wrap">

        <a href="<?= Http::url('/') ?>" class="nb-brand">
            <span class="nb-brand-icon">🎓</span>
            <span class="nb-brand-text">Stages CESI</span>
        </a>

        <button class="nb-burger" type="button" onclick="nbToggleMenu()" aria-label="Ouvrir le menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav id="nbMenu" class="nb-menu">
            <a class="nb-link <?= (!str_contains($currentPath, '/companies') && !str_contains($currentPath, '/offers') && !str_contains($currentPath, '/students') && !str_contains($currentPath, '/pilots') && !str_contains($currentPath, '/profile') && !str_contains($currentPath, '/wishlist') && !str_contains($currentPath, '/my-applications') && !str_contains($currentPath, '/pilot/applications')) ? 'nb-link-active' : '' ?>" href="<?= Http::url('/') ?>">Accueil</a>

            <a class="nb-link <?= nb_active('/companies', $currentPath) ?>" href="<?= Http::url('/companies') ?>">Entreprises</a>

            <a class="nb-link <?= (str_contains($currentPath, '/offers') && !str_contains($currentPath, '/offers/stats')) ? 'nb-link-active' : '' ?>" href="<?= Http::url('/offers') ?>">Offres</a>

<?php if ($user): ?>

    <?php if ($role === 'STUDENT'): ?>

        <?php if (Permissions::can($role, 'SFx21')): ?>
            <a class="nb-link <?= nb_active('/my-applications', $currentPath) ?>" href="<?= Http::url('/my-applications') ?>">Mes candidatures</a>
        <?php endif; ?>

        <?php if (Permissions::can($role, 'SFx23')): ?>
            <a class="nb-link <?= nb_active('/wishlist', $currentPath) ?>" href="<?= Http::url('/wishlist') ?>">Wish-list</a>
        <?php endif; ?>

        <?php if (Permissions::can($role, 'SFx11')): ?>
    <a class="nb-link <?= nb_active('/offers/stats', $currentPath) ?>" href="<?= Http::url('/offers/stats') ?>">
        Statistiques
    </a>
<?php endif; ?>

        <a class="nb-link <?= nb_active('/profile', $currentPath) ?>" href="<?= Http::url('/profile') ?>">Profil</a>

    <?php endif; ?>

<?php if ($role === 'PILOT'): ?>
    <details class="nb-dropdown">
        <summary class="nb-dropdown-toggle <?= (str_contains($currentPath, '/pilot/applications') || str_contains($currentPath, '/students') || str_contains($currentPath, '/pilot-students')) ? 'nb-link-active' : '' ?>">
            <span>Pilotage</span>
            <span class="nb-caret">▾</span>
        </summary>

        <div class="nb-dropdown-menu">
            <?php if (Permissions::can($role, 'SFx22')): ?>
                <a class="nb-dropdown-link <?= nb_active('/pilot/applications', $currentPath) ?>" href="<?= Http::url('/pilot/applications') ?>">
                    Candidatures élèves
                </a>
            <?php endif; ?>

            <?php if (Permissions::can($role, 'SFx16')): ?>
                <a class="nb-dropdown-link <?= nb_active('/students', $currentPath) ?>" href="<?= Http::url('/students') ?>">
                    Étudiants
                </a>
            <?php endif; ?>

            <a class="nb-dropdown-link <?= nb_active('/pilot-students', $currentPath) ?>" href="<?= Http::url('/pilot-students') ?>">
                Affectations
            </a>
        </div>
    </details>

    <?php if (Permissions::can($role, 'SFx11')): ?>
        <a class="nb-link <?= nb_active('/offers/stats', $currentPath) ?>" href="<?= Http::url('/offers/stats') ?>">Statistiques</a>
    <?php endif; ?>

    <a class="nb-link <?= nb_active('/profile', $currentPath) ?>" href="<?= Http::url('/profile') ?>">Profil</a>
<?php endif; ?>

    <?php if ($role === 'ADMIN'): ?>

        <?php if (Permissions::can($role, 'SFx11')): ?>
            <a class="nb-link <?= nb_active('/offers/stats', $currentPath) ?>" href="<?= Http::url('/offers/stats') ?>">Statistiques</a>
        <?php endif; ?>

        <?php if (Permissions::can($role, 'SFx12')): ?>
            <a class="nb-link <?= nb_active('/pilots', $currentPath) ?>" href="<?= Http::url('/pilots') ?>">Pilotes</a>
        <?php endif; ?>

        <?php if (Permissions::can($role, 'SFx16')): ?>
            <a class="nb-link <?= nb_active('/students', $currentPath) ?>" href="<?= Http::url('/students') ?>">Étudiants</a>
        <?php endif; ?>

        <a class="nb-link <?= nb_active('/pilot-students', $currentPath) ?>" href="<?= Http::url('/pilot-students') ?>">Affectations</a>

        <a class="nb-link <?= nb_active('/profile', $currentPath) ?>" href="<?= Http::url('/profile') ?>">Profil</a>

    <?php endif; ?>

    <div class="nb-userzone">
        <div class="nb-usercard">
            <div class="nb-avatar">
                <?= strtoupper(substr((string)$user['email'], 0, 1)) ?>
            </div>

            <div class="nb-usertext">
                <span class="nb-email"><?= htmlspecialchars((string)$user['email']) ?></span>
                <span class="nb-role"><?= htmlspecialchars((string)$role) ?></span>
            </div>
        </div>

        <form method="post" action="<?= Http::url('/logout') ?>" class="nb-logout-form">
            <input type="hidden" name="_csrf" value="<?= Csrf::token() ?>">
            <button type="submit" class="nb-logout-btn">Déconnexion</button>
        </form>
    </div>

<?php else: ?>

    <a class="nb-link <?= nb_active('/login', $currentPath) ?>" href="<?= Http::url('/login') ?>">Connexion</a>

<?php endif; ?>
        </nav>
    </div>
</header>
<?php endif; ?>

<main class="container" style="padding-top:22px;">
    <?= $content ?? '' ?>
</main>

<?php if (!$isAuthPage): ?>
<footer class="footer">
    <div class="container" style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
        <p>© <?= date('Y') ?> - Web4All | Projet CESI</p>
        <a href="<?= Http::url('/mentions-legales') ?>">Mentions légales</a>
    </div>
</footer>
<?php endif; ?>

<script src="<?= Http::url('/assets/js/app.js') ?>?v=500"></script>
</body>
</html>