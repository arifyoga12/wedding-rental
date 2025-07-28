<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Product
{
    public string $id;
    public string $name;
    public float $price;
    public string $category;
    public string $image;
    public string $description;
    public ?string $vendor_id;
    public string $vendor;
    public float $rating;
    public bool $available;
    public string $created_at;

    public static function getAll(): array
    {
        try {
            error_log("Product::getAll - Starting query");
            $db = Database::getConnection();
            
            $stmt = $db->query("
                SELECT p.*, v.name as vendor_name 
                FROM products p 
                LEFT JOIN vendors v ON p.vendor_id = v.id 
                ORDER BY p.created_at DESC
            ");
            
            $products = [];
            while ($row = $stmt->fetch()) {
                $product = new self();
                $product->id = $row['id'];
                $product->name = $row['name'];
                $product->price = (float)$row['price'];
                $product->category = $row['category'];
                $product->image = $row['image'];
                $product->description = $row['description'];
                $product->vendor_id = $row['vendor_id'];
                $product->vendor = $row['vendor_name'] ?? 'Tidak ada vendor';
                $product->rating = (float)$row['rating'];
                $product->available = (bool)$row['available'];
                $product->created_at = $row['created_at'];
                $products[] = $product;
            }
            
            error_log("Product::getAll - Found " . count($products) . " products");
            return $products;
        } catch (PDOException $e) {
            error_log("Product::getAll - Database error: " . $e->getMessage());
            return [];
        }
    }

    public static function findById(string $id): ?self
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT p.*, v.name as vendor_name 
                FROM products p 
                LEFT JOIN vendors v ON p.vendor_id = v.id 
                WHERE p.id = ?
            ");
            $stmt->execute([$id]);
            
            $row = $stmt->fetch();
            if ($row) {
                $product = new self();
                $product->id = $row['id'];
                $product->name = $row['name'];
                $product->price = (float)$row['price'];
                $product->category = $row['category'];
                $product->image = $row['image'];
                $product->description = $row['description'];
                $product->vendor_id = $row['vendor_id'];
                $product->vendor = $row['vendor_name'] ?? 'Tidak ada vendor';
                $product->rating = (float)$row['rating'];
                $product->available = (bool)$row['available'];
                $product->created_at = $row['created_at'];
                return $product;
            }
            
            return null;
        } catch (PDOException $e) {
            error_log("Product::findById - Database error: " . $e->getMessage());
            return null;
        }
    }

    public static function search(string $term): array
    {
        try {
            error_log("Product::search - Searching for: " . $term);
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT p.*, v.name as vendor_name 
                FROM products p 
                LEFT JOIN vendors v ON p.vendor_id = v.id 
                WHERE p.name LIKE ? OR p.description LIKE ? OR p.category LIKE ? OR v.name LIKE ?
                ORDER BY p.rating DESC, p.created_at DESC
            ");
            $searchTerm = "%{$term}%";
            $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
            
            $products = [];
            while ($row = $stmt->fetch()) {
                $product = new self();
                $product->id = $row['id'];
                $product->name = $row['name'];
                $product->price = (float)$row['price'];
                $product->category = $row['category'];
                $product->image = $row['image'];
                $product->description = $row['description'];
                $product->vendor_id = $row['vendor_id'];
                $product->vendor = $row['vendor_name'] ?? 'Tidak ada vendor';
                $product->rating = (float)$row['rating'];
                $product->available = (bool)$row['available'];
                $product->created_at = $row['created_at'];
                $products[] = $product;
            }
            
            error_log("Product::search - Found " . count($products) . " products");
            return $products;
        } catch (PDOException $e) {
            error_log("Product::search - Database error: " . $e->getMessage());
            return [];
        }
    }

    public static function getByCategory(string $category): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT p.*, v.name as vendor_name 
                FROM products p 
                LEFT JOIN vendors v ON p.vendor_id = v.id 
                WHERE p.category = ?
                ORDER BY p.rating DESC, p.created_at DESC
            ");
            $stmt->execute([$category]);
            
            $products = [];
            while ($row = $stmt->fetch()) {
                $product = new self();
                $product->id = $row['id'];
                $product->name = $row['name'];
                $product->price = (float)$row['price'];
                $product->category = $row['category'];
                $product->image = $row['image'];
                $product->description = $row['description'];
                $product->vendor_id = $row['vendor_id'];
                $product->vendor = $row['vendor_name'] ?? 'Tidak ada vendor';
                $product->rating = (float)$row['rating'];
                $product->available = (bool)$row['available'];
                $product->created_at = $row['created_at'];
                $products[] = $product;
            }
            
            return $products;
        } catch (PDOException $e) {
            error_log("Product::getByCategory - Database error: " . $e->getMessage());
            return [];
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'category' => $this->category,
            'image' => $this->image,
            'description' => $this->description,
            'vendor_id' => $this->vendor_id,
            'vendor' => $this->vendor,
            'rating' => $this->rating,
            'available' => $this->available,
            'created_at' => $this->created_at,
        ];
    }
}