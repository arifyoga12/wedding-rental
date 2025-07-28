<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table = 'vendors';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'description', 'phone', 'email', 'address', 
        'website', 'image', 'rating', 'verified'
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
        'description' => 'required|min_length[10]',
        'email' => 'permit_empty|valid_email',
        'phone' => 'permit_empty|max_length[20]',
        'rating' => 'permit_empty|decimal|greater_than_equal_to[0]|less_than_equal_to[5]',
        'verified' => 'permit_empty|in_list[0,1]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama vendor wajib diisi.',
            'min_length' => 'Nama vendor minimal 3 karakter.'
        ],
        'description' => [
            'required' => 'Deskripsi vendor wajib diisi.',
            'min_length' => 'Deskripsi minimal 10 karakter.'
        ],
        'email' => [
            'valid_email' => 'Format email tidak valid.'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;

    /**
     * Get all verified vendors
     */
    public function getVerifiedVendors(): array
    {
        return $this->where('verified', 1)
                    ->orderBy('rating', 'DESC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get vendor with product count
     */
    public function getVendorWithProductCount($vendorId): ?array
    {
        $vendor = $this->find($vendorId);
        if ($vendor) {
            $productModel = new ProductModel();
            $vendor['product_count'] = $productModel->where('vendor_id', $vendorId)
                                                  ->where('available', 1)
                                                  ->countAllResults();
        }
        return $vendor;
    }

    /**
     * Get vendors with pagination
     */
    public function getVendorsPaginated(int $perPage = 10): array
    {
        return $this->orderBy('rating', 'DESC')
                    ->orderBy('name', 'ASC')
                    ->paginate($perPage);
    }

    /**
     * Search vendors by name or description
     */
    public function searchVendors(string $term): array
    {
        $searchTerm = "%{$term}%";
        
        return $this->groupStart()
                        ->like('name', $searchTerm)
                        ->orLike('description', $searchTerm)
                        ->orLike('address', $searchTerm)
                    ->groupEnd()
                    ->orderBy('rating', 'DESC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Update vendor verification status
     */
    public function updateVerification(int $vendorId, bool $verified): bool
    {
        return $this->update($vendorId, ['verified' => $verified ? 1 : 0]);
    }

    /**
     * Update vendor rating
     */
    public function updateRating(int $vendorId, float $rating): bool
    {
        return $this->update($vendorId, ['rating' => $rating]);
    }
}