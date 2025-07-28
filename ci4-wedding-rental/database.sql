-- Database Schema for Wedding Decoration Rental System (CodeIgniter 4)
-- Create database
CREATE DATABASE IF NOT EXISTS wedding_rental;
USE wedding_rental;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Vendors table
CREATE TABLE vendors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    website VARCHAR(255),
    image VARCHAR(255),
    rating DECIMAL(2,1) DEFAULT 0,
    verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('pelaminan', 'bunga', 'dekorasi_meja', 'lighting', 'backdrop', 'karpet') NOT NULL,
    image VARCHAR(255),
    description TEXT,
    vendor_id INT,
    rating DECIMAL(2,1) DEFAULT 0,
    available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE SET NULL
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    event_date DATE NOT NULL,
    event_location TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Payments table
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    payment_date TIMESTAMP NULL,
    transaction_id VARCHAR(255),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Reviews table
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product_review (user_id, product_id)
);

-- Insert sample data

-- Sample vendors
INSERT INTO vendors (name, description, phone, email, address, verified, rating) VALUES
('Rumah Dekorasi Elegan', 'Spesialis dekorasi pernikahan dengan desain modern dan elegan', '08123456789', 'info@rumahdekora.com', 'Jl. Sudirman No. 123, Jakarta', TRUE, 4.8),
('Flower Paradise', 'Ahli dalam rangkaian bunga segar untuk berbagai acara', '08234567890', 'hello@flowerparadise.com', 'Jl. Melati No. 45, Bandung', TRUE, 4.6),
('Lighting Magic', 'Penyedia sistem pencahayaan profesional untuk event', '08345678901', 'contact@lightingmagic.com', 'Jl. Lampu No. 67, Surabaya', TRUE, 4.7);

-- Sample products
INSERT INTO products (name, price, category, description, vendor_id, rating, available) VALUES
-- Pelaminan
('Pelaminan Modern Minimalis', 2500000, 'pelaminan', 'Pelaminan dengan desain modern minimalis, cocok untuk acara indoor maupun outdoor', 1, 4.8, TRUE),
('Pelaminan Klasik Mewah', 3500000, 'pelaminan', 'Pelaminan bergaya klasik dengan detail ukiran mewah dan finishing emas', 1, 4.9, TRUE),
('Pelaminan Garden Style', 2800000, 'pelaminan', 'Pelaminan dengan konsep taman yang natural dan fresh', 1, 4.7, TRUE),

-- Bunga
('Buket Mawar Merah Premium', 350000, 'bunga', 'Buket mawar merah pilihan dengan kemasan elegan', 2, 4.6, TRUE),
('Rangkaian Bunga Meja VIP', 150000, 'bunga', 'Rangkaian bunga segar untuk dekorasi meja tamu VIP', 2, 4.5, TRUE),
('Standing Flower Congratulation', 500000, 'bunga', 'Standing flower ukuran besar untuk ucapan selamat', 2, 4.7, TRUE),

-- Dekorasi Meja
('Set Dekorasi Meja Romantic', 75000, 'dekorasi_meja', 'Set lengkap dekorasi meja dengan tema romantis', 1, 4.4, TRUE),
('Centerpiece Crystal Elegant', 125000, 'dekorasi_meja', 'Centerpiece mewah dengan kristal dan bunga segar', 1, 4.6, TRUE),

-- Lighting
('Paket Lighting Warm LED', 800000, 'lighting', 'Paket pencahayaan LED hangat untuk suasana romantis', 3, 4.8, TRUE),
('Spotlight Dancing Floor', 1200000, 'lighting', 'Sistem spotlight untuk area dancing dengan efek warna-warni', 3, 4.7, TRUE),

-- Backdrop
('Backdrop Flower Wall', 1500000, 'backdrop', 'Dinding bunga artificial dengan desain Instagram-able', 1, 4.5, TRUE),
('Backdrop Vintage Classic', 1200000, 'backdrop', 'Backdrop bergaya vintage dengan detail kayu dan kain', 1, 4.6, TRUE),

-- Karpet
('Karpet Aisle Runner Premium', 300000, 'karpet', 'Karpet panjang berkualitas premium untuk jalan pengantin', 1, 4.3, TRUE),
('Karpet Area Reception', 250000, 'karpet', 'Karpet untuk area resepsi dengan motif elegan', 1, 4.4, TRUE);

-- Sample users
INSERT INTO users (name, email, password, phone) VALUES
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456789'),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08234567890'),
('Ahmad Rahman', 'ahmad@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08345678901');