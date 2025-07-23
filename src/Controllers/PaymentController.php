<?php

namespace App\Controllers;

use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Core\Database;

class PaymentController extends BaseController
{
    public function getPaymentMethods(): void
    {
        $methods = PaymentMethod::getActive();
        
        $this->json([
            'success' => true,
            'payment_methods' => array_map(function($method) {
                return $method->toArray();
            }, $methods)
        ]);
    }

    public function uploadProof(): void
    {
        $this->requireAuth();
        
        // Parse JSON input if content type is JSON
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input) {
            $_POST = array_merge($_POST, $input);
        }
        
        $orderId = $_POST['order_id'] ?? '';
        $paymentMethodId = $_POST['payment_method_id'] ?? '';
        $amount = (float)($_POST['amount'] ?? 0);
        $paymentType = $_POST['payment_type'] ?? 'full';
        $senderName = $_POST['sender_name'] ?? '';
        $transferDate = $_POST['transfer_date'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $proofImage = $_POST['proof_image'] ?? ''; // Base64 image data
        
        // Validation
        if (empty($orderId) || empty($paymentMethodId) || empty($amount) || 
            empty($senderName) || empty($transferDate) || empty($proofImage)) {
            $this->json(['success' => false, 'message' => 'Semua field wajib diisi'], 400);
            return;
        }
        
        // Verify order belongs to user
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $_SESSION['user']['id']]);
        $order = $stmt->fetch();
        
        if (!$order) {
            $this->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
            return;
        }
        
        // Verify payment method exists
        $paymentMethod = PaymentMethod::findById($paymentMethodId);
        if (!$paymentMethod) {
            $this->json(['success' => false, 'message' => 'Metode pembayaran tidak valid'], 400);
            return;
        }
        
        try {
            // Save uploaded image (in real app, use proper file upload)
            $imageName = 'payment_proof_' . $orderId . '_' . time() . '.jpg';
            $imagePath = '/uploads/payments/' . $imageName;
            
            // Create directory if not exists
            $uploadDir = __DIR__ . '/../../public/uploads/payments';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // For demo, we'll just store the image name
            // In real implementation, decode base64 and save file
            
            $paymentData = [
                'order_id' => $orderId,
                'payment_method_id' => $paymentMethodId,
                'amount' => $amount,
                'payment_type' => $paymentType,
                'proof_image' => $imagePath,
                'sender_name' => $senderName,
                'transfer_date' => $transferDate,
                'notes' => $notes
            ];
            
            $payment = Payment::create($paymentData);
            
            if ($payment) {
                $this->json([
                    'success' => true,
                    'message' => 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.',
                    'payment' => $payment->toArray()
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'Gagal menyimpan bukti pembayaran'], 500);
            }
            
        } catch (Exception $e) {
            error_log("Payment upload error: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
        }
    }

    public function getOrderPayments(): void
    {
        $this->requireAuth();
        
        $orderId = $_GET['order_id'] ?? '';
        
        if (empty($orderId)) {
            $this->json(['success' => false, 'message' => 'ID pesanan diperlukan'], 400);
            return;
        }
        
        // Verify order belongs to user
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $_SESSION['user']['id']]);
        $order = $stmt->fetch();
        
        if (!$order) {
            $this->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
            return;
        }
        
        $payments = Payment::getByOrderId($orderId);
        
        $this->json([
            'success' => true,
            'payments' => $payments
        ]);
    }
}