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
('Cercueil Classique Chêne', 'Cercueil traditionnel en chêne massif', 1500.00, 'Chêne massif', 'Marron', 'Standard', 'https://img.freepik.com/psd-premium/psd-vieux-cercueil-fond-transparent_1304706-4217.jpg?ga=GA1.1.328500495.1774174547&semt=ais_hybrid&w=740&q=80'),
('Cercueil Élégant Acajou', 'Cercueil luxueux en acajou poli', 2500.00, 'Acajou', 'Rouge brun', 'Grand', 'https://img.freepik.com/vecteurs-libre/cofin-bois-croix-christain_1308-33179.jpg?ga=GA1.1.328500495.1774174547&semt=ais_hybrid&w=740&q=80'),
('Cercueil Simple Pin', 'Cercueil économique en pin', 800.00, 'Pin', 'Naturel', 'Standard', 'https://img.freepik.com/photos-gratuite/gros-plan-main-personne-cercueil-arriere-plan-flou_181624-13563.jpg?ga=GA1.1.328500495.1774174547&semt=ais_hybrid&w=740&q=80'),
('Cercueil Métallique Argenté', 'Cercueil moderne en métal argenté', 1800.00, 'Métal', 'Argent', 'Grand', 'https://img.freepik.com/photos-gratuite/ornement-cercueil-pour-halloween_23-2148633275.jpg?ga=GA1.1.328500495.1774174547&semt=ais_hybrid&w=740&q=80'),
('Cercueil Cérémonie Blanc', 'Cercueil cérémonial blanc', 1200.00, 'Bois peint', 'Blanc', 'Standard', 'https://img.freepik.com/psd-premium/elegant-cercueil-blanc-entoure-fleurs-dans-decor-funeraire-serein_1386465-13505.jpg?ga=GA1.1.328500495.1774174547&semt=ais_hybrid&w=740&q=80'),
('Cercueil Premium Or', 'Cercueil haut de gamme finition or', 3500.00, 'Bois précieux', 'Doré', 'Extra large', 'https://img.freepik.com/photos-gratuite/cercueil-vampires-entoure-roses_23-2151918161.jpg?ga=GA1.1.328500495.1774174547&semt=ais_hybrid&w=740&q=80');