<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_admin();

$products_count = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];
$orders_count = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'];
$users_count = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role = 'customer'")->fetch_assoc()['c'];
$revenue = $conn->query("SELECT SUM(total) AS r FROM orders WHERE status != 'cancelled'")->fetch_assoc()['r'];
$recent_orders = $conn->query("SELECT o.*, u.name AS user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — PitStop Parts</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=EB+Garamond:ital,wght@0,400;0,500&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/pitstop/assets/css/style.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-logo">Pit<span>Stop</span></div>
        <nav class="sidebar-nav">
            <a href="/pitstop/admin/index.php" class="active">Dashboard</a>
            <a href="/pitstop/admin/products.php">Products</a>
            <a href="/pitstop/admin/add-product.php">Add Product</a>
            <a href="/pitstop/admin/orders.php">Orders</a>
            <a href="/pitstop/index.php">View Store</a>
            <a href="/pitstop/logout.php">Logout</a>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-topbar">
            <h1>Dashboard</h1>
            <span style="font-family:var(--font-ui);font-size:11px;color:var(--taupe);">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        </div>
        <div class="admin-content">
            <div class="stat-cards">
                <div class="stat-card">
                    <div class="stat-card-num"><?php echo $products_count; ?></div>
                    <div class="stat-card-label">Total Products</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-num"><?php echo $orders_count; ?></div>
                    <div class="stat-card-label">Total Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-num"><?php echo $users_count; ?></div>
                    <div class="stat-card-label">Customers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-num" style="font-size:24px;"><?php echo format_price($revenue ?? 0); ?></div>
                    <div class="stat-card-label">Total Revenue</div>
                </div>
            </div>

            <h3 style="font-family:var(--font-display);font-size:20px;letter-spacing:2px;margin-bottom:20px;">Recent Orders</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['user_name'] ?? $order['guest_email'] ?? 'Guest'); ?></td>
                        <td><?php echo format_price($order['total']); ?></td>
                        <td><?php echo ucfirst($order['status']); ?></td>
                        <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                        <td><a href="orders.php?id=<?php echo $order['id']; ?>" class="btn-admin btn-admin-gold">View</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
