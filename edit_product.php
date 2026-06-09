<?php
session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: products.php");
    exit();
}

include '../includes/db_connect.php';

$message = "";
$messageClass = "";

// Get product ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch existing product
$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    header("Location: products.php");
    exit();
}

$product = mysqli_fetch_assoc($result);

// Handle UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    if (empty($name) || $price <= 0) {
        $message = "Product name is required and price must be greater than 0!";
        $messageClass = "error";
    } else {
        $update_sql = "UPDATE products SET 
                       name = '$name', 
                       price = $price, 
                       description = '$description' 
                       WHERE id = $id";
        
        if (mysqli_query($conn, $update_sql)) {
            header("Location: products.php?msg=updated");
            exit();
        } else {
            $message = "Error updating: " . mysqli_error($conn);
            $messageClass = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo"> ShopMart Admin</div>
                <div class="user-info">
                    <span class="user-name"> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="products.php" class="logout-btn">← Back to Products</a>
                    <a href="../logout.php" class="logout-btn">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container" style="padding: 40px 20px;">
        <div class="form-container" style="max-width: 600px;">
            <h2 style="color: #2c3e50; margin-bottom: 10px;"> Edit Product</h2>
            <p style="color: #7f8c8d; margin-bottom: 25px;">Editing: <strong><?php echo htmlspecialchars($product['name']); ?></strong> (ID: <?php echo $product['id']; ?>)</p>

            <?php if (!empty($message)): ?>
                <div class="status <?php echo $messageClass; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="edit_product.php?id=<?php echo $id; ?>" method="post">
                <div class="form-group">
                    <label>Product Name *</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Price (USD) *</label>
                    <input type="number" name="price" step="0.01" min="0.01" value="<?php echo $product['price']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" style="min-height: 120px;"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;"> Update Product</button>
            </form>

            <div style="margin-top: 20px;">
                <a href="products.php" style="color: #667eea; text-decoration: none;">← Cancel and Return</a>
            </div>
        </div>
    </div>

</body>
</html>
