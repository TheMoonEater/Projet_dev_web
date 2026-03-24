<?php
declare(strict_types=1);

namespace App\Core;

final class Http
{
    public static function base(): string
    {
        return defined('BASE_PATH') ? rtrim(BASE_PATH, '/') : '';
    }

    public static function url(string $path): string
    {
        $path = '/' . ltrim($path, '/');           // force /xxx
        return self::base() . $path;               // /Projet.../public + /xxx
    }

    public static function redirect(string $path): void
    {
        header('Location: ' . self::url($path));
        exit;
    }
}