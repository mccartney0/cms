<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): string
    {
        $viewPath = __DIR__ . '/../../resources/views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(500);
            return sprintf('View "%s" não encontrada.', $view);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $viewPath;
        return ob_get_clean();
    }

    public static function component(string $view, array $data = []): void
    {
        echo self::render($view, $data);
    }
}
