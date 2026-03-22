<?php
require_once 'config.php';

$conn = getConnection();
$result = $conn->query("SELECT * FROM products LIMIT 6");
$products = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elysian Rest - Vente de Cercueils</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <i class="fas fa-dove"></i>
                <span>Elysian Rest</span>
            </div>
            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="products.php">Produits</a></li>
                <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Panier <span id="cart-count">0</span></a></li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="orders.php">Mes commandes</a></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="login.php">Connexion</a></li>
                    <li><a href="register.php">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Dernier Voyage Avec Dignité</h1>
                <p>Des cercueils de qualité pour un hommage respectueux</p>
                <a href="products.php" class="btn-primary">Voir nos produits</a>
            </div>
        </section>

        <section class="featured-products">
            <h2>Produits Populaires</h2>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card" data-product-id="<?= $product['id'] ?>">
                        <img src="<?= $product['image_url'] ?>" alt="<?= $product['name'] ?>">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="price"><?= number_format($product['price'], 2) ?> €</p>
                        <p class="material"><?= htmlspecialchars($product['material']) ?></p>
                        <button class="btn-add-to-cart" onclick="addToCart(<?= $product['id'] ?>, '<?= addslashes($product['name']) ?>', <?= $product['price'] ?>)">
                            Ajouter au panier
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>Elysian Rest</h4>
                <p>Service funéraire depuis 1985</p>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <p>Email: contact@elysianrest.com</p>
                <p>Tél: 01 23 45 67 89</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>

</html>