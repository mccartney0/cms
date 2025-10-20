<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, array $action): void
    {
        $this->add('GET', $uri, $action);
    }

    public function post(string $uri, array $action): void
    {
        $this->add('POST', $uri, $action);
    }

    public function put(string $uri, array $action): void
    {
        $this->add('PUT', $uri, $action);
    }

    public function delete(string $uri, array $action): void
    {
        $this->add('DELETE', $uri, $action);
    }

    public function add(string $method, string $uri, array $action): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'uri' => $this->normalizeUri($uri),
            'action' => $action,
        ];
    }

    public function load(string $file): void
    {
        if (file_exists($file)) {
            $router = $this;
            require $file;
        }
    }

    public function dispatch(Request $request, Response $response): void
    {
        $path = $request->path();
        $method = $request->method();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->match($route['uri'], $path);

            if ($params === null) {
                continue;
            }

            [$controller, $action] = $route['action'];
            $instance = new $controller($request, $response);
            $instance->$action(...$params);
            return;
        }

        $response->view('errors.404', ['title' => 'Página não encontrada'], 404);
    }

    private function normalizeUri(string $uri): string
    {
        return rtrim($uri, '/') ?: '/';
    }

    private function match(string $routeUri, string $path): ?array
    {
        $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[a-zA-Z0-9\-]+)', $routeUri);
        $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';

        if (preg_match($pattern, $path, $matches)) {
            $params = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[] = $value;
                }
            }
            return $params;
        }

        return null;
    }
}
