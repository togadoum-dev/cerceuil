<?php
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if(!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit();
}

// Récupérer les données JSON
$data = json_decode(file_get_contents('php://input'), true);

if(!isset($data['cart']) || empty($data['cart'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Panier vide']);
    exit();
}

$conn = getConnection();
$conn->begin_transaction();

try {
    $user_id = $_SESSION['user_id'];
    $cart = $data['cart'];
    
    // Calculer le total
    $total = 0;
    foreach($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    // Récupérer l'adresse de l'utilisateur
    $stmt = $conn->prepare("SELECT address, phone FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    
    // Créer la commande
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, shipping_address, payment_method) VALUES (?, ?, 'pending', ?, 'carte bancaire')");
    $address = $user['address'] ?? 'Adresse non renseignée';
    $stmt->bind_param("ids", $user_id, $total, $address);
    $stmt->execute();
    $order_id = $conn->insert_id;
    
    // Ajouter les détails de la commande
    $stmt = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    
    foreach($cart as $item) {
        $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
        $stmt->execute();
        
        // Mettre à jour le stock
        $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $update_stock->bind_param("ii", $item['quantity'], $item['id']);
        $update_stock->execute();
    }
    
    $conn->commit();
    
    echo json_encode(['success' => true, 'order_id' => $order_id]);
    
} catch(Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la commande: ' . $e->getMessage()]);
}

$conn->close();
?>