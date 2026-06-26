<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_login();
$uid = (int)$_SESSION['user_id'];
$orders = $conn->query("SELECT * FROM orders WHERE user_id = $uid ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
foreach ($orders as &$order) {
    $oid = (int)$order['id'];
    $order['items'] = $conn->query("SELECT oi.*, p.name, p.image_url FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $oid")->fetch_all(MYSQLI_ASSOC);
}
unset($order);
$page_title = 'My Orders';
require_once 'includes/header.php';
?>
<div class="page-header"><h1>My Orders</h1></div>
<div class="page-body">
    <?php if (empty($orders)): ?>
    <div style="text-align:center;padding:80px 0;">
        <p style="font-family:var(--font-body);font-size:20px;color:var(--taupe);">You haven't placed any orders yet.</p>
        <a href="/shop.php" class="btn-gold" style="display:inline-block;margin-top:24px;">Start Shopping</a>
    </div>
    <?php else: ?>
    <div class="orders-list">
        <?php foreach ($orders as $order): ?>
        <div class="order-card">
            <div class="order-card-header">
                <div>
                    <div class="order-number">Order #<?php echo $order['id']; ?></div>
                    <div class="order-date"><?php echo date('d M Y', strtotime($order['created_at'])); ?></div>
                </div>
                <span class="order-status order-status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span>
            </div>
            <div class="order-card-items">
                <?php foreach ($order['items'] as $item): ?>
                <div class="order-item-row">
                    <span><?php echo htmlspecialchars($item['name'] ?? 'Product'); ?> x<?php echo $item['quantity']; ?></span>
                    <span><?php echo format_price($item['price_at_purchase'] * $item['quantity']); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="order-card-footer">
                <span>Total</span>
                <span><?php echo format_price($order['total']); ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
