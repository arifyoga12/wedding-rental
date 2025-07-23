<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Ensure path starts with /
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        // Debug untuk development
        error_log("Router - Method: $method, Path: $path");

        if (isset($this->routes[$method][$path])) {
            [$controller, $action] = $this->routes[$method][$path];
            $controllerInstance = new $controller();
            $controllerInstance->$action();
        } else {
            http_response_code(404);
            echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
            echo "<p>Path yang dicari: <code>" . htmlspecialchars($path) . "</code></p>";
            echo "<p><a href='/wedding-rental/'>Kembali ke Beranda</a></p>";
        }
    }
}