<?php
declare(strict_types=1);

namespace App\Core;

class Security
{
    public static function setSecurityHeaders(): void
    {
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=()');
    }
}