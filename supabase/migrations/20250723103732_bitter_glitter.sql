-- Wedding Rental Database Schema

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Vendors table
CREATE TABLE vendors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    logo VARCHAR(255),
    description TEXT,
    rating DECIMAL(3,2) DEFAULT 0.00,
    phone VARCHAR(20),
    specialties JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    category ENUM('pelaminan', 'fotografer', 'musik', 'mua', 'mc', 'paket') NOT NULL,
    image VARCHAR(255) NOT NULL,
    description TEXT,
    vendor_id INT,
    rating DECIMAL(3,2) DEFAULT 0.00,
    available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE SET NULL
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    event_date DATE NOT NULL,
    event_location TEXT NOT NULL,
    status ENUM('pending', 'confirmed', 'in-progress', 'completed') DEFAULT 'pending',
    payment_status ENUM('none', 'dp', 'full') DEFAULT 'none',
    payment_proof VARCHAR(255),
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
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert sample vendors
INSERT INTO vendors (name, address, description, rating, phone, specialties) VALUES
('Elegant Decorations', 'Jl. Merdeka No. 123, Jakarta', 'Spesialis dekorasi pelaminan mewah dengan pengalaman 10 tahun', 4.8, '081234567890', '["pelaminan", "backdrop"]'),
('Perfect Moments Photography', 'Jl. Sudirman No. 456, Jakarta', 'Fotografer profesional untuk momen spesial Anda', 4.9, '081234567891', '["fotografer", "videografi"]'),
('Harmony Music', 'Jl. Thamrin No. 789, Jakarta', 'Penyedia musik live dan sound system berkualitas', 4.7, '081234567892', '["musik", "sound system"]');

-- Insert sample products
INSERT INTO products (name, price, category, image, description, vendor_id, rating, available) VALUES
('Pelaminan Klasik Emas', 5000000, 'pelaminan', 'https://images.pexels.com/photos/1024993/pexels-photo-1024993.jpeg', 'Pelaminan dengan desain klasik berlapis emas yang elegan', 1, 4.8, TRUE),
('Paket Foto Pre-Wedding', 3000000, 'fotografer', 'https://images.pexels.com/photos/1024993/pexels-photo-1024993.jpeg', 'Paket lengkap foto pre-wedding dengan 100 foto edited', 2, 4.9, TRUE),
('Live Music Acoustic', 2500000, 'musik', 'https://images.pexels.com/photos/1024993/pexels-photo-1024993.jpeg', 'Pertunjukan musik akustik untuk acara pernikahan', 3, 4.7, TRUE),
('Pelaminan Modern Minimalis', 4000000, 'pelaminan', 'https://images.pexels.com/photos/1024993/pexels-photo-1024993.jpeg', 'Desain pelaminan modern dengan sentuhan minimalis', 1, 4.6, TRUE),
('Paket Dokumentasi Wedding', 4500000, 'fotografer', 'https://images.pexels.com/photos/1024993/pexels-photo-1024993.jpeg', 'Dokumentasi lengkap hari pernikahan dengan video cinematic', 2, 4.8, TRUE),
('Sound System Premium', 1500000, 'musik', 'https://images.pexels.com/photos/1024993/pexels-photo-1024993.jpeg', 'Sound system berkualitas tinggi untuk acara outdoor/indoor', 3, 4.5, TRUE);