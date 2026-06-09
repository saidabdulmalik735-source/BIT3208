<?php
session_start();
// If logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

include 'includes/db_connect.php';

$serverMessage = "";
$serverMessageClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($email) || empty($password)) {
        $serverMessage = "All fields are required!";
        $serverMessageClass = "error";
    } elseif (strlen($password) < 6) {
        $serverMessage = "Password must be at least 6 characters!";
        $serverMessageClass = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $serverMessage = "Invalid email format!";
        $serverMessageClass = "error";
    } else {
        // PLAIN TEXT for lesson demo (NOT for production!)
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        
        if (mysqli_query($conn, $sql)) {
            $serverMessage = "Registration successful! Welcome, $username. You can now <a href='login.php'>login</a>.";
            $serverMessageClass = "success";
        } else {
            if (mysqli_errno($conn) == 1062) {
                $serverMessage = "Error: Username or email already exists!";
            } else {
                $serverMessage = "Error: " . mysqli_error($conn);
            }
            $serverMessageClass = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ShopMart</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">🛒 ShopMart</div>
                <div><a href="index.php" style="color: white; text-decoration: none;">← Back to Home</a></div>
            </div>
        </div>
    </header>

    <div class="form-container" style="margin-top: 40px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-size: 2rem; color: #667eea; margin-bottom: 10px;">🛒</div>
            <h2 style="color: #2c3e50;">Create Your Account</h2>
            <p style="color: #7f8c8d;">Join ShopMart today</p>
        </div>

        <?php if (!empty($serverMessage)): ?>
            <div class="status <?php echo $serverMessageClass; ?>"><?php echo $serverMessage; ?></div>
        <?php endif; ?>

        <form id="registerForm" action="register.php" method="post" novalidate>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Choose a username" required>
                <div id="usernameError" style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: none;">Username must be at least 3 characters.</div>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="your@email.com" required>
                <div id="emailError" style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: none;">Please enter a valid email address.</div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Min 6 characters" required>
                <div id="passwordError" style="color: #e74c3c; font-size: 0.85rem; margin-top: 5px; display: none;">Password must be at least 6 characters.</div>
                <div id="passwordStrength" style="font-size: 0.8rem; margin-top: 3px;"></div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;" id="submitBtn">Create Account</button>
        </form>

        <div style="text-align: center; margin-top: 20px; color: #7f8c8d;">
            <p>Already have an account? <a href="login.php" style="color: #667eea; text-decoration: none; font-weight: 600;">Sign in</a></p>
        </div>
    </div>

    <script>
        const form = document.getElementById('registerForm');
        const u = document.getElementById('username'), e = document.getElementById('email'), p = document.getElementById('password');
        
        function validate() {
            let ok = true;
            if (u.value.trim().length < 3) { document.getElementById('usernameError').style.display = 'block'; u.classList.add('error-border'); ok = false; }
            else { document.getElementById('usernameError').style.display = 'none'; u.classList.remove('error-border'); }
            
            const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRe.test(e.value.trim())) { document.getElementById('emailError').style.display = 'block'; e.classList.add('error-border'); ok = false; }
            else { document.getElementById('emailError').style.display = 'none'; e.classList.remove('error-border'); }
            
            if (p.value.length < 6) { document.getElementById('passwordError').style.display = 'block'; p.classList.add('error-border'); ok = false; }
            else { 
                document.getElementById('passwordError').style.display = 'none'; p.classList.remove('error-border');
                const s = document.getElementById('passwordStrength');
                if (p.value.length < 8) { s.textContent = 'Strength: Weak'; s.style.color = '#f39c12'; }
                else if (p.value.length < 12) { s.textContent = 'Strength: Medium'; s.style.color = '#3498db'; }
                else { s.textContent = 'Strength: Strong'; s.style.color = '#27ae60'; }
            }
            return ok;
        }
        
        u.addEventListener('input', validate);
        e.addEventListener('input', validate);
        p.addEventListener('input', validate);
        
        form.addEventListener('submit', function(ev) {
            if (!validate()) ev.preventDefault();
        });
    </script>

</body>
</html>
