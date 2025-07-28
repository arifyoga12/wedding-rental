<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\Router;
use App\Core\Database;
use App\Controllers\HomeController;
use App\Controllers\ShopController;
use App\Controllers\VendorController;
use App\Controllers\CartController;
use App\Controllers\OrderController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\ContactController;
use App\Controllers\PaymentController;

// Load environment variables
try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
} catch (Exception $e) {
    // Fallback to default values if .env file is not found
    $_ENV['DB_HOST'] = $_ENV['DB_HOST'] ?? 'localhost';
    $_ENV['DB_NAME'] = $_ENV['DB_NAME'] ?? 'wedding_rental';
    $_ENV['DB_USER'] = $_ENV['DB_USER'] ?? 'root';
    $_ENV['DB_PASS'] = $_ENV['DB_PASS'] ?? '';
    $_ENV['APP_ENV'] = $_ENV['APP_ENV'] ?? 'development';
    $_ENV['APP_DEBUG'] = $_ENV['APP_DEBUG'] ?? 'true';
}

// Start session
session_start();

// Initialize database
Database::init();

// Get the request URI and clean it
$requestUri = $_SERVER['REQUEST_URI'];

// Remove query string
$requestUri = strtok($requestUri, '?');

// Remove base path if present (adjust based on your setup)
$basePath = '';
if (strpos($_SERVER['REQUEST_URI'], '/wedding-rental') !== false) {
    $basePath = '/wedding-rental';
}

$requestUri = str_replace($basePath . '/public', '', $requestUri);
$requestUri = str_replace($basePath, '', $requestUri);

// Ensure it starts with /
if (!str_starts_with($requestUri, '/')) {
    $requestUri = '/' . $requestUri;
}

// If empty, set to home
if ($requestUri === '/' || $requestUri === '') {
    $requestUri = '/';
}

// Debug mode
if (isset($_GET['debug'])) {
    echo "<h3>Debug Info:</h3>";
    echo "Original REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
    echo "Processed URI: " . $requestUri . "<br>";
    echo "Method: " . $_SERVER['REQUEST_METHOD'] . "<br>";
    exit;
}

