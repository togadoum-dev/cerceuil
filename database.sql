-- Création de la base de données
CREATE DATABASE IF NOT EXISTS coffin_shop;
USE coffin_shop;

-- Table des utilisateurs
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits (cercueils)
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    material VARCHAR(50),
    color VARCHAR(50),
    size VARCHAR(50),
    image_url VARCHAR(255),
    stock INT DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des commandes
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    shipping_address TEXT,
    payment_method VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table des détails de commande
CREATE TABLE order_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insertion de produits de démonstration
INSERT INTO products (name, description, price, material, color, size, image_url) VALUES
('Cercueil Classique Chêne', 'Cercueil traditionnel en chêne massif', 1500.00, 'Chêne massif', 'Marron', 'Standard', 'https://via.placeholder.com/300x200?text=Cercueil+Chêne'),
('Cercueil Élégant Acajou', 'Cercueil luxueux en acajou poli', 2500.00, 'Acajou', 'Rouge brun', 'Grand', 'https://via.placeholder.com/300x200?text=Cercueil+Acajou'),
('Cercueil Simple Pin', 'Cercueil économique en pin', 800.00, 'Pin', 'Naturel', 'Standard', 'https://via.placeholder.com/300x200?text=Cercueil+Pin'),
('Cercueil Métallique Argenté', 'Cercueil moderne en métal argenté', 1800.00, 'Métal', 'Argent', 'Grand', 'https://via.placeholder.com/300x200?text=Cercueil+Métal'),
('Cercueil Cérémonie Blanc', 'Cercueil cérémonial blanc', 1200.00, 'Bois peint', 'Blanc', 'Standard', 'https://via.placeholder.com/300x200?text=Cercueil+Blanc'),
('Cercueil Premium Or', 'Cercueil haut de gamme finition or', 3500.00, 'Bois précieux', 'Doré', 'Extra large', 'https://via.placeholder.com/300x200?text=Cercueil+Or');