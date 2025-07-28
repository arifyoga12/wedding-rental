# Wedding Decoration Rental - CodeIgniter 4

Aplikasi web rental dekorasi pernikahan yang dibangun menggunakan CodeIgniter 4 dengan desain modern dan responsive.

## 🌟 Fitur Utama

- **Katalog Produk**: Menampilkan berbagai dekorasi pernikahan dengan kategori yang beragam
- **Sistem Autentikasi**: Login dan registrasi pengguna dengan enkripsi password
- **Pencarian & Filter**: Pencarian produk berdasarkan nama dan kategori
- **Rating & Review**: Sistem penilaian dan ulasan produk
- **Responsive Design**: Tampilan yang optimal di semua device
- **Admin Panel**: Manajemen produk, vendor, dan pesanan

## 🚀 Teknologi yang Digunakan

- **Framework**: CodeIgniter 4
- **Database**: MySQL
- **Frontend**: Tailwind CSS, FontAwesome
- **PHP Version**: 8.1+
- **Authentication**: Session-based dengan password hashing

## 📋 Persyaratan Sistem

- PHP 8.1 atau lebih tinggi
- MySQL 5.7+ atau MariaDB 10.3+
- Composer
- Web server (Apache/Nginx)
- Extensions PHP: curl, intl, mbstring, xml

## 🔧 Instalasi

### 1. Clone Repository
```bash
cd ci4-wedding-rental
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Environment
```bash
cp env .env
```

Edit file `.env` dan sesuaikan konfigurasi database:
```
database.default.hostname = localhost
database.default.database = wedding_rental
database.default.username = your_username
database.default.password = your_password
```

### 4. Setup Database
```bash
# Import database schema
mysql -u your_username -p wedding_rental < database.sql
```

### 5. Set Permissions
```bash
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

### 6. Run Development Server
```bash
php spark serve
```

Aplikasi akan berjalan di `http://localhost:8080`

## 📁 Struktur Project

```
ci4-wedding-rental/
├── app/
│   ├── Controllers/         # Controllers
│   │   ├── Home.php        # Homepage controller
│   │   ├── Auth.php        # Authentication controller
│   │   └── Shop.php        # Product catalog controller
│   ├── Models/             # Models
│   │   ├── UserModel.php   # User model
│   │   ├── ProductModel.php # Product model
│   │   └── VendorModel.php # Vendor model
│   ├── Views/              # Views
│   │   ├── layout/         # Layout templates
│   │   └── pages/          # Page views
│   └── Config/
│       └── Routes.php      # Route configuration
├── public/
│   ├── assets/            # CSS, JS, Images
│   └── uploads/           # Upload directory
├── database.sql           # Database schema
└── .env                   # Environment configuration
```

## 🎯 Endpoints API

### Authentication
- `GET /auth` - Halaman login/register
- `POST /auth/login` - Proses login
- `POST /auth/register` - Proses registrasi
- `GET /auth/logout` - Logout

### Products
- `GET /shop` - Katalog produk
- `GET /product/{id}` - Detail produk
- `GET /shop?search={query}` - Pencarian produk
- `GET /shop?category={category}` - Filter kategori

### Home
- `GET /` - Homepage
- `GET /about` - Halaman tentang
- `GET /contact` - Halaman kontak

## 🗄️ Database Schema

### Tabel Utama:
- **users**: Data pengguna
- **vendors**: Data vendor/penyedia
- **products**: Data produk dekorasi
- **orders**: Data pesanan
- **order_items**: Item dalam pesanan
- **payments**: Data pembayaran
- **reviews**: Review dan rating

### Kategori Produk:
- Pelaminan
- Bunga
- Dekorasi Meja
- Lighting
- Backdrop
- Karpet

## 👤 Default Login

Setelah import database, Anda dapat login dengan akun berikut:
- **Email**: john@example.com
- **Password**: password

## 🛠️ Development

### Menambah Controller Baru
```bash
php spark make:controller NamaController
```

### Menambah Model Baru
```bash
php spark make:model NamaModel
```

### Migration
```bash
php spark make:migration nama_migration
php spark migrate
```

## 🔐 Security

- CSRF Protection diaktifkan
- Password di-hash menggunakan PHP password_hash()
- Input validation pada semua form
- SQL injection prevention dengan Query Builder

## 📱 Responsive Design

Aplikasi menggunakan Tailwind CSS dengan breakpoints:
- Mobile: < 768px
- Tablet: 768px - 1024px
- Desktop: > 1024px

## 🎨 Kustomisasi

### Mengubah Tema Warna
Edit file `app/Views/layout/header.php` dan sesuaikan class Tailwind CSS.

### Menambah Kategori Produk
1. Update enum di database (tabel products)
2. Update pilihan di form `app/Views/pages/shop.php`
3. Update validation di `ProductModel.php`

## 📞 Support

Jika ada pertanyaan atau issue, silakan buat issue di repository ini atau hubungi tim development.

## 📄 License

Project ini menggunakan MIT License.

---

**Wedding Decoration Rental** - Mewujudkan pernikahan impian Anda dengan dekorasi berkualitas tinggi. ✨💒