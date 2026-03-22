<?php
require_once 'config.php';

// Rediriger si déjà connecté
if(isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    // Validation
    if(empty($username) || empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs obligatoires';
    } elseif(strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères';
    } elseif($password !== $confirm_password) {
        $error = 'Les mots de passe ne correspondent pas';
    } else {
        $conn = getConnection();
        
        // Vérifier si l'email existe déjà
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $error = 'Cet email est déjà utilisé';
        } else {
            // Vérifier si le nom d'utilisateur existe déjà
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if($result->num_rows > 0) {
                $error = 'Ce nom d\'utilisateur est déjà pris';
            } else {
                // Créer l'utilisateur
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, address, phone) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $username, $email, $hashed_password, $address, $phone);
                
                if($stmt->execute()) {
                    $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                    // Rediriger vers la page de connexion après 2 secondes
                    header("refresh:2;url=login.php");
                } else {
                    $error = 'Erreur lors de l\'inscription. Veuillez réessayer.';
                }
            }
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
    <title>Inscription - Elysian Rest</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .auth-container {
            max-width: 500px;
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
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-submit:hover {
            background: #229954;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
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
            <h2>Inscription</h2>
            
            <?php if($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="success-message"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur *</label>
                    <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe * (minimum 6 caractères)</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">Adresse</label>
                    <textarea id="address" name="address"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                </div>
                
                <button type="submit" class="btn-submit">S'inscrire</button>
            </form>
            
            <div class="auth-link">
                <p>Déjà inscrit ? <a href="login.php">Connectez-vous</a></p>
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