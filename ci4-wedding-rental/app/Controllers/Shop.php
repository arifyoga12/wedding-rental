<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Shop extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $category = $this->request->getGet('category');
        $perPage = 12;

        try {
            if ($search) {
                $products = $this->productModel->searchProducts($search);
                $pager = null; // Search results don't use pagination
            } elseif ($category) {
                $products = $this->productModel->getProductsByCategory($category);
                $pager = null; // Category results don't use pagination
            } else {
                $products = $this->productModel->getProductsPaginated($perPage);
                $pager = $this->productModel->pager;
            }

            // Get available categories for filter
            $categories = $this->productModel->getCategories();

            $data = [
                'title' => 'Katalog Produk - Wedding Decoration Rental',
                'products' => $products,
                'categories' => $categories,
                'pager' => $pager,
                'search' => $search,
                'selected_category' => $category,
                'user' => session()->get('user')
            ];

            return view('pages/shop', $data);

        } catch (\Exception $e) {
            log_message('error', 'Shop controller error: ' . $e->getMessage());
            
            $data = [
                'title' => 'Katalog Produk - Wedding Decoration Rental',
                'products' => [],
                'categories' => [],
                'pager' => null,
                'search' => $search,
                'selected_category' => $category,
                'user' => session()->get('user'),
                'error' => 'Terjadi kesalahan saat memuat produk.'
            ];

            return view('pages/shop', $data);
        }
    }

    public function detail($id)
    {
        try {
            $product = $this->productModel->findProductById($id);

            if (!$product) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan.');
            }

            // Get related products (same category, exclude current product)
            $relatedProducts = $this->productModel->getProductsByCategory($product['category']);
            $relatedProducts = array_filter($relatedProducts, function($p) use ($id) {
                return $p['id'] != $id;
            });
            $relatedProducts = array_slice($relatedProducts, 0, 4);

            $data = [
                'title' => $product['name'] . ' - Wedding Decoration Rental',
                'product' => $product,
                'related_products' => $relatedProducts,
                'user' => session()->get('user')
            ];

            return view('pages/product_detail', $data);

        } catch (\Exception $e) {
            log_message('error', 'Product detail error: ' . $e->getMessage());
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Produk tidak ditemukan.');
        }
    }

    public function api_search()
    {
        $search = $this->request->getGet('q');
        
        if (empty($search)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter pencarian diperlukan'
            ]);
        }

        try {
            $products = $this->productModel->searchProducts($search);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $products,
                'count' => count($products)
            ]);

        } catch (\Exception $e) {
            log_message('error', 'API search error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat pencarian'
            ]);
        }
    }

    public function categories()
    {
        try {
            $categories = $this->productModel->getCategories();
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Categories API error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat kategori'
            ]);
        }
    }
}