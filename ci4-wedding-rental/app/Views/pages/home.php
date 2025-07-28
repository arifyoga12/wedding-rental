<?= $this->include('layout/header') ?>

<!-- Hero Section -->
<section class="hero-gradient min-h-screen flex items-center">
    <div class="max-w-7xl mx-auto px-4 py-20">
        <div class="text-center text-white">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                Wujudkan Pernikahan <br>
                <span class="text-pink-200">Impian Anda</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto text-gray-100">
                Dekorasi pernikahan berkualitas tinggi dengan desain elegan dan harga terjangkau.
                Jadikan hari istimewa Anda tak terlupakan.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/shop" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 inline-flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    Lihat Katalog
                </a>
                <a href="/contact" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition duration-300 inline-flex items-center">
                    <i class="fas fa-phone mr-2"></i>
                    Konsultasi Gratis
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Mengapa Memilih Kami?</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Kami berkomitmen memberikan pelayanan terbaik untuk hari bahagia Anda
            </p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-crown text-pink-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Desain Premium</h3>
                <p class="text-gray-600">Desain eksklusif yang dibuat khusus untuk setiap pasangan</p>
            </div>

            <div class="text-center">
                <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-handshake text-pink-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Tim Profesional</h3>
                <p class="text-gray-600">Tim berpengalaman yang siap membantu mewujudkan impian Anda</p>
            </div>

            <div class="text-center">
                <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tags text-pink-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Harga Terjangkau</h3>
                <p class="text-gray-600">Paket lengkap dengan harga yang sesuai budget Anda</p>
            </div>

            <div class="text-center">
                <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-pink-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Setup Tepat Waktu</h3>
                <p class="text-gray-600">Jaminan setup dekorasi selesai tepat waktu sebelum acara</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<?php if (!empty($featured_products)): ?>
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Produk Unggulan</h2>
            <p class="text-xl text-gray-600">Dekorasi terpopuler pilihan pasangan bahagia</p>
        </div>

        <div class="grid md:grid-cols-3 lg:grid-cols-3 gap-8">
            <?php foreach ($featured_products as $product): ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden card-hover">
                <div class="relative">
                    <img src="<?= $product['image'] ? '/uploads/' . $product['image'] : '/assets/images/placeholder.jpg' ?>" 
                         alt="<?= esc($product['name']) ?>" 
                         class="w-full h-64 object-cover">
                    
                    <?php if ($product['rating'] > 0): ?>
                    <div class="absolute top-4 left-4 bg-yellow-400 text-white px-2 py-1 rounded-full text-sm font-semibold">
                        <i class="fas fa-star mr-1"></i><?= number_format($product['rating'], 1) ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2 text-gray-800"><?= esc($product['name']) ?></h3>
                    <p class="text-gray-600 mb-3 text-sm"><?= esc(substr($product['description'], 0, 100)) ?>...</p>
                    
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-store mr-1"></i><?= esc($product['vendor_name'] ?? 'Tidak ada vendor') ?>
                        </span>
                        <span class="bg-pink-100 text-pink-800 text-xs font-semibold px-2 py-1 rounded-full">
                            <?= esc($product['category']) ?>
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-pink-600">
                            Rp <?= number_format($product['price'], 0, ',', '.') ?>
                        </span>
                        <a href="/shop/<?= $product['id'] ?>" 
                           class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition duration-200 text-sm">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-12">
            <a href="/shop" class="bg-pink-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-pink-700 transition duration-300 inline-flex items-center">
                <i class="fas fa-th-large mr-2"></i>
                Lihat Semua Produk
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Vendors Section -->
<?php if (!empty($featured_vendors)): ?>
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Vendor Terpercaya</h2>
            <p class="text-xl text-gray-600">Partner terbaik kami yang siap melayani Anda</p>
        </div>

        <div class="grid md:grid-cols-4 gap-6">
            <?php foreach (array_slice($featured_vendors, 0, 8) as $vendor): ?>
            <div class="bg-gray-50 rounded-lg p-6 text-center card-hover">
                <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <?php if (!empty($vendor['image'])): ?>
                        <img src="/uploads/<?= $vendor['image'] ?>" alt="<?= esc($vendor['name']) ?>" class="w-16 h-16 rounded-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-store text-pink-600 text-2xl"></i>
                    <?php endif; ?>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2"><?= esc($vendor['name']) ?></h3>
                
                <?php if ($vendor['rating'] > 0): ?>
                <div class="flex items-center justify-center mb-2">
                    <span class="text-yellow-400 text-sm mr-1">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star<?= $i <= $vendor['rating'] ? '' : '-o' ?>"></i>
                        <?php endfor; ?>
                    </span>
                    <span class="text-gray-600 text-sm">(<?= number_format($vendor['rating'], 1) ?>)</span>
                </div>
                <?php endif; ?>

                <?php if ($vendor['verified']): ?>
                <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">
                    <i class="fas fa-check-circle mr-1"></i>Terverifikasi
                </span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-12">
            <a href="/vendors" class="bg-pink-600 text-white px-8 py-4 rounded-lg font-semibold hover:bg-pink-700 transition duration-300 inline-flex items-center">
                <i class="fas fa-users mr-2"></i>
                Lihat Semua Vendor
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-20 bg-pink-600">
    <div class="max-w-4xl mx-auto px-4 text-center text-white">
        <h2 class="text-4xl font-bold mb-4">Siap Merencanakan Pernikahan Impian?</h2>
        <p class="text-xl mb-8">
            Tim ahli kami siap membantu mewujudkan dekorasi pernikahan yang sempurna untuk hari bahagia Anda.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/contact" class="bg-white text-pink-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 inline-flex items-center justify-center">
                <i class="fas fa-phone mr-2"></i>
                Hubungi Sekarang
            </a>
            <a href="/shop" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-pink-600 transition duration-300 inline-flex items-center justify-center">
                <i class="fas fa-shopping-bag mr-2"></i>
                Mulai Belanja
            </a>
        </div>
    </div>
</section>

<?= $this->include('layout/footer') ?>