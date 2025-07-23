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

-- Insert sample vendors (matching React app data)
INSERT INTO vendors (name, address, description, rating, phone, specialties) VALUES
('Elegant Wedding Solutions', 'Jl. Merdeka No. 123, Jakarta Pusat', 'Spesialis dekorasi pernikahan mewah dengan pengalaman lebih dari 10 tahun', 4.8, '+62812-3456-7890', '["Dekorasi Pelaminan", "Lighting Design", "Floral Arrangement"]'),
('Royal Wedding Decor', 'Jl. Sudirman No. 456, Jakarta Selatan', 'Menciptakan momen pernikahan yang tak terlupakan dengan sentuhan royal', 4.9, '+62813-9876-5432', '["Tema Royal", "Outdoor Wedding", "Garden Party"]'),
('Dream Photo Studio', 'Jl. Thamrin No. 789, Jakarta Pusat', 'Fotografer profesional untuk moment spesial pernikahan Anda', 4.7, '+62814-5555-1234', '["Pre-wedding", "Wedding Day", "Drone Photography"]');

-- Insert sample products (matching React app data)
INSERT INTO products (name, price, category, image, description, vendor_id, rating, available) VALUES
('Pelaminan Klasik Emas', 15000000, 'pelaminan', 'https://images.pexels.com/photos/1024993/pexels-photo-1024993.jpeg?auto=compress&cs=tinysrgb&w=800', 'Pelaminan mewah dengan ornamen emas dan bunga segar pilihan', 1, 4.8, TRUE),
('Pelaminan Modern Minimalis', 8500000, 'pelaminan', 'https://images.pexels.com/photos/1024992/pexels-photo-1024992.jpeg?auto=compress&cs=tinysrgb&w=800', 'Desain modern dengan konsep clean dan elegan', 2, 4.6, TRUE),
('Paket Fotografer Wedding', 12000000, 'fotografer', 'https://images.pexels.com/photos/1024981/pexels-photo-1024981.jpeg?auto=compress&cs=tinysrgb&w=800', 'Paket lengkap dokumentasi pernikahan dengan tim profesional', 3, 4.9, TRUE),
('Band Acoustic Wedding', 5000000, 'musik', 'https://images.pexels.com/photos/1024994/pexels-photo-1024994.jpeg?auto=compress&cs=tinysrgb&w=800', 'Band acoustic untuk menciptakan suasana romantis', 1, 4.5, TRUE),
('MUA Profesional', 3500000, 'mua', 'https://images.pexels.com/photos/1024995/pexels-photo-1024995.jpeg?auto=compress&cs=tinysrgb&w=800', 'Make up artist berpengalaman untuk pengantin', 2, 4.7, TRUE),
('MC Professional', 2500000, 'mc', 'https://images.pexels.com/photos/1024996/pexels-photo-1024996.jpeg?auto=compress&cs=tinysrgb&w=800', 'Master of Ceremony berpengalaman untuk acara pernikahan', 3, 4.6, TRUE);

-- Insert admin user for testing
INSERT INTO users (name, email, password, phone) VALUES
('Admin', 'admin@wedding.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+62812-0000-0000');