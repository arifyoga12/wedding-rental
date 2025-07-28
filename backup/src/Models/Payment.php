<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Payment
{
    public string $id;
    public string $order_id;
    public string $payment_method_id;
    public float $amount;
    public string $payment_type;
    public string $proof_image;
    public string $sender_name;
    public string $transfer_date;
    public ?string $notes;
    public string $status;
    public ?string $verified_at;
    public ?string $verified_by;
    public string $created_at;

    public static function create(array $data): ?self
    {
        try {
            $db = Database::getConnection();
            
            $stmt = $db->prepare("
                INSERT INTO payments (
                    order_id, payment_method_id, amount, payment_type, 
                    proof_image, sender_name, transfer_date, notes, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");
            
            $result = $stmt->execute([
                $data['order_id'],
                $data['payment_method_id'],
                $data['amount'],
                $data['payment_type'],
                $data['proof_image'],
                $data['sender_name'],
                $data['transfer_date'],
                $data['notes'] ?? null
            ]);
            
            if ($result) {
                $paymentId = $db->lastInsertId();
                
                // Update order payment status
                $updateStmt = $db->prepare("
                    UPDATE orders 
                    SET payment_status = ?, total_paid = total_paid + ?, remaining_amount = total - (total_paid + ?)
                    WHERE id = ?
                ");
                $updateStmt->execute([
                    $data['payment_type'] === 'full' ? 'full' : 'dp',
                    $data['amount'],
                    $data['amount'],
                    $data['order_id']
                ]);
                
                return self::findById($paymentId);
            }
            
            return null;
        } catch (PDOException $e) {
            error_log("Payment creation error: " . $e->getMessage());
            return null;
        }
    }

    public static function findById(string $id): ?self
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM payments WHERE id = ?");
        $stmt->execute([$id]);
        
        $row = $stmt->fetch();
        if ($row) {
            $payment = new self();
            $payment->id = $row['id'];
            $payment->order_id = $row['order_id'];
            $payment->payment_method_id = $row['payment_method_id'];
            $payment->amount = (float)$row['amount'];
            $payment->payment_type = $row['payment_type'];
            $payment->proof_image = $row['proof_image'];
            $payment->sender_name = $row['sender_name'];
            $payment->transfer_date = $row['transfer_date'];
            $payment->notes = $row['notes'];
            $payment->status = $row['status'];
            $payment->verified_at = $row['verified_at'];
            $payment->verified_by = $row['verified_by'];
            $payment->created_at = $row['created_at'];
            return $payment;
        }
        
        return null;
    }

    public static function getByOrderId(string $orderId): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT p.*, pm.bank_name, pm.account_number 
            FROM payments p
            LEFT JOIN payment_methods pm ON p.payment_method_id = pm.id
            WHERE p.order_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$orderId]);
        
        return $stmt->fetchAll();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'payment_method_id' => $this->payment_method_id,
            'amount' => $this->amount,
            'payment_type' => $this->payment_type,
            'proof_image' => $this->proof_image,
            'sender_name' => $this->sender_name,
            'transfer_date' => $this->transfer_date,
            'notes' => $this->notes,
            'status' => $this->status,
            'verified_at' => $this->verified_at,
            'verified_by' => $this->verified_by,
            'created_at' => $this->created_at,
        ];
    }
}