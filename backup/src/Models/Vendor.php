<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Vendor
{
    public string $id;
    public string $name;
    public string $address;
    public ?string $logo;
    public string $description;
    public float $rating;
    public string $phone;
    public array $specialties;

    public static function getAll(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM vendors ORDER BY rating DESC");
        
        $vendors = [];
        while ($row = $stmt->fetch()) {
            $vendor = new self();
            $vendor->id = $row['id'];
            $vendor->name = $row['name'];
            $vendor->address = $row['address'];
            $vendor->logo = $row['logo'];
            $vendor->description = $row['description'];
            $vendor->rating = (float)$row['rating'];
            $vendor->phone = $row['phone'];
            $vendor->specialties = json_decode($row['specialties'], true) ?? [];
            $vendors[] = $vendor;
        }
        
        return $vendors;
    }

    public static function findById(string $id): ?self
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM vendors WHERE id = ?");
        $stmt->execute([$id]);
        
        $row = $stmt->fetch();
        if ($row) {
            $vendor = new self();
            $vendor->id = $row['id'];
            $vendor->name = $row['name'];
            $vendor->address = $row['address'];
            $vendor->logo = $row['logo'];
            $vendor->description = $row['description'];
            $vendor->rating = (float)$row['rating'];
            $vendor->phone = $row['phone'];
            $vendor->specialties = json_decode($row['specialties'], true) ?? [];
            return $vendor;
        }
        
        return null;
    }

    public static function search(string $term): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT * FROM vendors 
            WHERE name LIKE ? OR description LIKE ? OR address LIKE ?
            ORDER BY rating DESC
        ");
        $searchTerm = "%{$term}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        
        $vendors = [];
        while ($row = $stmt->fetch()) {
            $vendor = new self();
            $vendor->id = $row['id'];
            $vendor->name = $row['name'];
            $vendor->address = $row['address'];
            $vendor->logo = $row['logo'];
            $vendor->description = $row['description'];
            $vendor->rating = (float)$row['rating'];
            $vendor->phone = $row['phone'];
            $vendor->specialties = json_decode($row['specialties'], true) ?? [];
            $vendors[] = $vendor;
        }
        
        return $vendors;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'logo' => $this->logo,
            'description' => $this->description,
            'rating' => $this->rating,
            'phone' => $this->phone,
            'specialties' => $this->specialties,
        ];
    }
}