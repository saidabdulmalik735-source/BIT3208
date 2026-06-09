<?php
session_start();

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    $access_denied = true;
} else {
    $access_denied = false;
    include '../includes/db_connect.php';

    // Handle DELETE request
    if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
        $id = intval($_GET['delete']);
        $delete_sql = "DELETE FROM products WHERE id = $id";
        mysqli_query($conn, $delete_sql);
        header("Location: products.php?msg=deleted");
        exit();
    }

    // Fetch all products
    $result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Product Management</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">🛒 ShopMart Admin</div>
                <div class="user-info">
                    <span class="user-name"> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="../dashboard.php" class="logout-btn">Dashboard</a>
                    <a href="../logout.php" class="logout-btn">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <nav class="navbar">
        <div class="container">
            <ul class="nav-links">
                <li><a href="../index.php"> Store</a></li>
                <li><a href="../dashboard.php"> Dashboard</a></li>
                <li><a href="products.php" class="active"> Manage Products</a></li>
                <li><a href="../add_product.php"> Add Product</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">

        <?php if ($access_denied): ?>

            <!-- ACCESS DENIED FOR NON-ADMINS -->
            <div class="access-denied">
                <h2> Access Denied</h2>
                <p>You do not have permission to view this page.</p>
                <p><strong>Admin privileges required.</strong></p>
                <a href="../dashboard.php" class="btn btn-primary">← Back to Dashboard</a>
                <a href="../index.php" class="btn" style="margin-left: 10px;">Go to Store</a>
            </div>

        <?php else: ?>

            <!-- ADMIN CRUD PANEL -->
            <div class="admin-container">

                <div class="admin-header">
                    <div>
                        <h2 class="admin-title"> Product Management</h2>
                        <p style="color: #7f8c8d;">Manage your store inventory (CRUD Operations)</p>
                    </div>
                    <a href="../add_product.php" class="btn-add"> Add New Product</a>
                </div>

                <?php
                if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
                    echo '<div class="status success"> Product deleted successfully.</div>';
                }
                if (isset($_GET['msg']) && $_GET['msg'] == 'updated') {
                    echo '<div class="status success"> Product updated successfully.</div>';
                }
                ?>

                <table class="crud-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Price (USD)</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
                                echo "<td class='price'>$" . number_format($row['price'], 2) . "</td>";
                                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>" . $row['created_at'] . "</td>";
                                echo "<td>";
                                echo "<a href='edit_product.php?id=" . $row['id'] . "' class='btn-small btn-edit'>✏️ Edit</a>";
                                echo "<a href='products.php?delete=" . $row['id'] . "' class='btn-small btn-delete' onclick='return confirmDelete()'>🗑️ Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='no-records'>No products found. <a href='../add_product.php'>Add one now</a>.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

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

    <script>
        function confirmDelete() {
            return confirm(' Are you sure you want to delete this product? This action cannot be undone.');
        }
    </script>

</body>
</html>
