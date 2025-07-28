<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Vendor;
use App\Models\User;
use App\Core\Database;

class AdminController extends BaseController
{
    public function index(): void
    {
        // Check if user is authenticated and is admin
        if (!$this->isAuthenticated()) {
            header('Location: ' . $this->getBaseUrl() . '/auth');
            exit;
        }
        
        // Check if user is admin
        if ($_SESSION['user']['email'] !== 'admin@wedding.com') {
            // Redirect non-admin users to home page
            header('Location: ' . $this->getBaseUrl() . '/');
            exit;
        }
        
        $stats = $this->getAdminStats();
        $vendors = Vendor::getAll();
        $products = $this->getAllProductsWithVendor();
        $orders = $this->getAllOrdersWithUser();
        
        $this->render('pages/admin.twig', [
            'title' => 'Admin Dashboard - Wedding Decoration Rental',
            'stats' => $stats,
            'vendors' => $vendors,
            'products' => $products,
            'orders' => $orders,
            'current_page' => 'admin',
        ]);
    }

    public function createCategory(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $name = $input['name'] ?? '';
        $icon = $input['icon'] ?? '';
        
        if (empty($name)) {
            $this->json(['success' => false, 'message' => 'Nama kategori wajib diisi'], 400);
            return;
        }
        
        // For demo purposes, we'll just return success
        // In real app, you'd save to database
        $this->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan']);
    }

    public function updateCategory(string $id): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $name = $input['name'] ?? '';
        $icon = $input['icon'] ?? '';
        
        if (empty($name)) {
            $this->json(['success' => false, 'message' => 'Nama kategori wajib diisi'], 400);
            return;
        }
        
