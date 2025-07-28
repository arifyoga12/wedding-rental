<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'price', 'category', 'image', 'description', 
        'vendor_id', 'rating', 'available'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'price' => 'required|numeric|greater_than[0]',
        'category' => 'required|max_length[100]',
        'description' => 'required|min_length[10]',
        'rating' => 'permit_empty|decimal|greater_than_equal_to[0]|less_than_equal_to[5]',
        'available' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama produk wajib diisi.',
            'min_length' => 'Nama produk minimal 3 karakter.'
        ],
        'price' => [
            'required' => 'Harga produk wajib diisi.',
            'numeric' => 'Harga harus berupa angka.',
            'greater_than' => 'Harga harus lebih dari 0.'
        ],
        'category' => [
            'required' => 'Kategori produk wajib diisi.'
        ],
        'description' => [
            'required' => 'Deskripsi produk wajib diisi.',
            'min_length' => 'Deskripsi minimal 10 karakter.'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;

    /**
     * Get all products with vendor information
     */
    public function getAllProducts(): array
    {
        return $this->select('products.*, vendors.name as vendor_name')
                    ->join('vendors', 'vendors.id = products.vendor_id', 'left')
                    ->orderBy('products.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get products with pagination and vendor information
     */
    public function getProductsPaginated(int $perPage = 12): array
    {
        return $this->select('products.*, vendors.name as vendor_name')
                    ->join('vendors', 'vendors.id = products.vendor_id', 'left')
                    ->where('products.available', 1)
                    ->orderBy('products.created_at', 'DESC')
                    ->paginate($perPage);
    }

    /**
     * Find product by ID with vendor information
     */
    public function findProductById($id): ?array
    {
        return $this->select('products.*, vendors.name as vendor_name')
                    ->join('vendors', 'vendors.id = products.vendor_id', 'left')
                    ->where('products.id', $id)
                    ->first();
    }

    /**
     * Search products by term
     */
    public function searchProducts(string $term): array
    {
        $searchTerm = "%{$term}%";
        
        return $this->select('products.*, vendors.name as vendor_name')
                    ->join('vendors', 'vendors.id = products.vendor_id', 'left')
                    ->groupStart()
                        ->like('products.name', $searchTerm)
                        ->orLike('products.description', $searchTerm)
                        ->orLike('products.category', $searchTerm)
                        ->orLike('vendors.name', $searchTerm)
                    ->groupEnd()
                    ->where('products.available', 1)
                    ->orderBy('products.rating', 'DESC')
                    ->orderBy('products.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get products by category
     */
    public function getProductsByCategory(string $category): array
    {
        return $this->select('products.*, vendors.name as vendor_name')
                    ->join('vendors', 'vendors.id = products.vendor_id', 'left')
                    ->where('products.category', $category)
                    ->where('products.available', 1)
                    ->orderBy('products.rating', 'DESC')
                    ->orderBy('products.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get featured products (highest rated, limited)
     */
    public function getFeaturedProducts(int $limit = 6): array
    {
        return $this->select('products.*, vendors.name as vendor_name')
                    ->join('vendors', 'vendors.id = products.vendor_id', 'left')
                    ->where('products.available', 1)
                    ->orderBy('products.rating', 'DESC')
                    ->orderBy('products.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get all available categories
     */
    public function getCategories(): array
    {
        return $this->select('category')
                    ->where('available', 1)
                    ->groupBy('category')
                    ->orderBy('category', 'ASC')
                    ->findAll();
    }

    /**
     * Update product availability
     */
    public function updateAvailability(int $productId, bool $available): bool
    {
        return $this->update($productId, ['available' => $available ? 1 : 0]);
    }

    /**
     * Get products by vendor
     */
    public function getProductsByVendor(int $vendorId): array
    {
        return $this->select('products.*, vendors.name as vendor_name')
                    ->join('vendors', 'vendors.id = products.vendor_id', 'left')
                    ->where('products.vendor_id', $vendorId)
                    ->orderBy('products.created_at', 'DESC')
                    ->findAll();
    }
}