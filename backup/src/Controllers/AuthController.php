<?php

namespace App\Controllers;

use App\Models\User;

class AuthController extends BaseController
{
    public function index(): void
    {
        if ($this->isAuthenticated()) {
            header('Location: /');
            exit;
        }

        $this->render('pages/auth.twig', [
            'title' => 'Login / Register',
        ]);
    }

    public function login(): void
    {
        // Parse JSON input if content type is JSON
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input) {
            $_POST = array_merge($_POST, $input);
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        error_log("=== LOGIN ATTEMPT ===");
        error_log("Email: " . $email);
        error_log("Password length: " . strlen($password));
        error_log("POST data: " . json_encode($_POST));

        if (empty($email) || empty($password)) {
            error_log("LOGIN FAILED: Empty email or password");
            $this->json(['success' => false, 'message' => 'Email dan password wajib diisi'], 400);
            return;
        }

        try {
            error_log("Attempting to authenticate user: " . $email);
            $user = User::authenticate($email, $password);
            error_log("Authentication result: " . ($user ? "SUCCESS" : "FAILED"));
            
            if ($user) {
                error_log("User authenticated successfully: " . $user->name . " (" . $user->email . ")");
                $_SESSION['user'] = $user->toArray();
                error_log("Session user set: " . json_encode($_SESSION['user']));
                
                // Check if admin user
                if ($user->email === 'admin@wedding.com') {
                    error_log("ADMIN USER DETECTED - Redirecting to /admin");
                    $this->json([
                        'success' => true, 
                        'user' => $user->toArray(),
                        'redirect_url' => '/admin',
                        'is_admin' => true
                    ]);
                } else {
                    error_log("REGULAR USER - Redirecting to /");
                    $this->json([
                        'success' => true, 
                        'user' => $user->toArray(),
                        'redirect_url' => '/',
                        'is_admin' => false
                    ]);
                }
            } else {
                error_log("LOGIN FAILED: Invalid credentials for email: " . $email);
                $this->json(['success' => false, 'message' => 'Email atau password salah'], 401);
            }
        } catch (Exception $e) {
            error_log("LOGIN ERROR: " . $e->getMessage());
            error_log("LOGIN ERROR TRACE: " . $e->getTraceAsString());
            $this->json(['success' => false, 'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'], 500);
        }
    }

    public function register(): void
    {
        // Parse JSON input if content type is JSON
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input) {
            $_POST = array_merge($_POST, $input);
        }
        
        // Debug logging
        error_log("Registration attempt - POST data: " . json_encode($_POST));
        
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $phone = $_POST['phone'] ?? null;

        error_log("Registration data - Name: $name, Email: $email, Phone: $phone");

        if (empty($name) || empty($email) || empty($password)) {
            error_log("Registration failed - Missing required fields");
            $this->json(['success' => false, 'message' => 'Nama, email, dan password wajib diisi'], 400);
            return;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            error_log("Registration failed - Invalid email format: $email");
            $this->json(['success' => false, 'message' => 'Format email tidak valid'], 400);
            return;
        }

        if (strlen($password) < 6) {
            error_log("Registration failed - Password too short");
            $this->json(['success' => false, 'message' => 'Password minimal 6 karakter'], 400);
            return;
        }

        try {
            error_log("Checking if user exists with email: $email");
            // Check if user already exists
            $existingUser = User::findByEmail($email);
            if ($existingUser) {
                error_log("Registration failed - Email already exists: $email");
                $this->json(['success' => false, 'message' => 'Email sudah terdaftar'], 400);
                return;
            }

            error_log("Creating new user...");
            $user = User::create($name, $email, $password, $phone);
            if ($user) {
                error_log("User created successfully with ID: " . $user->id);
                    error_log("Regular user detected, redirecting to home");
                // Don't auto-login after registration, redirect to login
                $this->json(['success' => true, 'message' => 'Akun berhasil dibuat! Silakan login dengan akun Anda.', 'redirect_to_login' => true]);
            } else {
                error_log("Authentication failed for email: $email");
                error_log("User creation returned null");
                $this->json(['success' => false, 'message' => 'Pendaftaran gagal. Silakan coba lagi.'], 500);
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $this->json(['success' => false, 'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'], 500);
        }
    }

    public function logout(): void
    {
        session_destroy();
        $this->json(['success' => true]);
    }
}