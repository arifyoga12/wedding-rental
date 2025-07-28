<?php

namespace App\Controllers;

use App\Core\Database;

class OrderController extends BaseController
{
    public function index(): void
    {
        $this->requireAuth();
        
        $orders = $this->getUserOrders();
        
        $this->render('pages/orders.twig', [
            'title' => 'My Orders - Wedding Decoration Rental',
            'orders' => $orders,
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        
        // Parse JSON input if content type is JSON
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input) {
            $_POST = array_merge($_POST, $input);
        }
        
        $eventDate = $_POST['event_date'] ?? '';
        $eventLocation = $_POST['event_location'] ?? '';
        
        error_log("OrderController::create - Event Date: '$eventDate', Event Location: '$eventLocation'");
        
        if (empty($eventDate) || empty(trim($eventLocation))) {
            $this->json(['success' => false, 'message' => 'Tanggal dan lokasi acara wajib diisi'], 400);
            return;
        }

        if (empty($_SESSION['cart'])) {
            $this->json(['success' => false, 'message' => 'Keranjang kosong'], 400);
            return;
        }

        $db = Database::getConnection();
        
        try {
            $db->beginTransaction();
            
            // Calculate total
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['product']['price'] * $item['quantity'];
            }
            
            // Create order
            $stmt = $db->prepare("
                INSERT INTO orders (user_id, total, event_date, event_location, status, payment_status, created_at) 
                VALUES (?, ?, ?, ?, 'pending', 'none', NOW())
            ");
            $stmt->execute([
                $_SESSION['user']['id'],
                $total,
                $eventDate,
                trim($eventLocation)
            ]);
            
            $orderId = $db->lastInsertId();
            
            // Create order items
            $stmt = $db->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            
            foreach ($_SESSION['cart'] as $productId => $item) {
                $stmt->execute([
                    $orderId,
                    $productId,
                    $item['quantity'],
                    $item['product']['price']
                ]);
            }
            
            $db->commit();
            
            // Clear cart
            $_SESSION['cart'] = [];
            
            error_log("OrderController::create - Order created successfully with ID: $orderId");
            $this->json(['success' => true, 'message' => 'Pesanan berhasil dibuat', 'order_id' => $orderId]);
            
        } catch (Exception $e) {
            error_log("OrderController::create - Error: " . $e->getMessage());
            $db->rollBack();
            $this->json(['success' => false, 'message' => 'Gagal membuat pesanan'], 500);
        }
    }

    private function getUserOrders(): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT o.*, 
                   GROUP_CONCAT(
                       CONCAT(oi.quantity, 'x ', p.name) 
                       SEPARATOR ', '
                   ) as items_summary
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE o.user_id = ?
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$_SESSION['user']['id']]);
        
        return $stmt->fetchAll();
    }

    public function getOrderDetail(): void
    {
        $this->requireAuth();
        
        $orderId = $_GET['id'] ?? '';
        
        if (empty($orderId)) {
            $this->json(['success' => false, 'message' => 'ID pesanan diperlukan'], 400);
            return;
        }
        
        $db = Database::getConnection();
        
        // Get order details
        $stmt = $db->prepare("
            SELECT o.*, u.name as user_name, u.email as user_email
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.id = ? AND o.user_id = ?
        ");
        $stmt->execute([$orderId, $_SESSION['user']['id']]);
        $order = $stmt->fetch();
        
        if (!$order) {
            $this->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
            return;
        }
        
        // Get order items
        $stmt = $db->prepare("
            SELECT oi.*, p.name as product_name, p.image as product_image, p.category
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll();
        
        $this->json([
            'success' => true,
            'order' => $order,
            'items' => $items
        ]);
    }
}