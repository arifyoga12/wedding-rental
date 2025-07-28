<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Vendor;

class HomeController extends BaseController
{
    public function index(): void
    {
        try {
            // Get featured products (limit to 6)
            $allProducts = Product::getAll();
            error_log("HomeController: Found " . count($allProducts) . " products");
            
            $featuredProducts = array_slice($allProducts, 0, 6);
            error_log("HomeController: Showing " . count($featuredProducts) . " featured products");
        } catch (Exception $e) {
            error_log("HomeController error: " . $e->getMessage());
            $featuredProducts = [];
        }
        
        $this->render('pages/home.twig', [
            'title' => 'Wedding Decoration Rental',
            'featured_products' => $featuredProducts,
        ]);
    }
}