<?php
require_once 'config.php';

// Rediriger si déjà connecté
if(isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if(empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($user = $result->fetch_assoc()) {
            if(password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Rediriger vers la page demandée ou l'accueil
                $redirect = $_GET['redirect'] ?? 'index.php';
                redirect($redirect);
            } else {
                $error = 'Mot de passe incorrect';
            }
        } else {
            $error = 'Aucun compte trouvé avec cet email';
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Elysian Rest</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .auth-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-submit:hover {
            background: #2980b9;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .auth-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .auth-link a {
            color: #3498db;
            text-decoration: none;
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
                <li><a href="login.php">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="auth-container">
            <h2>Connexion</h2>
            
            <?php if($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-submit">Se connecter</button>
            </form>
            
            <div class="auth-link">
                <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous</a></p>
            </div>
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