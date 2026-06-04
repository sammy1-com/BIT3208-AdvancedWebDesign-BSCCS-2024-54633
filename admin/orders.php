<?php
// WEEK 5 - Admin: View and manage orders
$page_title = 'Orders';
require_once '../includes/header.php';
if (!is_admin()) redirect('/pitstop/index.php');

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $oid    = (int)$_POST['order_id'];
    $status = $conn->real_escape_string($_POST['status'] ?? 'pending');
    $conn->query("UPDATE orders SET status = '$status' WHERE id = $oid");
}

$orders = $conn->query("SELECT o.*, u.name AS customer_name, u.email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<div class="page-header"><h1>Orders</h1></div>
<div class="page-body">
    <table class="cart-table">
        <thead>
            <tr><th>#</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th>Update</th></tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
            <tr>
                <td>#<?php echo $o['id']; ?></td>
                <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
                <td><?php echo format_price($o['total']); ?></td>
                <td><?php echo ucfirst($o['status']); ?></td>
                <td><?php echo date('d M Y', strtotime($o['created_at'])); ?></td>
                <td>
                    <form method="POST" style="display:inline-flex;gap:8px;align-items:center;">
                        <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                        <select name="status" class="filter-select" style="padding:4px 8px;font-size:12px;">
                            <?php foreach (['pending','processing','shipped','delivered','cancelled'] as $s): ?>
                            <option value="<?php echo $s; ?>" <?php echo $o['status'] === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="filter-btn" style="padding:4px 10px;font-size:11px;">Save</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../includes/footer.php'; ?>
