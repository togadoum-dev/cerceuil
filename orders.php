<?php
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if(!isLoggedIn()) {
    redirect('login.php?redirect=orders');
}

$conn = getConnection();

// Récupérer les commandes de l'utilisateur
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes commandes - Elysian Rest</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .orders-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }
        
        .orders-container h2 {
            color: #2c3e50;
            margin-bottom: 30px;
        }
        
        .order-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .order-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .order-number {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .order-date {
            color: #6c757d;
        }
        
        .order-status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-processing {
            background: #cce5ff;
            color: #004085;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .order-details {
            padding: 20px;
        }
        
        .order-items {
            margin-bottom: 20px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .order-total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #e67e22;
            padding-top: 10px;
        }
        
        .no-orders {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn-shop {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
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
                <li><a href="orders.php">Mes commandes</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="orders-container">
            <h2>Mes commandes</h2>
            
            <?php if(empty($orders)): ?>
                <div class="no-orders">
                    <i class="fas fa-box-open" style="font-size: 48px; color: #6c757d;"></i>
                    <h3>Aucune commande passée</h3>
                    <p>Vous n'avez pas encore passé de commande.</p>
                    <a href="products.php" class="btn-shop">Découvrir nos produits</a>
                </div>
            <?php else: ?>
                <?php foreach($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <span class="order-number">Commande #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></span>
                                <span class="order-date"> - <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></span>
                            </div>
                            <div>
                                <span class="order-status status-<?= $order['status'] ?>">
                                    <?php
                                    switch($order['status']) {
                                        case 'pending': echo 'En attente'; break;
                                        case 'processing': echo 'En traitement'; break;
                                        case 'completed': echo 'Terminée'; break;
                                        case 'cancelled': echo 'Annulée'; break;
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="order-details">
                            <div class="order-items">
                                <?php
                                // Récupérer les détails de la commande
                                $conn = getConnection();
                                $stmt = $conn->prepare("SELECT od.*, p.name FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = ?");
                                $stmt->bind_param("i", $order['id']);
                                $stmt->execute();
                                $details = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                
                                foreach($details as $detail):
                                ?>
                                    <div class="order-item">
                                        <span><?= htmlspecialchars($detail['name']) ?> x <?= $detail['quantity'] ?></span>
                                        <span><?= number_format($detail['price'] * $detail['quantity'], 2) ?> €</span>
                                    </div>
                                <?php endforeach; ?>
                                <div class="order-total">
                                    Total: <?= number_format($order['total_amount'], 2) ?> €
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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