        // For demo purposes, we'll just return success
        $this->json(['success' => true, 'message' => 'Kategori berhasil diperbarui']);
    }

    public function deleteCategory(string $id): void
    {
        // For demo purposes, we'll just return success
        $this->json(['success' => true, 'message' => 'Kategori berhasil dihapus']);
    }

    public function createVendor(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $name = $input['name'] ?? '';
        $address = $input['address'] ?? '';
        $phone = $input['phone'] ?? '';
        $description = $input['description'] ?? '';
        $rating = (float)($input['rating'] ?? 4.5);
        $logo = $input['logo'] ?? '';
        $specialties = $input['specialties'] ?? [];
        
        if (empty($name) || empty($address) || empty($phone)) {
            $this->json(['success' => false, 'message' => 'Nama, alamat, dan telepon wajib diisi'], 400);
            return;
        }
        
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO vendors (name, address, phone, description, rating, logo, specialties, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $result = $stmt->execute([
                $name,
                $address,
                $phone,
                $description,
                $rating,
                $logo,
                json_encode($specialties)
            ]);
            
            if ($result) {
                $this->json(['success' => true, 'message' => 'Vendor berhasil ditambahkan']);
            } else {
                $this->json(['success' => false, 'message' => 'Gagal menambahkan vendor'], 500);
            }
        } catch (Exception $e) {
            error_log("Create vendor error: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function updateVendor(string $id): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $name = $input['name'] ?? '';
        $address = $input['address'] ?? '';
        $phone = $input['phone'] ?? '';
        $description = $input['description'] ?? '';
        $rating = (float)($input['rating'] ?? 4.5);
        $logo = $input['logo'] ?? '';
        $specialties = $input['specialties'] ?? [];
        
        if (empty($name) || empty($address) || empty($phone)) {
            $this->json(['success' => false, 'message' => 'Nama, alamat, dan telepon wajib diisi'], 400);
            return;
        }
        
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                UPDATE vendors 
                SET name = ?, address = ?, phone = ?, description = ?, rating = ?, logo = ?, specialties = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            $result = $stmt->execute([
                $name,
                $address,
                $phone,
                $description,
                $rating,
                $logo,
                json_encode($specialties),
                $id
            ]);
            
            if ($result) {
                $this->json(['success' => true, 'message' => 'Vendor berhasil diperbarui']);
            } else {
                $this->json(['success' => false, 'message' => 'Gagal memperbarui vendor'], 500);
            }
        } catch (Exception $e) {
            error_log("Update vendor error: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function deleteVendor(string $id): void
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM vendors WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            if ($result) {
                $this->json(['success' => true, 'message' => 'Vendor berhasil dihapus']);
            } else {
                $this->json(['success' => false, 'message' => 'Gagal menghapus vendor'], 500);
            }
        } catch (Exception $e) {
            error_log("Delete vendor error: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function createProduct(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $name = $input['name'] ?? '';
        $price = (float)($input['price'] ?? 0);
        $category = $input['category'] ?? '';
        $image = $input['image'] ?? '';
        $description = $input['description'] ?? '';
        $vendor_id = $input['vendor_id'] ?? null;
        $rating = (float)($input['rating'] ?? 4.5);
        $available = (bool)($input['available'] ?? true);
        
        if (empty($name) || empty($category) || empty($image) || $price <= 0) {
            $this->json(['success' => false, 'message' => 'Nama, kategori, gambar, dan harga wajib diisi'], 400);
            return;
        }
        
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO products (name, price, category, image, description, vendor_id, rating, available, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $result = $stmt->execute([
                $name,
                $price,
                $category,
                $image,
                $description,
                $vendor_id ?: null,
                $rating,
                $available
            ]);
            
            if ($result) {
                $this->json(['success' => true, 'message' => 'Produk berhasil ditambahkan']);
            } else {
                $this->json(['success' => false, 'message' => 'Gagal menambahkan produk'], 500);
            }
        } catch (Exception $e) {
            error_log("Create product error: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function updateProduct(string $id): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $name = $input['name'] ?? '';
        $price = (float)($input['price'] ?? 0);
        $category = $input['category'] ?? '';
        $image = $input['image'] ?? '';
        $description = $input['description'] ?? '';
        $vendor_id = $input['vendor_id'] ?? null;
        $rating = (float)($input['rating'] ?? 4.5);
        $available = (bool)($input['available'] ?? true);
        
        if (empty($name) || empty($category) || empty($image) || $price <= 0) {
            $this->json(['success' => false, 'message' => 'Nama, kategori, gambar, dan harga wajib diisi'], 400);
            return;
        }
        
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                UPDATE products 
                SET name = ?, price = ?, category = ?, image = ?, description = ?, vendor_id = ?, rating = ?, available = ?, updated_at = NOW()
                WHERE id = ?
            ");
            
            $result = $stmt->execute([
                $name,
                $price,
                $category,
                $image,
                $description,
                $vendor_id ?: null,
                $rating,
                $available,
                $id
            ]);
            
            if ($result) {
                $this->json(['success' => true, 'message' => 'Produk berhasil diperbarui']);
            } else {
                $this->json(['success' => false, 'message' => 'Gagal memperbarui produk'], 500);
            }
        } catch (Exception $e) {
            error_log("Update product error: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function deleteProduct(string $id): void
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            if ($result) {
                $this->json(['success' => true, 'message' => 'Produk berhasil dihapus']);
            } else {
                $this->json(['success' => false, 'message' => 'Gagal menghapus produk'], 500);
            }
        } catch (Exception $e) {
            error_log("Delete product error: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function updateOrderStatus(string $id): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $status = $input['status'] ?? '';
        
        if (empty($status)) {
            $this->json(['success' => false, 'message' => 'Status wajib diisi'], 400);
            return;
        }
        
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
            $result = $stmt->execute([$status, $id]);
            
            if ($result) {
                $this->json(['success' => true, 'message' => 'Status pesanan berhasil diperbarui']);
            } else {
                $this->json(['success' => false, 'message' => 'Gagal memperbarui status'], 500);
            }
        } catch (Exception $e) {
            error_log("Update order status error: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function getSalesReport(): void
    {
        $startDate = $_GET['start_date'] ?? '';
        $endDate = $_GET['end_date'] ?? '';
        $category = $_GET['category'] ?? '';
        
        if (empty($startDate) || empty($endDate)) {
            $this->json(['success' => false, 'message' => 'Tanggal mulai dan akhir wajib diisi'], 400);
            return;
        }
        
        try {
            $db = Database::getConnection();
            
            // Build query with optional category filter
            $whereClause = "WHERE o.created_at BETWEEN ? AND ?";
            $params = [$startDate . ' 00:00:00', $endDate . ' 23:59:59'];
            
            if ($category) {
                $whereClause .= " AND p.category = ?";
                $params[] = $category;
            }
            
            // Get sales summary
            $stmt = $db->prepare("
                SELECT 
                    COUNT(DISTINCT o.id) as total_orders,
                    COALESCE(SUM(o.total), 0) as total_sales,
                    COALESCE(AVG(o.total), 0) as average_order
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                LEFT JOIN products p ON oi.product_id = p.id
                $whereClause
            ");
            $stmt->execute($params);
            $summary = $stmt->fetch();
            
            // Get top products
            $stmt = $db->prepare("
                SELECT 
                    p.id,
                    p.name,
                    p.category,
                    SUM(oi.quantity) as total_sold,
                    SUM(oi.quantity * oi.price) as total_revenue
                FROM products p
                JOIN order_items oi ON p.id = oi.product_id
                JOIN orders o ON oi.order_id = o.id
                $whereClause
                GROUP BY p.id, p.name, p.category
                ORDER BY total_sold DESC
                LIMIT 10
            ");
            $stmt->execute($params);
            $topProducts = $stmt->fetchAll();
            
            $this->json([
                'success' => true,
                'data' => [
                    'totalOrders' => (int)$summary['total_orders'],
                    'totalSales' => (float)$summary['total_sales'],
                    'averageOrder' => (float)$summary['average_order'],
                    'topProducts' => $topProducts
                ]
            ]);
            
        } catch (Exception $e) {
            error_log("Sales report error: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }
    private function getBaseUrl(): string
    {
        return strpos($_SERVER['REQUEST_URI'], '/wedding-rental') !== false ? '/wedding-rental' : '';
    }

    private function getAdminStats(): array
    {
        $db = Database::getConnection();
        
        // Get total counts
        $totalProducts = $db->query("SELECT COUNT(*) as count FROM products")->fetch()['count'];
        $totalVendors = $db->query("SELECT COUNT(*) as count FROM vendors")->fetch()['count'];
        $totalOrders = $db->query("SELECT COUNT(*) as count FROM orders")->fetch()['count'];
        $totalUsers = $db->query("SELECT COUNT(*) as count FROM users")->fetch()['count'];
        
        // Get recent orders
        $recentOrders = $db->query("
            SELECT o.*, u.name as user_name 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC 
            LIMIT 5
        ")->fetchAll();
        
        // Get recent products
        $recentProducts = $db->query("
            SELECT p.*, v.name as vendor_name 
            FROM products p 
            LEFT JOIN vendors v ON p.vendor_id = v.id 
            ORDER BY p.created_at DESC 
            LIMIT 10
        ")->fetchAll();
        
        return [
            'total_products' => $totalProducts,
            'total_vendors' => $totalVendors,
            'total_orders' => $totalOrders,
            'total_users' => $totalUsers,
            'recent_orders' => $recentOrders,
            'recent_products' => $recentProducts,
        ];
    }

    private function getAllProductsWithVendor(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT p.*, v.name as vendor_name 
            FROM products p 
            LEFT JOIN vendors v ON p.vendor_id = v.id 
            ORDER BY p.created_at DESC
        ");
        
        return $stmt->fetchAll();
    }

    private function getAllOrdersWithUser(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT o.*, u.name as user_name, u.email as user_email
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC
        ");
        
        return $stmt->fetchAll();
    }
}