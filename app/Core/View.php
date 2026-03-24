<?php
declare(strict_types=1);

namespace App\Core;

class View
{
    public static function render(string $view, array $params = [], string $layout = 'main'): void
    {
        extract($params, EXTR_SKIP);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("Vue introuvable: $view");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require __DIR__ . '/../Views/layouts/' . $layout . '.php';
    }
}