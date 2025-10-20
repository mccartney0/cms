<?php

use App\Controllers\Frontend\BlogController;
use App\Controllers\Frontend\HomeController;

/** @var App\Core\Router $router */

$router->get('/', [HomeController::class, 'index']);
$router->get('/blog', [BlogController::class, 'index']);
$router->get('/blog/{slug}', [BlogController::class, 'show']);
$router->get('/categoria/{slug}', [BlogController::class, 'category']);
