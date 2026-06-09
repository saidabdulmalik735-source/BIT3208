<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // No active session - redirect to login
    header("Location: login.php");
    exit();
}

// User is logged in - get session data
$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ShopMart</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">🛒 ShopMart</div>
                <div class="user-info">
                    <span class="user-name"> Welcome, <?php echo htmlspecialchars($username); ?></span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <nav class="navbar">
        <div class="container">
            <ul class="nav-links">
                <li><a href="index.php"> Home</a></li>
                <li><a href="dashboard.php" class="active"> Dashboard</a></li>
                <li><a href="add_product.php"> Add Product</a></li>
                <li><a href="test_db.php"> Test DB</a></li>
            </ul>
        </div>
    </nav>

    <div class="container" style="padding: 40px 20px;">
        <h1 style="color: #2c3e50; margin-bottom: 10px;">Dashboard</h1>
        <p style="color: #7f8c8d; margin-bottom: 30px;">
            You are logged in as <strong><?php echo htmlspecialchars($email); ?></strong>
        </p>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-icon">📦</div>
                <div class="card-title">Total Products</div>
                <div class="card-value">8</div>
            </div>
            <div class="dashboard-card">
                <div class="card-icon">👥</div>
                <div class="card-title">Registered Users</div>
                <div class="card-value">3</div>
            </div>
            <div class="dashboard-card">
                <div class="card-icon">🛒</div>
                <div class="card-title">Orders Today</div>
                <div class="card-value">0</div>
            </div>
            <div class="dashboard-card">
                <div class="card-icon"></div>
                <div class="card-title">Revenue</div>
                <div class="card-value">$0</div>
            </div>
        </div>

        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); margin-top: 30px;">
            <h3 style="color: #2c3e50; margin-bottom: 15px;">🔐 Session Information</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="border-bottom: 1px solid #e0e0e0;">
                    <td style="padding: 10px; font-weight: 600;">Session ID</td>
                    <td style="padding: 10px; color: #667eea;"><?php echo session_id(); ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #e0e0e0;">
                    <td style="padding: 10px; font-weight: 600;">User ID</td>
                    <td style="padding: 10px;"><?php echo $_SESSION['user_id']; ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #e0e0e0;">
                    <td style="padding: 10px; font-weight: 600;">Username</td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($_SESSION['username']); ?></td>
                </tr>
                <tr>
                    <td style="padding: 10px; font-weight: 600;">Login Time</td>
                    <td style="padding: 10px;"><?php echo date('Y-m-d H:i:s'); ?></td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 30px;">
            <a href="index.php" class="btn btn-primary">← Back to Store</a>
            <a href="logout.php" class="btn btn-danger" style="margin-left: 10px;">Logout</a>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="copyright">
                <p>&copy; 2026 ShopMart E-Commerce. | BIT3208 Week 4 Project</p>
            </div>
        </div>
    </footer>

</body>
</html>
