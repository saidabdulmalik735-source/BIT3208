<?php
session_start();
include 'includes/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopMart - Premium E-Commerce Store</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">🛒 ShopMart</div>
                <div class="user-info">
                    <?php if (isset($_SESSION['username'])): ?>
                        <span class="user-name"> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        <a href="dashboard.php" class="logout-btn">Dashboard</a>
                        <a href="logout.php" class="logout-btn">Logout</a>
                    <?php else: ?>
                        <a href="login.php" style="color: white; text-decoration: none; margin-right: 15px;">Login</a>
                        <a href="register.php" style="background: white; color: #667eea; padding: 8px 20px; border-radius: 20px; text-decoration: none;">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <nav class="navbar">
        <div class="container">
            <ul class="nav-links">
                <li><a href="index.php" class="active"> Home</a></li>
                <li><a href="#"> Products</a></li>
                <li><a href="#"> Deals</a></li>
                <li><a href="#"> Contact</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
        <li><a href="add_product.php"> Add Product</a></li>
    <?php endif; ?>
    <li><a href="dashboard.php"> Dashboard</a></li>
<?php endif; ?>
            </ul>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <h1>Welcome to ShopMart</h1>
            <p>Your one-stop destination for premium products at unbeatable prices</p>
            <a href="#products" class="btn">Shop Now</a>
        </div>
    </section>

    <div class="search-section" style="background: white; padding: 30px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px;">
        <div class="container">
            <div class="search-container" style="max-width: 600px; margin: 0 auto; position: relative;">
                <input type="text" id="liveSearch" class="search-input" placeholder=" Search products..." autocomplete="off" style="width: 100%; padding: 15px 50px 15px 20px; font-size: 1.1rem; border: 2px solid #e0e0e0; border-radius: 50px; outline: none;">
                <span class="search-icon" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 1.3rem;"></span>
            </div>
            <div class="search-stats" id="searchStats" style="text-align: center; margin-top: 10px; color: #7f8c8d;"></div>
        </div>
    </div>

    <div class="container" id="products">
        <h2 class="section-title" style="text-align: center; font-size: 2rem; margin: 40px 0 30px; color: #2c3e50;">Featured Products</h2>
        <div class="no-results" id="noResults" style="text-align: center; padding: 60px; color: #7f8c8d; display: none;">
            <h3> No products found! We'll let you know when we restock.</h3>
            <p>Try searching with different keywords.</p>
        </div>
        <div class="product-grid" id="productGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-bottom: 50px;"></div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div><h3>About ShopMart</h3><p>Your trusted e-commerce platform.</p></div>
                <div><h3>Quick Links</h3><p><a href="#">About Us</a><br><a href="#">Shipping</a></p></div>
                <div><h3>Contact</h3><p> support@shopmart.com<br>
                    📞 +254 798509106<br>
                    📍 Kidhimani Rd, Thika Town</p></div>
            </div>
            <div class="copyright">
                <p>&copy; 2026 ShopMart. | BIT3208 Week 4</p>
            </div>
        </div>
    </footer>

    <script>
        let products = [];
        <?php
        $result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
        $dbProducts = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $dbProducts[] = [
                    'id' => $row['id'],
                    'name' => addslashes($row['name'] ?? 'Unnamed'),
                    'price' => floatval($row['price']),
                    'description' => addslashes($row['description'] ?? 'No description'),
                    'emoji' => '📦'
                ];
            }
        }
        if (count($dbProducts) > 0) {
            echo "products = " . json_encode($dbProducts) . ";\n";
        }
        ?>
        if (products.length === 0) {
            products = [
                { id: 1, name: "Smartphone Pro X", price: 899.99, description: "Latest flagship smartphone", emoji: "📱" },
                { id: 2, name: "UltraBook 15", price: 1299.00, description: "Lightweight professional laptop", emoji: "💻" },
                { id: 3, name: "NoiseCancel Headphones", price: 249.99, description: "Premium wireless headphones", emoji: "🎧" },
                { id: 4, name: "SmartWatch Series 5", price: 399.00, description: "Fitness tracking smartwatch", emoji: "⌚" },
                { id: 5, name: "DSLR Camera Kit", price: 1599.00, description: "Professional 24MP camera", emoji: "📷" },
                { id: 6, name: "Game Console Elite", price: 499.99, description: "Next-gen gaming console", emoji: "🎮" }
            ];
        }

        const productGrid = document.getElementById('productGrid');
        const searchInput = document.getElementById('liveSearch');
        const searchStats = document.getElementById('searchStats');
        const noResults = document.getElementById('noResults');

        function renderProducts(productsToRender, searchTerm = '') {
            productGrid.innerHTML = '';
            if (productsToRender.length === 0) {
                noResults.style.display = 'block';
                searchStats.textContent = '';
                return;
            }
            noResults.style.display = 'none';
            productsToRender.forEach(product => {
                let displayName = product.name;
                let displayDesc = product.description;
                if (searchTerm) {
                    const regex = new RegExp(`(${searchTerm})`, 'gi');
                    displayName = product.name.replace(regex, '<span style="background: yellow; font-weight: bold;">$1</span>');
                    displayDesc = product.description.replace(regex, '<span style="background: yellow; font-weight: bold;">$1</span>');
                }
                const card = document.createElement('div');
                card.className = 'product-card';
                card.style.cssText = 'background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s;';
                card.innerHTML = `
                    <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">${product.emoji}</div>
                    <div style="padding: 20px;">
                        <div style="font-size: 1.2rem; font-weight: bold; margin-bottom: 10px;">${displayName}</div>
                        <div style="color: #e74c3c; font-size: 1.3rem; font-weight: bold;">$${parseFloat(product.price).toFixed(2)}</div>
                        <div style="color: #7f8c8d; margin: 10px 0; font-size: 0.9rem;">${displayDesc}</div>
                        <a href="#" class="btn" style="width: 100%; text-align: center; display: block; margin-top: 15px;">Add to Cart</a>
                    </div>
                `;
                card.onmouseover = () => card.style.transform = 'translateY(-5px)';
                card.onmouseout = () => card.style.transform = 'translateY(0)';
                productGrid.appendChild(card);
            });
            if (searchTerm) {
                searchStats.innerHTML = `Showing <strong>${productsToRender.length}</strong> of ${products.length} products matching "<em>${searchTerm}</em>"`;
            } else {
                searchStats.textContent = `Showing all ${products.length} products`;
            }
        }

        searchInput.addEventListener('input', function() {
            const term = this.value.trim().toLowerCase();
            if (term === '') { renderProducts(products); return; }
            const filtered = products.filter(p => p.name.toLowerCase().includes(term) || p.description.toLowerCase().includes(term) || p.price.toString().includes(term));
            renderProducts(filtered, term);
        });

        renderProducts(products);
    </script>

</body>
</html>
