<?php
// WEEK 4 - Cart: Session-based cart (no checkout yet, comes in Week 5)
$page_title = 'Cart';
require_once 'includes/header.php';

// Handle remove item
if (isset($_GET['remove'])) {
    $pid = (int)$_GET['remove'];
    unset($_SESSION['cart'][$pid]);
    redirect('/pitstop/cart.php');
}

// Handle update quantities
if (isset($_POST['update_cart'])) {
    if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $pid => $qty) {
            $pid = (int)$pid;
            $qty = (int)$qty;
            if ($qty <= 0) {
                unset($_SESSION['cart'][$pid]);
            } else {
                $_SESSION['cart'][$pid] = $qty;
            }
        }
    }
    redirect('/pitstop/cart.php');
}

// Build cart items from session
$cart_items = [];
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $id     = (int)$pid;
        $result = $conn->query("SELECT * FROM products WHERE id = $id AND is_active = 1");
        $p      = $result->fetch_assoc();
        if ($p) {
            $p['qty']      = $qty;
            $p['subtotal'] = $p['price'] * $qty;
            $cart_items[]  = $p;
        }
    }
}

$total = array_sum(array_column($cart_items, 'subtotal'));
?>

<div class="page-header"><h1>Your Cart</h1></div>
<div class="page-body">
    <?php if (empty($cart_items)): ?>
    <div style="text-align:center;padding:80px 0;">
        <p style="font-family:var(--font-body);font-size:22px;color:var(--taupe);">Your cart is empty.</p>
        <a href="shop.php" class="btn-gold" style="display:inline-block;margin-top:28px;">Continue Shopping</a>
    </div>
    <?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <form method="POST">
                <table class="cart-table">
                    <thead>
                        <tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="cart-product-img" alt="">
                                <div>
                                    <div class="cart-product-brand"><?php echo htmlspecialchars($item['brand']); ?></div>
                                    <div class="cart-product-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                </div>
                            </td>
                            <td><?php echo format_price($item['price']); ?></td>
                            <td>
                                <input type="number" name="quantities[<?php echo $item['id']; ?>]"
                                    value="<?php echo $item['qty']; ?>" min="0" max="<?php echo $item['stock']; ?>" class="cart-qty">
                            </td>
                            <td><?php echo format_price($item['subtotal']); ?></td>
                            <td><a href="cart.php?remove=<?php echo $item['id']; ?>" class="btn-remove">Remove</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" name="update_cart" class="filter-btn" style="margin-top:20px;">Update Cart</button>
            </form>
        </div>
        <div class="col-md-4">
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
                <!-- WEEK 4: Checkout page comes in Week 5 -->
                <?php if (is_logged_in()): ?>
                <p style="color:var(--taupe);font-size:13px;margin-top:16px;">Checkout coming in Week 5</p>
                <?php else: ?>
                <a href="login.php" class="btn-checkout" style="display:block;text-align:center;">Login to Checkout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
