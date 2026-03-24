<?php
declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function role(): string
    {
        $u = self::user();
        return $u['role'] ?? 'ANON';
    }

    public static function requireLogin(): void
    {
        if (!self::user()) {
            header('Location: /login');
            exit;
        }
    }

    public static function requirePermission(string $sfxCode): void
    {
        $role = self::role();
        if (!Permissions::can($role, $sfxCode)) {
            http_response_code(403);
            echo "403 - Accès refusé";
            exit;
        }
    }
}