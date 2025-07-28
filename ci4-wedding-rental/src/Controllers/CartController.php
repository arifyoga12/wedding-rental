<?php

namespace App\Controllers;

use App\Models\Product;

class CartController extends BaseController
{
    public function index(): void
    {
        $this->render('pages/cart.twig', [
            'title' => 'Shopping Cart - Wedding Decoration Rental',
        ]);
    }

    public function add(): void
    {
        // Parse JSON input if content type is JSON
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input) {
            $_POST = array_merge($_POST, $input);
        }
        
        $productId = $_POST['product_id'] ?? '';
        
        error_log("CartController::add - Product ID: " . $productId);
        error_log("CartController::add - POST data: " . json_encode($_POST));
        
        if (empty($productId)) {
            $this->json(['success' => false, 'message' => 'ID produk diperlukan'], 400);
            return;
        }

        $product = Product::findById($productId);
        if (!$product) {
            error_log("CartController::add - Product not found: " . $productId);
            $this->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
            return;
        }

        if (!$product->available) {
            error_log("CartController::add - Product not available: " . $productId);
            $this->json(['success' => false, 'message' => 'Produk tidak tersedia'], 400);
            return;
        }

        // Initialize cart if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add or update product in cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
            error_log("CartController::add - Updated quantity for product: " . $productId);
        } else {
            $_SESSION['cart'][$productId] = [
                'product' => $product->toArray(),
                'quantity' => 1
            ];
            error_log("CartController::add - Added new product to cart: " . $productId);
        }

        error_log("CartController::add - Cart contents: " . json_encode($_SESSION['cart']));
        $this->json(['success' => true, 'message' => 'Produk ditambahkan ke keranjang']);
    }

    public function remove(): void
    {
        // Parse JSON input if content type is JSON
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input) {
            $_POST = array_merge($_POST, $input);
        }
        
        $productId = $_POST['product_id'] ?? '';
        
        if (empty($productId)) {
            $this->json(['success' => false, 'message' => 'ID produk diperlukan'], 400);
            return;
        }

        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            $this->json(['success' => true, 'message' => 'Produk dihapus dari keranjang']);
        } else {
            $this->json(['success' => false, 'message' => 'Produk tidak ditemukan di keranjang'], 404);
        }
    }

    public function update(): void
    {
        // Parse JSON input if content type is JSON
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input) {
            $_POST = array_merge($_POST, $input);
        }
        
        $productId = $_POST['product_id'] ?? '';
        $quantity = (int)($_POST['quantity'] ?? 0);
        
        if (empty($productId) || $quantity < 0) {
            $this->json(['success' => false, 'message' => 'Parameter tidak valid'], 400);
            return;
        }

        if ($quantity === 0) {
            $this->remove();
            return;
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] = $quantity;
            $this->json(['success' => true, 'message' => 'Keranjang diperbarui']);
        } else {
            $this->json(['success' => false, 'message' => 'Produk tidak ditemukan di keranjang'], 404);
        }
    }

    public function get(): void
    {
        $cartItems = [];
        
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $productId => $item) {
                $cartItems[] = [
                    'id' => $productId,
                    'product' => $item['product'],
                    'quantity' => $item['quantity']
                ];
            }
        }
        
        $this->json(['success' => true, 'items' => $cartItems]);
    }
}