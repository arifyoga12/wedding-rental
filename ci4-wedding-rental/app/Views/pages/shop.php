<?= $this->include('layout/header') ?>

<section class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Katalog Dekorasi</h1>
            <p class="text-xl text-gray-600">Temukan dekorasi pernikahan terbaik untuk hari istimewa Anda</p>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" action="/shop" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="<?= esc($search ?? '') ?>" 
                           placeholder="Cari dekorasi..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500">
                </div>
                <div class="md:w-48">
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500">
                        <option value="">Semua Kategori</option>
                        <option value="pelaminan" <?= ($category ?? '') === 'pelaminan' ? 'selected' : '' ?>>Pelaminan</option>
                        <option value="bunga" <?= ($category ?? '') === 'bunga' ? 'selected' : '' ?>>Bunga</option>
                        <option value="dekorasi_meja" <?= ($category ?? '') === 'dekorasi_meja' ? 'selected' : '' ?>>Dekorasi Meja</option>
                        <option value="lighting" <?= ($category ?? '') === 'lighting' ? 'selected' : '' ?>>Lighting</option>
                        <option value="backdrop" <?= ($category ?? '') === 'backdrop' ? 'selected' : '' ?>>Backdrop</option>
                        <option value="karpet" <?= ($category ?? '') === 'karpet' ? 'selected' : '' ?>>Karpet</option>
                    </select>
                </div>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-md">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </form>
        </div>

        <!-- Results Count -->
        <?php if (isset($products) && !empty($products)): ?>
            <div class="mb-6">
                <p class="text-gray-600">Menampilkan <?= count($products) ?> produk</p>
            </div>
        <?php endif; ?>

        <!-- Products Grid -->
        <?php if (isset($products) && !empty($products)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
                <?php foreach ($products as $product): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                        <div class="relative">
                            <img src="<?= $product['image'] ? '/uploads/' . $product['image'] : '/assets/images/no-image.jpg' ?>" 
                                 alt="<?= esc($product['name']) ?>" 
                                 class="w-full h-48 object-cover">
                            <?php if (!$product['available']): ?>
                                <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-xs">
                                    Tidak Tersedia
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2 truncate"><?= esc($product['name']) ?></h3>
                            <p class="text-gray-600 text-sm mb-2"><?= ucfirst($product['category']) ?></p>
                            <div class="flex items-center justify-between">
                                <span class="text-purple-600 font-bold text-lg">
                                    Rp <?= number_format($product['price'], 0, ',', '.') ?>
                                </span>
                                <?php if (isset($product['rating']) && $product['rating']): ?>
                                    <div class="flex items-center text-yellow-500">
                                        <i class="fas fa-star text-xs"></i>
                                        <span class="text-sm ml-1"><?= number_format($product['rating'], 1) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <a href="/product/<?= $product['id'] ?>" 
                               class="block mt-3 bg-purple-600 hover:bg-purple-700 text-white text-center py-2 rounded-md transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if (isset($pager)): ?>
                <div class="flex justify-center">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- No Products Found -->
            <div class="text-center py-12">
                <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada produk ditemukan</h3>
                <p class="text-gray-500 mb-6">
                    <?php if (isset($search) && $search): ?>
                        Tidak ada hasil untuk pencarian "<?= esc($search) ?>"
                    <?php elseif (isset($category) && $category): ?>
                        Tidak ada produk dalam kategori "<?= ucfirst($category) ?>"
                    <?php else: ?>
                        Belum ada produk yang tersedia
                    <?php endif; ?>
                </p>
                <a href="/shop" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-md">
                    Lihat Semua Produk
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->include('layout/footer') ?>