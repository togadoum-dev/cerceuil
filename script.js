// Gestion du panier
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Mettre à jour l'affichage du compteur du panier
function updateCartCount() {
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
    }
}

// Ajouter au panier
function addToCart(id, name, price, image = '') {
    const existingItem = cart.find(item => item.id === id);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            quantity: 1,
            image: image
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showNotification('Produit ajouté au panier !');
}

// Supprimer du panier
function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    displayCart();
}

// Mettre à jour la quantité
function updateQuantity(id, change) {
    const item = cart.find(item => item.id === id);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(id);
        } else {
            localStorage.setItem('cart', JSON.stringify(cart));
            displayCart();
        }
    }
}

// Afficher le panier
function displayCart() {
    const cartContainer = document.getElementById('cart-items');
    if (!cartContainer) return;
    
    if (cart.length === 0) {
        cartContainer.innerHTML = '<p class="empty-cart">Votre panier est vide</p>';
        document.getElementById('cart-summary').style.display = 'none';
        return;
    }
    
    let html = '';
    let total = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        html += `
            <div class="cart-item">
                <img src="${item.image || 'https://via.placeholder.com/80'}" alt="${item.name}">
                <div>
                    <h4>${item.name}</h4>
                    <p>${item.price.toFixed(2)} €</p>
                </div>
                <div class="quantity-controls">
                    <button onclick="updateQuantity(${item.id}, -1)">-</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateQuantity(${item.id}, 1)">+</button>
                </div>
                <div class="item-total">
                    ${itemTotal.toFixed(2)} €
                </div>
                <button onclick="removeFromCart(${item.id})" class="btn-remove">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
    });
    
    cartContainer.innerHTML = html;
    
    const summaryHtml = `
        <div class="cart-summary">
            <h3>Total: ${total.toFixed(2)} €</h3>
            <button class="btn-checkout" onclick="checkout()">Passer la commande</button>
        </div>
    `;
    
    document.getElementById('cart-summary').innerHTML = summaryHtml;
    document.getElementById('cart-summary').style.display = 'block';
}

// Passer la commande
async function checkout() {
    if (cart.length === 0) {
        showNotification('Votre panier est vide', 'error');
        return;
    }
    
    // Vérifier si l'utilisateur est connecté
    const isLoggedIn = document.body.hasAttribute('data-logged-in');
    
    if (!isLoggedIn) {
        if (confirm('Vous devez être connecté pour passer commande. Voulez-vous vous connecter ?')) {
            window.location.href = 'login.php?redirect=checkout';
        }
        return;
    }
    
    try {
        const response = await fetch('process_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ cart: cart })
        });
        
        const result = await response.json();
        
        if (result.success) {
            localStorage.removeItem('cart');
            showNotification('Commande passée avec succès !');
            setTimeout(() => {
                window.location.href = 'orders.php';
            }, 2000);
        } else {
            showNotification('Erreur lors de la commande', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Erreur de connexion', 'error');
    }
}

// Afficher une notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem;
        background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
        color: white;
        border-radius: 5px;
        z-index: 1000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    
    // Ajouter l'attribut pour vérifier la connexion
    const isLoggedIn = document.querySelector('.nav-links a[href="logout.php"]');
    if (isLoggedIn) {
        document.body.setAttribute('data-logged-in', 'true');
    }
    
    // Afficher le panier sur la page cart.php
    if (window.location.pathname.includes('cart.php')) {
        displayCart();
    }
});

// Animation pour les notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .quantity-controls {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    .quantity-controls button {
        background: #ecf0f1;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .btn-remove {
        background: none;
        border: none;
        color: #e74c3c;
        cursor: pointer;
        font-size: 1.2rem;
    }
    
    .empty-cart {
        text-align: center;
        padding: 3rem;
        color: #7f8c8d;
    }
`;
document.head.appendChild(style);