-- ============================================================
-- PitStop Parts — Database Schema
-- Week 5: Full database with all 7 tables
-- Import via phpMyAdmin: Import > Select this file > Go
-- ============================================================

CREATE DATABASE IF NOT EXISTS pitstop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pitstop;

-- ── USERS ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(120)  NOT NULL,
    email      VARCHAR(160)  NOT NULL UNIQUE,
    phone      VARCHAR(30),
    password   VARCHAR(255)  NOT NULL,
    role       ENUM('customer','admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── CATEGORIES ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS categories (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    name      VARCHAR(80)  NOT NULL,
    slug      VARCHAR(80)  NOT NULL UNIQUE,
    image_url VARCHAR(255)
);

-- ── MAKES (Vehicle Makes) ────────────────────────────────────
CREATE TABLE IF NOT EXISTS makes (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL
);

-- ── MODELS (Vehicle Models) ──────────────────────────────────
CREATE TABLE IF NOT EXISTS models (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    make_id INT NOT NULL,
    name    VARCHAR(80) NOT NULL,
    FOREIGN KEY (make_id) REFERENCES makes(id) ON DELETE CASCADE
);

-- ── PRODUCTS ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS products (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(200)  NOT NULL,
    slug           VARCHAR(220)  NOT NULL UNIQUE,
    brand          VARCHAR(100)  NOT NULL,
    part_number    VARCHAR(80),
    description    TEXT,
    price          DECIMAL(10,2) NOT NULL,
    stock          INT           DEFAULT 0,
    category_id    INT,
    condition_type ENUM('new','oem','aftermarket','refurbished') DEFAULT 'new',
    image_url      VARCHAR(255),
    is_active      TINYINT(1)    DEFAULT 1,
    created_at     TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- ── ORDERS ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS orders (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    user_id          INT NOT NULL,
    total            DECIMAL(10,2) NOT NULL,
    shipping_name    VARCHAR(120),
    shipping_phone   VARCHAR(30),
    shipping_address TEXT,
    notes            TEXT,
    status           ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ── ORDER ITEMS ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS order_items (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    order_id          INT NOT NULL,
    product_id        INT NOT NULL,
    quantity          INT NOT NULL,
    price_at_purchase DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- ============================================================
-- SEED DATA
-- ============================================================

-- Admin user (password: password)
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@pitstopparts.co.ke', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Categories
INSERT INTO categories (name, slug, image_url) VALUES
('Engine Parts',     'engine',    'assets/images/cat-engine.jpg'),
('Brakes',           'brakes',    'assets/images/cat-brakes.jpg'),
('Suspension',       'suspension','assets/images/cat-suspension.jpg'),
('Electrical',       'electrical','assets/images/cat-electrical.jpg'),
('Body Parts',       'body',      'assets/images/cat-body.jpg'),
('Filters & Fluids', 'filters',   'assets/images/cat-filters.jpg');

-- Vehicle Makes
INSERT INTO makes (name) VALUES ('Toyota'),('Nissan'),('Subaru'),('Mazda'),('Honda'),('Mitsubishi');

-- Vehicle Models
INSERT INTO models (make_id, name) VALUES
(1,'Corolla'),(1,'Camry'),(1,'Hilux'),(1,'Land Cruiser'),(1,'Prado'),(1,'Vitz'),(1,'RAV4'),
(2,'Note'),(2,'Tiida'),(2,'X-Trail'),(2,'Patrol'),(2,'Hardbody'),
(3,'Impreza'),(3,'Forester'),(3,'Outback'),(3,'Legacy'),(3,'XV'),
(4,'Demio'),(4,'Axela'),(4,'CX-5'),(4,'BT-50'),
(5,'Fit'),(5,'Civic'),(5,'CR-V'),(5,'Accord'),
(6,'Outlander'),(6,'Pajero'),(6,'ASX'),(6,'Eclipse Cross');

-- Sample Products
INSERT INTO products (name, slug, brand, part_number, description, price, stock, category_id, condition_type, image_url) VALUES
('Toyota Alternator','toyota-alternator','Denso','27060-0P010','Genuine Denso alternator compatible with Toyota Corolla, Camry, Vitz. 12V 90A output.',4500,12,4,'new','assets/images/product1.jpg'),
('Brembo Brake Pads Set','brake-pads-brembo','Brembo','BP-TS2210','Premium Brembo front brake pads. High-temperature ceramic compound for reliable stopping power.',2800,24,2,'oem','assets/images/product2.jpg'),
('K&N Air Filter','kn-air-filter','K&N','33-2842','High-flow washable and reusable air filter. Increases horsepower and acceleration.',1200,30,6,'new','assets/images/product3.jpg'),
('KYB Shock Absorber','kyb-shock-absorber','KYB','344390','KYB Excel-G shock absorber for Toyota Hilux rear axle. OEM-spec replacement.',3600,8,3,'aftermarket','assets/images/product4.jpg'),
('Nissens Radiator','nissens-radiator','Nissens','638862','Aluminium core radiator for Nissan X-Trail 2.0L. Full OEM specification.',7800,5,1,'new','assets/images/product5.jpg'),
('Bosch Starter Motor','bosch-starter-motor','Bosch','0001107436','Reconditioned Bosch starter motor for Subaru Impreza EJ20. Tested and warranted.',5200,4,4,'refurbished','assets/images/product6.jpg');