// Simple routing
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        switch ($requestUri) {
            case '/':
                $controller = new HomeController();
                $controller->index();
                break;
            case '/shop':
                $controller = new ShopController();
                $controller->index();
                break;
            case '/vendors':
                $controller = new VendorController();
                $controller->index();
                break;
            case '/cart':
                $controller = new CartController();
                $controller->index();
                break;
            case '/orders':
                $controller = new OrderController();
                $controller->index();
                break;
            case '/contact':
                $controller = new ContactController();
                $controller->index();
                break;
            case '/auth':
                $controller = new AuthController();
                $controller->index();
                break;
            case '/admin':
                $controller = new AdminController();
                $controller->index();
                break;
            case '/api/shop/search':
                $controller = new ShopController();
                $controller->search();
                break;
            case '/api/cart/get':
                $controller = new CartController();
                $controller->get();
                break;
            case '/api/orders/detail':
                $controller = new OrderController();
                $controller->getOrderDetail();
                break;
            case '/api/payment/methods':
                $controller = new PaymentController();
                $controller->getPaymentMethods();
                break;
            case '/api/payment/history':
                $controller = new PaymentController();
                $controller->getOrderPayments();
                break;
            case '/api/admin/reports/sales':
                $controller = new AdminController();
                $controller->getSalesReport();
                break;
            default:
                http_response_code(404);
                echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
                echo "<p>Path: <code>" . htmlspecialchars($requestUri) . "</code></p>";
                echo "<p><a href='" . $basePath . "/'>Kembali ke Beranda</a></p>";
        }
    } elseif ($method === 'POST') {
        switch ($requestUri) {
            case '/api/auth/login':
                $controller = new AuthController();
                $controller->login();
                break;
            case '/api/auth/register':
                $controller = new AuthController();
                $controller->register();
                break;
            case '/api/auth/logout':
                $controller = new AuthController();
                $controller->logout();
                break;
            case '/api/cart/add':
                $controller = new CartController();
                $controller->add();
                break;
            case '/api/cart/remove':
                $controller = new CartController();
                $controller->remove();
                break;
            case '/api/cart/update':
                $controller = new CartController();
                $controller->update();
                break;
            case '/api/orders/create':
                $controller = new OrderController();
                $controller->create();
                break;
            case '/api/payment/upload':
                $controller = new PaymentController();
                $controller->uploadProof();
                break;
            case '/api/admin/categories':
                $controller = new AdminController();
                $controller->createCategory();
                break;
            case '/api/admin/vendors':
                $controller = new AdminController();
                $controller->createVendor();
                break;
            case '/api/admin/products':
                $controller = new AdminController();
                $controller->createProduct();
                break;
            default:
                // Handle dynamic routes for admin
                if (preg_match('/^\/api\/admin\/categories\/(\d+)$/', $requestUri, $matches)) {
                    $controller = new AdminController();
                    $controller->updateCategory($matches[1]);
                } elseif (preg_match('/^\/api\/admin\/vendors\/(\d+)$/', $requestUri, $matches)) {
                    $controller = new AdminController();
                    $controller->updateVendor($matches[1]);
                } elseif (preg_match('/^\/api\/admin\/products\/(\d+)$/', $requestUri, $matches)) {
                    $controller = new AdminController();
                    $controller->updateProduct($matches[1]);
                } elseif (preg_match('/^\/api\/admin\/orders\/(\d+)\/status$/', $requestUri, $matches)) {
                    $controller = new AdminController();
                    $controller->updateOrderStatus($matches[1]);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Endpoint API tidak ditemukan']);
                }
        }
    } elseif ($method === 'PUT') {
        // Handle PUT requests for admin updates
        if (preg_match('/^\/api\/admin\/categories\/(\w+)$/', $requestUri, $matches)) {
            $controller = new AdminController();
            $controller->updateCategory($matches[1]);
        } elseif (preg_match('/^\/api\/admin\/vendors\/(\d+)$/', $requestUri, $matches)) {
            $controller = new AdminController();
            $controller->updateVendor($matches[1]);
        } elseif (preg_match('/^\/api\/admin\/products\/(\d+)$/', $requestUri, $matches)) {
            $controller = new AdminController();
            $controller->updateProduct($matches[1]);
        } elseif (preg_match('/^\/api\/admin\/orders\/(\d+)\/status$/', $requestUri, $matches)) {
            $controller = new AdminController();
            $controller->updateOrderStatus($matches[1]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint API tidak ditemukan']);
        }
    } elseif ($method === 'DELETE') {
        // Handle DELETE requests for admin
        if (preg_match('/^\/api\/admin\/categories\/(\w+)$/', $requestUri, $matches)) {
            $controller = new AdminController();
            $controller->deleteCategory($matches[1]);
        } elseif (preg_match('/^\/api\/admin\/vendors\/(\d+)$/', $requestUri, $matches)) {
            $controller = new AdminController();
            $controller->deleteVendor($matches[1]);
        } elseif (preg_match('/^\/api\/admin\/products\/(\d+)$/', $requestUri, $matches)) {
            $controller = new AdminController();
            $controller->deleteProduct($matches[1]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint API tidak ditemukan']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method tidak diizinkan']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "<h1>500 - Kesalahan Server</h1>";
    echo "<p>Kesalahan: " . htmlspecialchars($e->getMessage()) . "</p>";
    if ($_ENV['APP_DEBUG'] ?? false) {
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
}
?>
            default:
                http_response_code(404);
                echo json_encode(['error' => 'Endpoint API tidak ditemukan']);
        }
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "<h1>500 - Kesalahan Server</h1>";
    echo "<p>Kesalahan: " . htmlspecialchars($e->getMessage()) . "</p>";
    if ($_ENV['APP_DEBUG'] ?? false) {
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
}
?>