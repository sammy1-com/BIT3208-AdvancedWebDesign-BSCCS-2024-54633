CREATE DATABASE IF NOT EXISTS pitstop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pitstop_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('customer','admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    image_url VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS makes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS models (
    id INT AUTO_INCREMENT PRIMARY KEY,
    make_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    FOREIGN KEY (make_id) REFERENCES makes(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    part_number VARCHAR(100),
    brand VARCHAR(100),
    category_id INT,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    condition_type ENUM('new','oem','aftermarket','refurbished') DEFAULT 'new',
    image_url VARCHAR(255),
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    guest_email VARCHAR(150),
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    shipping_name VARCHAR(150),
    shipping_phone VARCHAR(20),
    shipping_address TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_purchase DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);

INSERT INTO users (name, email, password, role) VALUES
('PitStop Admin', 'admin@pitstopparts.co.ke', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

INSERT INTO categories (name, slug, image_url) VALUES
('Engine', 'engine', 'assets/images/turbo-engine.jpg'),
('Brakes', 'brakes', 'assets/images/suspension.jpg'),
('Body Parts', 'body-parts', 'assets/images/carbon-bumper.jpg'),
('Electrical', 'electrical', 'assets/images/headlights.jpg'),
('Suspension', 'suspension', 'assets/images/suspension.jpg'),
('Transmission', 'transmission', 'assets/images/clutch-disc.jpg');

INSERT INTO makes (name) VALUES
('Toyota'),('Nissan'),('Subaru'),('Mercedes-Benz'),('BMW'),
('Honda'),('Mitsubishi'),('Ford'),('Hyundai'),('Isuzu');

INSERT INTO models (make_id, name) VALUES
(1,'Fielder'),(1,'Axio'),(1,'Premio'),(1,'Harrier'),(1,'Land Cruiser'),(1,'Hilux'),
(2,'Note'),(2,'X-Trail'),(2,'Navara'),
(3,'Forester'),(3,'Outback'),(3,'Impreza'),
(4,'E-Class'),(4,'C-Class'),(4,'GLE'),
(5,'3 Series'),(5,'5 Series'),(5,'X5'),
(6,'Fit'),(6,'CRV'),
(7,'Outlander'),(7,'Pajero'),
(8,'Ranger'),(8,'Explorer'),
(9,'Tucson'),(9,'Santa Fe'),
(10,'D-Max'),(10,'MU-X');

INSERT INTO products (name, slug, part_number, brand, category_id, description, price, stock, condition_type, image_url, is_featured) VALUES
('Front Brake Pad Set', 'front-brake-pad-set', 'BP-2241-KIT', 'Bosch', 2, 'High performance front brake pads suitable for Japanese and European vehicles. Ceramic compound for reduced dust and noise.', 2800.00, 24, 'new', 'assets/images/suspension.jpg', 1),
('Turbo Engine 1NZ-FE', 'turbo-engine-1nz-fe', 'ENG-1NZ-TURBO', 'Toyota Genuine', 1, 'Reconditioned 1NZ-FE turbo engine. Tested and certified. Suitable for Toyota Fielder, Axio and Vitz 2007-2015.', 145000.00, 3, 'refurbished', 'assets/images/turbo-engine.jpg', 1),
('BMW Alloy Wheels Set', 'bmw-alloy-wheels-set', 'WHL-BMW-18-SET', 'BMW Genuine', 3, 'Set of 4 genuine BMW 18 inch alloy wheels in gloss black. Fits BMW 3 Series and 5 Series.', 62000.00, 5, 'new', 'assets/images/bmw-alloys.jpg', 1),
('Coil Spring Suspension Kit', 'coil-spring-suspension-kit', 'SUSP-COIL-4X4', 'Monroe', 5, 'Heavy duty coil spring and shock absorber kit for 4x4 vehicles. Includes front and rear springs with new shock absorbers.', 18500.00, 9, 'new', 'assets/images/suspension.jpg', 1),
('LED Headlight Assembly', 'led-headlight-assembly', 'HL-LED-UNI-L', 'Philips', 4, 'Universal LED headlight assembly. DRL daytime running lights included. Direct OEM replacement.', 8900.00, 14, 'new', 'assets/images/headlights.jpg', 1),
('Carbon Fibre Front Bumper', 'carbon-fibre-front-bumper', 'BMP-CF-MUSTANG', 'Aftermarket', 3, 'Carbon fibre composite front bumper with integrated mesh grille. Sport aggressive styling. Requires professional fitting.', 38000.00, 2, 'aftermarket', 'assets/images/carbon-bumper.jpg', 1),
('Bosch Starter Motor', 'bosch-starter-motor', 'SM-BSH-SR15N', 'Bosch', 4, 'Genuine Bosch starter motor SR15N. 12V. Fits a wide range of Japanese vehicles. New with full warranty.', 6500.00, 18, 'new', 'assets/images/starter-motor.jpg', 0),
('Clutch Disc Assembly', 'clutch-disc-assembly', 'CLT-DISC-225', 'Exedy', 6, 'Exedy clutch disc 225mm diameter. Suitable for medium to heavy duty applications.', 5800.00, 11, 'new', 'assets/images/clutch-disc.jpg', 0),
('Heavy Duty Front Bumper', 'heavy-duty-front-bumper', 'BMP-HD-FORD', 'Ironman', 3, 'Steel heavy duty front bumper with winch mounting points and LED light mounts. Fits Ford Ranger 2015 onwards.', 42000.00, 6, 'aftermarket', 'assets/images/ford-bumpers.jpg', 0),
('Engine Block Assembly', 'engine-block-assembly', 'ENG-BLK-4CYL', 'Reconditioned', 1, 'Reconditioned 4 cylinder engine block complete with pistons and connecting rods. Pressure tested.', 95000.00, 2, 'refurbished', 'assets/images/engine-pistons.jpg', 0),
('Timing Belt Kit', 'timing-belt-kit', 'TB-KIT-1NZ', 'Gates', 1, 'Complete timing belt kit includes belt, tensioner, idler pulley and water pump. Fits Toyota 1NZ and 2NZ engines.', 4200.00, 31, 'new', 'assets/images/timing-belt.jpg', 0),
('Bull Bar Heavy Duty', 'bull-bar-heavy-duty', 'BB-HD-STEEL-4X4', 'Ironman', 3, 'Stainless steel bull bar with integrated nudge bar. Fits most 4x4 and pickup truck models. Powder coated.', 28000.00, 7, 'new', 'assets/images/bullbars.jpg', 0);
