<?php

namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class BaseController
{
    protected Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader);

        // Determine base URL
        $baseUrl = '';
        if (strpos($_SERVER['REQUEST_URI'], '/wedding-rental') !== false) {
            $baseUrl = '/wedding-rental';
        }

        // Add global variables
        $this->twig->addGlobal('user', $this->getCurrentUser());
        $this->twig->addGlobal('cart_count', $this->getCartCount());
        $this->twig->addGlobal('base_url', $baseUrl);
    }

    protected function render(string $template, array $data = []): void
    {
        echo $this->twig->render($template, $data);
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    protected function getCurrentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    protected function getCartCount(): int
    {
        return count($_SESSION['cart'] ?? []);
    }

    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            header('Location: /auth');
            exit;
        }
    }
}