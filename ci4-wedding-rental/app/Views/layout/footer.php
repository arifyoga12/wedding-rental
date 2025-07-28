    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1">
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-heart text-pink-600 text-2xl"></i>
                        <span class="text-xl font-bold">Wedding Rental</span>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Menyediakan dekorasi pernikahan berkualitas tinggi untuk hari istimewa Anda.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-pink-600">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-pink-600">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-pink-600">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-pink-600">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-span-1">
                    <h3 class="text-lg font-semibold mb-4">Link Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-300 hover:text-pink-600">Beranda</a></li>
                        <li><a href="/shop" class="text-gray-300 hover:text-pink-600">Katalog Produk</a></li>
                        <li><a href="/vendors" class="text-gray-300 hover:text-pink-600">Vendor</a></li>
                        <li><a href="/about" class="text-gray-300 hover:text-pink-600">Tentang Kami</a></li>
                        <li><a href="/contact" class="text-gray-300 hover:text-pink-600">Kontak</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div class="col-span-1">
                    <h3 class="text-lg font-semibold mb-4">Layanan</h3>
                    <ul class="space-y-2">
                        <li><a href="/shop?category=dekorasi" class="text-gray-300 hover:text-pink-600">Dekorasi Pernikahan</a></li>
                        <li><a href="/shop?category=bunga" class="text-gray-300 hover:text-pink-600">Bunga & Rangkaian</a></li>
                        <li><a href="/shop?category=furniture" class="text-gray-300 hover:text-pink-600">Furniture Acara</a></li>
                        <li><a href="/shop?category=lighting" class="text-gray-300 hover:text-pink-600">Lighting & Sound</a></li>
                        <li><a href="/shop?category=catering" class="text-gray-300 hover:text-pink-600">Catering Setup</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="col-span-1">
                    <h3 class="text-lg font-semibold mb-4">Kontak Kami</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-map-marker-alt text-pink-600"></i>
                            <span class="text-gray-300">Jl. Wedding Street No. 123, Jakarta</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-pink-600"></i>
                            <span class="text-gray-300">+62 21 1234 5678</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-pink-600"></i>
                            <span class="text-gray-300">info@weddingrental.com</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-clock text-pink-600"></i>
                            <span class="text-gray-300">Senin - Sabtu: 08:00 - 17:00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">
                    &copy; <?= date('Y') ?> Wedding Decoration Rental. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Auto-hide flash messages
        setTimeout(function() {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(function(message) {
                message.style.opacity = '0';
                setTimeout(function() {
                    message.remove();
                }, 300);
            });
        }, 5000);

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>