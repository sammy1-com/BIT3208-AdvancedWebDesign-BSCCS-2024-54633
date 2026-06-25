<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_manager_or_above();

if (isset($_POST['update_status'])) {
    $oid = (int)$_POST['order_id'];
    $status = $conn->real_escape_string($_POST['status']);
    $conn->query("UPDATE orders SET status = '$status' WHERE id = $oid");
    redirect('/admin/orders.php');
}

$view_id = (int)($_GET['id'] ?? 0);
$order = null;
$order_items = [];

if ($view_id) {
    $order = $conn->query("SELECT o.*, u.name AS user_name, u.email AS user_email FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = $view_id")->fetch_assoc();
    if ($order) {
        $order_items = $conn->query("SELECT oi.*, p.name AS product_name, p.image_url FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $view_id")->fetch_all(MYSQLI_ASSOC);
    }
}

$orders = $conn->query("SELECT o.*, u.name AS user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Orders — Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=EB+Garamond:ital,wght@0,400;0,500&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-logo">Pit<span>Stop</span></div>
        <nav class="sidebar-nav">
            <a href="/admin/index.php">Dashboard</a>
            <a href="/admin/products.php">Products</a>
            <a href="/admin/add-product.php">Add Product</a>
            <a href="/admin/orders.php" class="active">Orders</a>
            <?php if (is_admin()): ?>
            <a href="/admin/users.php">Manage Users</a>
            <?php endif; ?>
            <a href="/index.php">View Store</a>
            <a href="/logout.php">Logout</a>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-topbar">
            <h1>Orders</h1>
        </div>
        <div class="admin-content">
            <?php if ($order): ?>
            <div style="margin-bottom:32px;">
                <a href="/admin/orders.php" class="btn-admin btn-admin-dark" style="margin-bottom:24px;display:inline-block;">Back to All Orders</a>
                <div style="display:flex;gap:32px;margin-bottom:24px;flex-wrap:wrap;">
                    <div>
                        <div style="font-family:var(--font-ui);font-size:9px;letter-spacing:2px;color:var(--taupe);text-transform:uppercase;margin-bottom:4px;">Order</div>
                        <div style="font-family:var(--font-display);font-size:22px;">#<?php echo $order['id']; ?></div>
                    </div>
                    <div>
                        <div style="font-family:var(--font-ui);font-size:9px;letter-spacing:2px;color:var(--taupe);text-transform:uppercase;margin-bottom:4px;">Customer</div>
                        <div style="font-family:var(--font-body);font-size:17px;"><?php echo htmlspecialchars($order['user_name'] ?? 'Guest'); ?></div>
                    </div>
                    <div>
                        <div style="font-family:var(--font-ui);font-size:9px;letter-spacing:2px;color:var(--taupe);text-transform:uppercase;margin-bottom:4px;">Total</div>
                        <div style="font-family:var(--font-display);font-size:22px;"><?php echo format_price($order['total']); ?></div>
                    </div>
                    <div>
                        <div style="font-family:var(--font-ui);font-size:9px;letter-spacing:2px;color:var(--taupe);text-transform:uppercase;margin-bottom:4px;">Shipping To</div>
                        <div style="font-family:var(--font-body);font-size:16px;"><?php echo htmlspecialchars($order['shipping_name']); ?><br><?php echo htmlspecialchars($order['shipping_phone']); ?><br><?php echo htmlspecialchars($order['shipping_address']); ?></div>
                    </div>
                </div>
                <form method="POST" style="margin-bottom:28px;display:flex;gap:12px;align-items:center;">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <select name="status" class="filter-select">
                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                        <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                        <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                    <button type="submit" name="update_status" class="btn-admin btn-admin-gold">Update Status</button>
                </form>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td style="display:flex;align-items:center;gap:12px;">
                                    <img src="/<?php echo htmlspecialchars($item['image_url']); ?>" style="width:48px;height:48px;object-fit:cover;border-radius:3px;">
                                    <?php echo htmlspecialchars($item['product_name']); ?>
                                </td>
                                <td><?php echo format_price($item['price_at_purchase']); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo format_price($item['price_at_purchase'] * $item['quantity']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else: ?>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Shipping Name</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $o): ?>
                        <tr>
                            <td>#<?php echo $o['id']; ?></td>
                            <td><?php echo htmlspecialchars($o['user_name'] ?? 'Guest'); ?></td>
                            <td><?php echo htmlspecialchars($o['shipping_name']); ?></td>
                            <td><?php echo format_price($o['total']); ?></td>
                            <td><?php echo ucfirst($o['status']); ?></td>
                            <td><?php echo date('d M Y', strtotime($o['created_at'])); ?></td>
                            <td><a href="orders.php?id=<?php echo $o['id']; ?>" class="btn-admin btn-admin-gold">View</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>
