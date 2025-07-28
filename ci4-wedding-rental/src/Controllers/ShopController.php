<?php

namespace App\Controllers;

use App\Models\Product;

class ShopController extends BaseController
{
    public function index(): void
    {
        $searchTerm = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        
        if ($searchTerm) {
            $products = Product::search($searchTerm);
        } else {
            $products = Product::getAll();
        }
        
        // Filter by category if specified
        if ($category) {
            $products = array_filter($products, function($product) use ($category) {
                return $product->category === $category;
            });
        }
        
        $categories = [
            ['id' => 'paket', 'name' => 'Paket Dekorasi', 'icon' => '🎊'],
            ['id' => 'pelaminan', 'name' => 'Pelaminan', 'icon' => '👑'],
            ['id' => 'fotografer', 'name' => 'Fotografer', 'icon' => '📸'],
            ['id' => 'musik', 'name' => 'Musik', 'icon' => '🎵'],
            ['id' => 'mua', 'name' => 'MUA', 'icon' => '💄'],
            ['id' => 'mc', 'name' => 'MC', 'icon' => '🎤']
        ];
        
        $this->render('pages/shop.twig', [
            'title' => 'Shop - Wedding Decoration Rental',
            'products' => $products,
            'categories' => $categories,
            'current_category' => $category,
            'search_term' => $searchTerm,
        ]);
    }

    public function search(): void
    {
        $searchTerm = $_GET['q'] ?? '';
        $category = $_GET['category'] ?? '';
        
        if (empty($searchTerm)) {
            $this->json(['success' => true, 'products' => []]);
            return;
        }
        
        $products = Product::search($searchTerm);
        
        // Filter by category if specified
        if ($category) {
            $products = array_filter($products, function($product) use ($category) {
                return $product->category === $category;
            });
        }
        
        // Convert to array format for JSON response
        $productsArray = array_map(function($product) {
            return $product->toArray();
        }, $products);
        
        $this->json([
            'success' => true,
            'products' => array_values($productsArray),
            'count' => count($productsArray)
        ]);
    }
}