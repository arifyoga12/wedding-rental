<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class PaymentMethod
{
    public string $id;
    public string $bank_name;
    public string $account_number;
    public string $account_name;
    public string $type;
    public ?string $logo;
    public bool $is_active;

    public static function getActive(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM payment_methods WHERE is_active = TRUE ORDER BY type, bank_name");
        
        $methods = [];
        while ($row = $stmt->fetch()) {
            $method = new self();
            $method->id = $row['id'];
            $method->bank_name = $row['bank_name'];
            $method->account_number = $row['account_number'];
            $method->account_name = $row['account_name'];
            $method->type = $row['type'];
            $method->logo = $row['logo'];
            $method->is_active = (bool)$row['is_active'];
            $methods[] = $method;
        }
        
        return $methods;
    }

    public static function findById(string $id): ?self
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM payment_methods WHERE id = ? AND is_active = TRUE");
        $stmt->execute([$id]);
        
        $row = $stmt->fetch();
        if ($row) {
            $method = new self();
            $method->id = $row['id'];
            $method->bank_name = $row['bank_name'];
            $method->account_number = $row['account_number'];
            $method->account_name = $row['account_name'];
            $method->type = $row['type'];
            $method->logo = $row['logo'];
            $method->is_active = (bool)$row['is_active'];
            return $method;
        }
        
        return null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'type' => $this->type,
            'logo' => $this->logo,
            'is_active' => $this->is_active,
        ];
    }
}