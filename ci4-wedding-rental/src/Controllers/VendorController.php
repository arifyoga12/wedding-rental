<?php

namespace App\Controllers;

use App\Models\Vendor;

class VendorController extends BaseController
{
    public function index(): void
    {
        $searchTerm = $_GET['search'] ?? '';
        
        if ($searchTerm) {
            $vendors = Vendor::search($searchTerm);
        } else {
            $vendors = Vendor::getAll();
        }
        
        $this->render('pages/vendors.twig', [
            'title' => 'Vendors - Wedding Decoration Rental',
            'vendors' => $vendors,
            'search_term' => $searchTerm,
        ]);
    }
}