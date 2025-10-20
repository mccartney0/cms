<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $relativePath = BASE_PATH . '/src/' . str_replace('\\', '/', $relative) . '.php';

    if (file_exists($relativePath)) {
        require_once $relativePath;
    }
});

$config = require BASE_PATH . '/config/app.php';

App\Core\Config::set($config);

date_default_timezone_set($config['timezone'] ?? 'UTC');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
