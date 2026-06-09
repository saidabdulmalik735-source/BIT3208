<?php
session_start();
// If already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ShopMart</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">🛒 ShopMart</div>
                <div>
                    <a href="index.php" style="color: white; text-decoration: none;">← Back to Home</a>
                </div>
            </div>
        </div>
    </header>

    <div class="form-container" style="margin-top: 60px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-size: 2rem; color: #667eea; margin-bottom: 10px;">🛒</div>
            <h2 style="color: #2c3e50;">Sign In to Your Account</h2>
            <p style="color: #7f8c8d;">Enter your credentials to access the dashboard</p>
        </div>

        <?php
        // Display error from authenticate.php
        if (isset($_SESSION['login_error'])) {
            echo '<div class="status error">' . $_SESSION['login_error'] . '</div>';
            unset($_SESSION['login_error']);
        }
        // Display logout success
        if (isset($_SESSION['logout_msg']) && !empty($_SESSION['logout_msg'])) {
            echo '<div class="status success">' . $_SESSION['logout_msg'] . '</div>';
            unset($_SESSION['logout_msg']);
        }
        ?>

        <form action="authenticate.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
        </form>

        <div style="text-align: center; margin-top: 20px; color: #7f8c8d;">
            <p>Don't have an account? <a href="register.php" style="color: #667eea; text-decoration: none; font-weight: 600;">Register here</a></p>
        </div>

       
    </div>

</body>
</html>
