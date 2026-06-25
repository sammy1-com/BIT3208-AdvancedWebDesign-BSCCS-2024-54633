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
    <p style="font-family:var(--font-ui);text-align:center;padding:60px 0;">You have no orders yet. <a href="shop.php" style="color:var(--gold);">Start shopping</a></p>
<?php else: ?>
    <?php foreach ($orders as $order): ?>
    <div style="border:1px solid var(--linen);margin-bottom:32px;padding:24px;">
        <div style="display:flex;justify-content:space-between;margin-bottom:16px;">
            <span style="font-family:var(--font-ui);font-size:11px;letter-spacing:2px;">ORDER #<?php echo $order['id']; ?></span>
            <span style="font-family:var(--font-ui);font-size:11px;color:var(--taupe);"><?php echo date('d M Y', strtotime($order['created_at'])); ?></span>
            <span style="font-family:var(--font-ui);font-size:11px;text-transform:uppercase;color:var(--gold);"><?php echo $order['status']; ?></span>
        </div>
        <?php foreach ($order['items'] as $item): ?>
        <div style="display:flex;gap:16px;align-items:center;padding:12px 0;border-top:1px solid var(--linen);">
            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" style="width:60px;height:60px;object-fit:cover;">
            <div style="flex:1;font-family:var(--font-ui);font-size:12px;"><?php echo htmlspecialchars($item['name']); ?></div>
            <div style="font-family:var(--font-ui);font-size:11px;">Qty: <?php echo $item['quantity']; ?></div>
            <div style="font-family:var(--font-ui);font-size:12px;color:var(--gold);"><?php echo format_price($item['price']); ?></div>
        </div>
        <?php endforeach; ?>
        <div style="text-align:right;padding-top:16px;font-family:var(--font-ui);font-size:13px;">
            Total: <strong><?php echo format_price($order['total']); ?></strong>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
