CREATE DATABASE IF NOT EXISTS tahunutrifeed CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tahunutrifeed;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(12,2) NOT NULL DEFAULT 0,
  stock INT NOT NULL DEFAULT 0,
  image VARCHAR(255) DEFAULT '',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
);

INSERT INTO products (name, description, price, stock, image) VALUES
('Pakan Ternak Ampas Tahu', 'Pakan ternak berbahan ampas tahu. Hubungi kami untuk informasi proses pengolahan, kandungan, dan ketersediaan produk.', 5000, 100, 'assets/original.jpeg'),
('Ampas Tahu Kering', 'Pilihan ampas tahu kering untuk kebutuhan pakan ternak. Ketersediaan dan berat kemasan dapat dikonfirmasi melalui WhatsApp.', 3000, 80, 'assets/pelet.jpeg'),
('Pakan Campuran', 'Produk campuran berbahan ampas tahu. Komposisi dan petunjuk pemakaian perlu dikonfirmasi kepada pengelola.', 4500, 50, 'assets/campuran.jpeg');



CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(150) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  address TEXT NULL,
  product_id INT NULL,
  product_name VARCHAR(150) NOT NULL,
  quantity INT NOT NULL,
  total_price DECIMAL(12,2) NOT NULL DEFAULT 0,
  status ENUM('Baru','Diproses','Selesai','Dibatalkan') NOT NULL DEFAULT 'Baru',
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
  setting_key VARCHAR(100) PRIMARY KEY,
  setting_value TEXT NOT NULL
);
INSERT IGNORE INTO settings(setting_key,setting_value) VALUES
('business_name','UD. Tahu Yaya'),
('address','Kelurahan Abian Tubuh, Kecamatan Sandubaya, Kota Mataram'),
('description','Usaha produksi tahu yang mengembangkan pemanfaatan limbah ampas tahu menjadi pakan ternak bernilai ekonomis.'),
('phone','6287765470125'),
('email','');
