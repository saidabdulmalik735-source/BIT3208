<?php
// ============================================
// ADMIN PROTECTION - MUST BE FIRST
// ============================================
session_start();

// Check 1: Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check 2: Must be admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // Not an admin — show access denied page instead of redirecting
    $access_denied = true;
} else {
    $access_denied = false;
    include 'includes/db_connect.php';

    $message = "";
    $messageClass = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $price = floatval($_POST['price']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        
        if (empty($name) || $price <= 0) {
            $message = "Product name is required and price must be greater than 0!";
            $messageClass = "error";
        } else {
            $sql = "INSERT INTO products (name, price, description) VALUES ('$name', $price, '$description')";
            if (mysqli_query($conn, $sql)) {
                $message = "Product '$name' added successfully!";
                $messageClass = "success";
            } else {
                $message = "Error: " . mysqli_error($conn);
                $messageClass = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - ShopMart</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">🛒 ShopMart</div>
                <div class="user-info">
                    <span class="user-name">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="dashboard.php" class="logout-btn">Dashboard</a>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container" style="padding: 40px 20px;">

        <?php if ($access_denied): ?>

            <!-- ACCESS DENIED FOR NON-ADMINS -->
            <div class="access-denied">
                <h2>🚫 Access Denied</h2>
                <p>You do not have permission to add products.</p>
                <p><strong>Admin privileges required.</strong></p>
                <a href="dashboard.php" class="btn btn-primary">← Back to Dashboard</a>
                <a href="index.php" class="btn" style="margin-left: 10px;">Go to Store</a>
            </div>

        <?php else: ?>

            <!-- ADMIN ADD PRODUCT FORM -->
            <div class="form-container" style="max-width: 600px;">
                <h2 style="color: #2c3e50; margin-bottom: 10px;">➕ Add New Product</h2>
                <p style="color: #7f8c8d; margin-bottom: 25px;">Enter product details below.</p>

                <?php if (!empty($message)): ?>
                    <div class="status <?php echo $messageClass; ?>"><?php echo $message; ?></div>
                <?php endif; ?>

                <form action="add_product.php" method="post">
                    <div class="form-group">
                        <label>Product Name *</label>
                        <input type="text" name="name" placeholder="e.g., Wireless Bluetooth Speaker" required>
                    </div>
                    <div class="form-group">
                        <label>Price (USD) *</label>
                        <input type="number" name="price" step="0.01" min="0.01" placeholder="e.g., 49.99" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" placeholder="Enter product description..." style="min-height: 120px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Add Product</button>
                </form>

                <div style="margin-top: 20px;">
                    <a href="index.php" style="color: #667eea; text-decoration: none;">← Return to Homepage</a>
                </div>
            </div>

        <?php endif; ?>

    </div>

    <footer class="footer">
        <div class="container">
            <div class="copyright">
                <p>&copy; 2026 ShopMart. | BIT3208 Week 5</p>
            </div>
        </div>
    </footer>

</body>
</html>