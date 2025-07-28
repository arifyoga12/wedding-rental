<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Wedding Decoration Rental' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <i class="fas fa-heart text-pink-600 text-2xl"></i>
                        <span class="text-xl font-bold text-gray-800">Wedding Rental</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-700 hover:text-pink-600 font-medium">Beranda</a>
                    <a href="/shop" class="text-gray-700 hover:text-pink-600 font-medium">Katalog</a>
                    <a href="/vendors" class="text-gray-700 hover:text-pink-600 font-medium">Vendor</a>
                    <a href="/about" class="text-gray-700 hover:text-pink-600 font-medium">Tentang</a>
                    <a href="/contact" class="text-gray-700 hover:text-pink-600 font-medium">Kontak</a>
                </div>

                <!-- User Actions -->
                <div class="flex items-center space-x-4">
                    <?php if (isset($user) && $user): ?>
                        <!-- User is logged in -->
                        <div class="relative group">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-pink-600">
                                <i class="fas fa-user-circle text-xl"></i>
                                <span class="font-medium"><?= esc($user['name']) ?></span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <div class="py-1">
                                    <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i>Profil
                                    </a>
                                    <a href="/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-shopping-bag mr-2"></i>Pesanan
                                    </a>
                                    <hr class="my-1">
                                    <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- User is not logged in -->
                        <a href="/auth" class="bg-pink-600 text-white px-4 py-2 rounded-md hover:bg-pink-700 transition duration-200">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                    <?php endif; ?>

                    <!-- Cart Icon -->
                    <a href="/cart" class="text-gray-700 hover:text-pink-600 relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span class="absolute -top-2 -right-2 bg-pink-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center cart-count">0</span>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-700 hover:text-pink-600" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                <a href="/" class="block px-3 py-2 text-gray-700 hover:text-pink-600">Beranda</a>
                <a href="/shop" class="block px-3 py-2 text-gray-700 hover:text-pink-600">Katalog</a>
                <a href="/vendors" class="block px-3 py-2 text-gray-700 hover:text-pink-600">Vendor</a>
                <a href="/about" class="block px-3 py-2 text-gray-700 hover:text-pink-600">Tentang</a>
                <a href="/contact" class="block px-3 py-2 text-gray-700 hover:text-pink-600">Kontak</a>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flash-message">
            <i class="fas fa-check-circle mr-2"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="fixed top-20 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flash-message">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="pt-16">