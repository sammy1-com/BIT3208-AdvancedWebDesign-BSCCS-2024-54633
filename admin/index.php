<?php
// WEEK 5 - Admin Dashboard
$page_title = 'Admin Dashboard';
require_once '../includes/header.php';
if (!is_admin()) redirect('/pitstop/index.php');

$products_count = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];
$orders_count   = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'];
$users_count    = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];
$revenue        = $conn->query("SELECT SUM(total) AS r FROM orders WHERE status != 'cancelled'")->fetch_assoc()['r'] ?? 0;
?>
<div class="page-header"><h1>Admin Dashboard</h1></div>
<div class="page-body">
    <div class="admin-stats-grid">
        <div class="admin-stat-card"><div class="stat-value"><?php echo $products_count; ?></div><div class="stat-label">Products</div></div>
        <div class="admin-stat-card"><div class="stat-value"><?php echo $orders_count; ?></div><div class="stat-label">Orders</div></div>
        <div class="admin-stat-card"><div class="stat-value"><?php echo $users_count; ?></div><div class="stat-label">Users</div></div>
        <div class="admin-stat-card"><div class="stat-value"><?php echo format_price($revenue); ?></div><div class="stat-label">Revenue</div></div>
    </div>
    <div style="margin-top:40px;">
        <a href="add-product.php" class="btn-gold" style="margin-right:16px;">+ Add Product</a>
        <a href="products.php" class="filter-btn" style="margin-right:16px;">Manage Products</a>
        <a href="orders.php" class="filter-btn">View Orders</a>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>
