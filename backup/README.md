# Wedding Decoration Rental - PHP Version

Aplikasi Wedding Decoration Rental yang dimigrasi dari React/TypeScript ke PHP dengan fitur lengkap.

## 🚀 Fitur Utama

- ✅ **Authentication System** (Login/Register/Logout)
- ✅ **Product Catalog** dengan search dan filter
- ✅ **Shopping Cart** functionality
- ✅ **Order Management** 
- ✅ **Vendor Directory**
- ✅ **Admin Dashboard**
- ✅ **Responsive Design** dengan Tailwind CSS

## 📋 Persyaratan Sistem

- PHP 8.1 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Composer
- Web server (Apache/Nginx) atau PHP built-in server

## 🛠️ Instalasi

### 1. Clone atau Download Project

```bash
git clone <repository-url>
cd wedding-rental-php
```

### 2. Setup Database

#### Menggunakan XAMPP (Recommended):
1. Download dan install XAMPP dari https://www.apachefriends.org/
2. Jalankan XAMPP Control Panel
3. Start **Apache** dan **MySQL**
4. Buka http://localhost/phpmyadmin
5. Buat database baru dengan nama `wedding_rental`
6. Import file `database/schema.sql`

#### Menggunakan Command Line:
```bash
mysql -u root -p
CREATE DATABASE wedding_rental;
USE wedding_rental;
SOURCE database/schema.sql;
exit
```

### 3. Konfigurasi Environment

Copy file `.env.example` ke `.env`:
```bash
cp .env.example .env
```

Edit file `.env` sesuai konfigurasi database Anda:
```env
DB_HOST=localhost
DB_NAME=wedding_rental
DB_USER=root
DB_PASS=your_mysql_password
```

### 4. Install Dependencies

```bash
composer install
```

### 5. Jalankan Aplikasi

```bash
composer serve
```

Atau manual:
```bash
php -S localhost:8000 -t public
```

### 6. Akses Aplikasi

- **Website**: http://localhost:8000
- **Admin Login**: 
  - Email: `admin@wedding.com`
  - Password: `admin123`

## 📁 Struktur Project

```
wedding-rental-php/
├── public/
│   ├── index.php          # Entry point
│   └── assets/            # CSS, JS, images
├── src/
│   ├── Controllers/       # Application logic
│   ├── Models/           # Database models
│   └── Core/             # Router, Database core
├── templates/
│   ├── pages/            # Page templates
│   ├── components/       # Reusable components
│   └── layout/           # Base layout
├── database/
│   └── schema.sql        # Database schema
├── .env                  # Database configuration
└── composer.json         # PHP dependencies
```

## 🎯 Testing Aplikasi

1. **Homepage**: Lihat featured products
2. **Shop**: Browse dan search produk
3. **Vendors**: Lihat daftar vendor
4. **Register**: Buat akun baru
5. **Login**: Masuk dengan akun
6. **Add to Cart**: Tambah produk ke keranjang
7. **Checkout**: Buat pesanan
8. **Admin**: Login sebagai admin untuk dashboard

## 🔧 Troubleshooting

### Error: "could not find driver"
Install PHP MySQL extension:
```bash
# Ubuntu/Debian
sudo apt-get install php-mysql php-pdo-mysql

# Windows: Uncomment di php.ini
extension=pdo_mysql
```

### Error: "Access denied for user"
Periksa konfigurasi database di `.env` dan pastikan MySQL service berjalan.

### Error: "Class not found"
Jalankan:
```bash
composer dump-autoload
```

## 🛡️ Security

- Password di-hash menggunakan PHP `password_hash()`
- Prepared statements untuk mencegah SQL injection
- Session-based authentication
- CSRF protection (bisa ditambahkan)

## 📝 API Endpoints

- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout
- `POST /api/cart/add` - Add item to cart
- `POST /api/cart/remove` - Remove item from cart
- `POST /api/cart/update` - Update cart quantity
- `POST /api/orders/create` - Create new order

## 🎨 Frontend Technologies

- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Twig** - Template engine
- **Font Awesome** - Icons

## 📄 License

MIT License - feel free to use this project for learning or commercial purposes.

## 🤝 Contributing

1. Fork the project
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## 📞 Support

Jika ada pertanyaan atau masalah, silakan buat issue di repository ini.