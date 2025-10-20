<?php

use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\PostController;

/** @var App\Core\Router $router */

$router->get('/admin', [PostController::class, 'index']);

$router->get('/admin/posts', [PostController::class, 'index']);
$router->get('/admin/posts/create', [PostController::class, 'create']);
$router->post('/admin/posts', [PostController::class, 'store']);
$router->get('/admin/posts/{id}/edit', [PostController::class, 'edit']);
$router->put('/admin/posts/{id}', [PostController::class, 'update']);
$router->delete('/admin/posts/{id}', [PostController::class, 'destroy']);

$router->get('/admin/categories', [CategoryController::class, 'index']);
$router->get('/admin/categories/create', [CategoryController::class, 'create']);
$router->post('/admin/categories', [CategoryController::class, 'store']);
$router->get('/admin/categories/{id}/edit', [CategoryController::class, 'edit']);
$router->put('/admin/categories/{id}', [CategoryController::class, 'update']);
$router->delete('/admin/categories/{id}', [CategoryController::class, 'destroy']);
