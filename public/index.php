<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap/app.php';

use App\Core\Request;
use App\Core\Response;
use App\Core\Router;

$request = new Request();
$response = new Response();
$router = new Router();

$router->load(BASE_PATH . '/routes/web.php');
$router->load(BASE_PATH . '/routes/admin.php');

$router->dispatch($request, $response);
