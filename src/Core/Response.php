<?php

namespace App\Core;

class Response
{
    public function view(string $template, array $data = [], int $status = 200): void
    {
        http_response_code($status);
        echo View::render($template, $data);
    }

    public function redirect(string $to): void
    {
        header('Location: ' . $to);
        exit;
    }
}
