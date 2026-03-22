<?php
require_once 'config.php';

$conn = getConnection();
$material = isset($_GET['material']) ? $_GET['material'] : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 10000;

$query = "SELECT * FROM products WHERE 1=1";
if ($material) {
    $query .= " AND material = '" . $conn->real_escape_string($material) . "'";
}
if ($min_price > 0) {
    $query .= " AND price >= $min_price";
}
if ($max_price < 10000) {
    $query .= " AND price <= $max_price";
}
$query .= " ORDER BY created_at DESC";

$result = $conn->query($query);
$products = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos produits - Elysian Rest</title>
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
                <?php if(isLoggedIn()): ?>
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
        <div class="products-page">
            <aside class="filters">
                <h3>Filtres</h3>
                <form method="GET" action="products.php">
                    <div class="filter-group">
                        <label>Matériau</label>
                        <select name="material">
                            <option value="">Tous</option>
                            <option value="Chêne massif" <?= $material == 'Chêne massif' ? 'selected' : '' ?>>Chêne</option>
                            <option value="Acajou" <?= $material == 'Acajou' ? 'selected' : '' ?>>Acajou</option>
                            <option value="Pin" <?= $material == 'Pin' ? 'selected' : '' ?>>Pin</option>
                            <option value="Métal" <?= $material == 'Métal' ? 'selected' : '' ?>>Métal</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Prix min</label>
                        <input type="number" name="min_price" value="<?= $min_price ?>" step="100">
                    </div>
                    
                    <div class="filter-group">
                        <label>Prix max</label>
                        <input type="number" name="max_price" value="<?= $max_price ?>" step="100">
                    </div>
                    
                    <button type="submit" class="btn-filter">Appliquer</button>
                </form>
            </aside>
            
            <div class="products-content">
                <h2>Nos Cercueils</h2>
                <div class="products-grid">
                    <?php foreach($products as $product): ?>
                        <div class="product-card">
                            <img src="<?= $product['image_url'] ?>" alt="<?= $product['name'] ?>">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="price"><?= number_format($product['price'], 2) ?> €</p>
                            <p class="material"><?= htmlspecialchars($product['material']) ?></p>
                            <p class="stock">Stock: <?= $product['stock'] ?></p>
                            <button class="btn-add-to-cart" onclick="addToCart(<?= $product['id'] ?>, '<?= addslashes($product['name']) ?>', <?= $product['price'] ?>, '<?= $product['image_url'] ?>')">
                                Ajouter au panier
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>