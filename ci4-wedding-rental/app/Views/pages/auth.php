<?= $this->include('layout/header') ?>

<section class="min-h-screen bg-gray-50 py-20">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Tab Headers -->
            <div class="flex border-b mb-6">
                <button id="loginTab" class="flex-1 py-2 px-4 text-center border-b-2 border-purple-600 text-purple-600 font-semibold">
                    Masuk
                </button>
                <button id="registerTab" class="flex-1 py-2 px-4 text-center text-gray-500 hover:text-purple-600">
                    Daftar
                </button>
            </div>

            <!-- Login Form -->
            <div id="loginForm">
                <h2 class="text-2xl font-bold text-center mb-6">Masuk ke Akun Anda</h2>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="/auth/login" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Email
                        </label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500" 
                               id="email" name="email" type="email" placeholder="Masukkan email Anda" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                            Password
                        </label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500" 
                               id="password" name="password" type="password" placeholder="Masukkan password Anda" required>
                    </div>
                    <button class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-md" 
                            type="submit">
                        Masuk
                    </button>
                </form>
            </div>

            <!-- Register Form -->
            <div id="registerForm" class="hidden">
                <h2 class="text-2xl font-bold text-center mb-6">Buat Akun Baru</h2>
                
                <form action="/auth/register" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="reg_name">
                            Nama Lengkap
                        </label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500" 
                               id="reg_name" name="name" type="text" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="reg_email">
                            Email
                        </label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500" 
                               id="reg_email" name="email" type="email" placeholder="Masukkan email Anda" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="reg_phone">
                            No. Telepon
                        </label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500" 
                               id="reg_phone" name="phone" type="tel" placeholder="Masukkan nomor telepon">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="reg_password">
                            Password
                        </label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500" 
                               id="reg_password" name="password" type="password" placeholder="Masukkan password" required>
                    </div>
                    <button class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-md" 
                            type="submit">
                        Daftar
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    // Tab switching functionality
    document.getElementById('loginTab').addEventListener('click', function() {
        document.getElementById('loginForm').classList.remove('hidden');
        document.getElementById('registerForm').classList.add('hidden');
        this.classList.add('border-b-2', 'border-purple-600', 'text-purple-600', 'font-semibold');
        this.classList.remove('text-gray-500');
        document.getElementById('registerTab').classList.remove('border-b-2', 'border-purple-600', 'text-purple-600', 'font-semibold');
        document.getElementById('registerTab').classList.add('text-gray-500');
    });

    document.getElementById('registerTab').addEventListener('click', function() {
        document.getElementById('registerForm').classList.remove('hidden');
        document.getElementById('loginForm').classList.add('hidden');
        this.classList.add('border-b-2', 'border-purple-600', 'text-purple-600', 'font-semibold');
        this.classList.remove('text-gray-500');
        document.getElementById('loginTab').classList.remove('border-b-2', 'border-purple-600', 'text-purple-600', 'font-semibold');
        document.getElementById('loginTab').classList.add('text-gray-500');
    });
</script>

<?= $this->include('layout/footer') ?>