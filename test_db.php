<?php
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Test - ShopMart</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            padding: 40px 20px;
        }
        .container { max-width: 1000px; margin: 0 auto; }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        .header h1 { font-size: 2rem; margin-bottom: 10px; }
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .card h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .status-box {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .status-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        pre {
            background: #2c3e50;
            color: #2ecc71;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        code {
            background: #ecf0f1;
            padding: 2px 6px;
            border-radius: 4px;
            color: #e74c3c;
            font-family: 'Courier New', monospace;
        }
        .nav-back {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background: #667eea;
            color: white;
        }
        tr:hover { background: #f5f6fa; }
        .count-badge {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Database Connection Test</h1>
        <p>BIT3208 Week 4 - PHP Data Fetching Demonstration</p>
    </div>

    <!-- Connection Status -->
    <div class="card">
        <h2>1. Connection Status</h2>
        <?php
        if ($conn) {
            echo '<div class="status-box status-success">';
            echo '<strong>Connected Successfully to week3db</strong><br>';
            echo 'Server: ' . mysqli_get_server_info($conn) . '<br>';
            echo 'Host: localhost | User: root';
            echo '</div>';
        } else {
            echo '<div class="status-box status-error">';
            echo '<strong>❌ Connection Failed:</strong> ' . mysqli_connect_error();
            echo '</div>';
        }
        ?>
    </div>

    <!-- Products Table - print_r() Output -->
    <div class="card">
        <h2>2. Raw PHP Output: <code>print_r()</code> on Products</h2>
        <p>This demonstrates PHP fetching data from MySQL and outputting the raw array structure:</p>
        
        <pre><?php
        $sql = "SELECT * FROM products";
        $result = mysqli_query($conn, $sql);
        
        if ($result) {
            $products_array = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $products_array[] = $row;
            }
            
            echo "// PHP Array fetched from 'products' table in week3db\n";
            echo "// Using: mysqli_query() + mysqli_fetch_assoc() + print_r()\n\n";
            print_r($products_array);
            
            $product_count = count($products_array);
        } else {
            echo "Error: " . mysqli_error($conn);
            $product_count = 0;
        }
        ?></pre>
    </div>

    <!-- Products Table - Formatted HTML Table -->
    <div class="card">
        <h2>3. Formatted Display: Products Table <span class="count-badge"><?php echo $product_count; ?> records</span></h2>
        <p>This demonstrates the same data rendered as a professional HTML table:</p>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price (USD)</th>
                    <th>Description</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Re-fetch for the table display
                $result2 = mysqli_query($conn, "SELECT * FROM products");
                if ($result2 && mysqli_num_rows($result2) > 0) {
                    while ($row = mysqli_fetch_assoc($result2)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
                        echo "<td style='color: #e74c3c; font-weight: bold;'>$" . number_format($row['price'], 2) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align: center; color: #7f8c8d;'>No products found in database. <a href='add_product.php'>Add one now</a>.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Users Table -->
    <div class="card">
        <h2>4. Users Table <span class="count-badge"><?php 
            $user_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users")); 
            echo $user_count; 
        ?> records</span></h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $user_result = mysqli_query($conn, "SELECT id, username, email, created_at FROM users");
                if ($user_result && mysqli_num_rows($user_result) > 0) {
                    while ($row = mysqli_fetch_assoc($user_result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align: center; color: #7f8c8d;'>No users registered yet. <a href='register.php'>Register one now</a>.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- PHP Info Summary -->
    <div class="card">
        <h2>5. PHP Environment Info</h2>
        <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
        <p><strong>MySQLi Extension:</strong> <?php echo extension_loaded('mysqli') ? '✅ Enabled' : '❌ Disabled'; ?></p>
        <p><strong>Current Script:</strong> <?php echo $_SERVER['PHP_SELF']; ?></p>
        <p><strong>Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
    </div>

    <a href="index.php" class="nav-back">← Back to Homepage</a>
</div>

</body>
</html>

