<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\VendorModel;

class Home extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $vendorModel = new VendorModel();

        try {
            // Get featured products (limit to 6)
            $featuredProducts = $productModel->getFeaturedProducts(6);
            
            // Get verified vendors (limit to 8)
            $featuredVendors = $vendorModel->getVerifiedVendors();
            $featuredVendors = array_slice($featuredVendors, 0, 8);
            
            $data = [
                'title' => 'Wedding Decoration Rental',
                'featured_products' => $featuredProducts,
                'featured_vendors' => $featuredVendors,
                'user' => session()->get('user')
            ];

            return view('pages/home', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Home controller error: ' . $e->getMessage());
            
            $data = [
                'title' => 'Wedding Decoration Rental',
                'featured_products' => [],
                'featured_vendors' => [],
                'user' => session()->get('user')
            ];

            return view('pages/home', $data);
        }
    }

    public function about()
    {
        $data = [
            'title' => 'Tentang Kami - Wedding Decoration Rental',
            'user' => session()->get('user')
        ];

        return view('pages/about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Kontak - Wedding Decoration Rental',
            'user' => session()->get('user')
        ];

        return view('pages/contact', $data);
    }

    public function submitContact()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'subject' => 'required|min_length[5]|max_length[200]',
            'message' => 'required|min_length[10]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Here you would typically send an email or save to database
        // For now, we'll just show a success message
        
        return redirect()->to('/contact')->with('success', 'Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.');
    }
}
