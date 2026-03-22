<?php
require_once 'config.php';

// Vérifier si l'utilisateur est connecté pour afficher le panier
$isLoggedIn = isLoggedIn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon panier - Elysian Rest</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                <?php if($isLoggedIn): ?>
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
        <div class="cart-container">
            <h2>Mon panier</h2>
            <div id="cart-items" class="cart-items">
                <!-- Les articles du panier seront affichés ici par JavaScript -->
            </div>
            <div id="cart-summary" style="display: none;">
                <!-- Le résumé du panier sera affiché ici -->
            </div>
            
            <?php if(!$isLoggedIn): ?>
                <div class="login-prompt">
                    <p>Vous devez être connecté pour passer commande.</p>
                    <a href="login.php?redirect=cart" class="btn-primary">Se connecter</a>
                    <a href="register.php" class="btn-secondary">Créer un compte</a>
                </div>
            <?php endif; ?>
        </div>
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