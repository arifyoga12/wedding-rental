<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // If user is already logged in, redirect to home
        if (session()->get('user')) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Login / Register - Wedding Decoration Rental'
        ];

        return view('pages/auth', $data);
    }

    public function login()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email dan password wajib diisi dengan format yang benar',
                'errors' => $validation->getErrors()
            ]);
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        try {
            $user = $this->userModel->authenticate($email, $password);
            
            if ($user) {
                // Set session
                session()->set('user', $user);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Login berhasil! Selamat datang ' . $user['name'],
                    'redirect' => '/'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Email atau password tidak valid'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Login error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ]);
        }
    }

    public function register()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'phone' => 'permit_empty|max_length[20]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data yang dimasukkan tidak valid',
                'errors' => $validation->getErrors()
            ]);
        }

        $userData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'phone' => $this->request->getPost('phone')
        ];

        try {
            $user = $this->userModel->createUser($userData);
            
            if ($user) {
                // Auto login after registration
                unset($user['password']);
                session()->set('user', $user);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Registrasi berhasil! Selamat datang ' . $user['name'],
                    'redirect' => '/'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal mendaftarkan akun. Silakan coba lagi.'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Register error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ]);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Anda telah berhasil logout.');
    }

    public function profile()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to('/auth')->with('error', 'Silakan login terlebih dahulu.');
        }

        $data = [
            'title' => 'Profil - Wedding Decoration Rental',
            'user' => $user
        ];

        return view('pages/profile', $data);
    }

    public function updateProfile()
    {
        $user = session()->get('user');
        if (!$user) {
            return redirect()->to('/auth')->with('error', 'Silakan login terlebih dahulu.');
        }

        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'phone' => 'permit_empty|max_length[20]'
        ];

        // Only validate password if it's provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $updateData = [
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone')
        ];

        if ($this->request->getPost('password')) {
            $updateData['password'] = $this->request->getPost('password');
        }

        try {
            if ($this->userModel->updateProfile($user['id'], $updateData)) {
                // Update session data
                $updatedUser = $this->userModel->find($user['id']);
                unset($updatedUser['password']);
                session()->set('user', $updatedUser);
                
                return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Update profile error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
}