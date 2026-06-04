<?php
$page_title = 'Checkout';
require_once 'includes/header.php';
require_login();

$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $id = (int)$pid;
        $result = $conn->query("SELECT * FROM products WHERE id = $id AND is_active = 1");
        $p = $result->fetch_assoc();
        if ($p) {
            $p['qty'] = $qty;
            $p['subtotal'] = $p['price'] * $qty;
            $cart_items[] = $p;
            $total += $p['subtotal'];
        }
    }
}

if (empty($cart_items)) redirect('/cart.php');

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ship_name = trim($_POST['shipping_name'] ?? '');
    $ship_phone = trim($_POST['shipping_phone'] ?? '');
    $ship_address = trim($_POST['shipping_address'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if (!$ship_name || !$ship_phone || !$ship_address) {
        $error = 'Please fill in all required shipping fields.';
    } else {
        $uid = (int)$_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total, shipping_name, shipping_phone, shipping_address, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('idssss', $uid, $total, $ship_name, $ship_phone, $ship_address, $notes);
        $stmt->execute();
        $order_id = $conn->insert_id;

        foreach ($cart_items as $item) {
            $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param('iiid', $order_id, $item['id'], $item['qty'], $item['price']);
            $stmt2->execute();
            $conn->query("UPDATE products SET stock = stock - " . (int)$item['qty'] . " WHERE id = " . (int)$item['id']);
        }

        $_SESSION['cart'] = [];
        $success = true;
        $placed_order_id = $order_id;
    }
}
?>

<div class="page-header">
    <h1>Checkout</h1>
</div>

<div class="page-body">
    <?php if ($success): ?>
    <div style="text-align:center;padding:80px 0;">
        <h2 style="font-family:var(--font-display);font-size:36px;color:var(--black);letter-spacing:2px;margin-bottom:16px;">Order Placed</h2>
        <p style="font-family:var(--font-body);font-size:18px;color:var(--taupe);">Thank you. Your order #<?php echo $placed_order_id; ?> has been received. We will contact you shortly.</p>
        <a href="/index.php" class="btn-gold" style="display:inline-block;margin-top:32px;">Back to Store</a>
    </div>
    <?php else: ?>
    <?php if ($error): ?>
    <div class="alert-error" style="margin-bottom:24px;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-7">
            <h3 style="font-family:var(--font-display);font-size:24px;letter-spacing:2px;margin-bottom:28px;">Shipping Details</h3>
            <form method="POST">
                <label class="form-label">Full Name</label>
                <input type="text" name="shipping_name" class="form-control" required value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>">
                <label class="form-label">Phone Number</label>
                <input type="text" name="shipping_phone" class="form-control" required>
                <label class="form-label">Delivery Address</label>
                <input type="text" name="shipping_address" class="form-control" required placeholder="Street, Town, City">
                <label class="form-label">Order Notes (optional)</label>
                <textarea name="notes" class="form-control" rows="3" style="resize:vertical;"></textarea>
                <button type="submit" class="btn-submit" style="margin-top:8px;">Place Order</button>
            </form>
        </div>
        <div class="col-md-5">
            <div class="cart-summary">
                <div class="summary-title">Order Summary</div>
                <?php foreach ($cart_items as $item): ?>
                <div class="summary-row">
                    <span><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['qty']; ?></span>
                    <span><?php echo format_price($item['subtotal']); ?></span>
                </div>
                <?php endforeach; ?>
                <div class="summary-total">
                    <span>Total</span>
                    <span><?php echo format_price($total); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
