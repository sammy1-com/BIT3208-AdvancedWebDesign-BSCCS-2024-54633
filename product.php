<?php
require_once 'includes/header.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
if (!$slug) {
    redirect('/pitstop/shop.php');
}

$product = get_product_by_slug($conn, $slug);
if (!$product) {
    redirect('/pitstop/shop.php');
}

$page_title = $product['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $pid = (int)$product['id'];
    $qty = max(1, (int)($_POST['quantity'] ?? 1));
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $_SESSION['cart'][$pid] = ($SESSION['cart'][$pid] ?? 0) + $qty;
    if (!isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid] = $qty;
    } else {
        $_SESSION['cart'][$pid] += $qty;
    }
    redirect('/pitstop/cart.php');
}

$related_result = $conn->query("SELECT * FROM products WHERE category_id = " . (int)$product['category_id'] . " AND id != " . (int)$product['id'] . " AND is_active = 1 LIMIT 4");
$related = $related_result->fetch_all(MYSQLI_ASSOC);
?>

<div class="product-detail">
    <div class="row">
        <div class="col-md-6">
            <div class="product-detail-img">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="product-detail-body">
                <div class="detail-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                <h1 class="detail-name"><?php echo htmlspecialchars($product['name']); ?></h1>
                <div class="detail-partno">Part No: <?php echo htmlspecialchars($product['part_number']); ?></div>
                <div class="detail-price"><?php echo format_price($product['price']); ?></div>
                <div class="detail-stock">
                    <?php if ($product['stock'] > 0): ?>
                    <?php echo $product['stock']; ?> in stock
                    <?php else: ?>
                    Out of stock
                    <?php endif; ?>
                </div>
                <div class="detail-desc"><?php echo nl2br(htmlspecialchars($product['description'])); ?></div>

                <?php if ($product['stock'] > 0): ?>
                <form method="POST">
                    <div class="qty-row">
                        <button type="button" id="qty-minus" class="btn-gold" style="padding:10px 16px;">-</button>
                        <input type="number" id="qty-input" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" class="qty-input">
                        <button type="button" id="qty-plus" class="btn-gold" style="padding:10px 16px;">+</button>
                        <button type="submit" name="add_to_cart" class="btn-add-to-cart">Add to Cart</button>
                    </div>
                </form>
                <?php else: ?>
                <p style="font-family:var(--font-ui);font-size:11px;letter-spacing:2px;color:#c0392b;text-transform:uppercase;">Currently Out of Stock</p>
                <?php endif; ?>

                <div style="margin-top:24px;padding-top:24px;border-top:1px solid var(--linen);">
                    <div style="font-family:var(--font-ui);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:var(--taupe);margin-bottom:8px;">Condition</div>
                    <span class="badge-condition badge-<?php echo $product['condition_type']; ?>" style="font-size:10px;padding:5px 12px;">
                        <?php echo ucfirst($product['condition_type']); ?>
                    </span>
                    <div style="font-family:var(--font-ui);font-size:9px;letter-spacing:2px;text-transform:uppercase;color:var(--taupe);margin:16px 0 8px;">Category</div>
                    <a href="shop.php?category=<?php echo urlencode($product['category_name'] ?? ''); ?>" style="font-family:var(--font-ui);font-size:11px;color:var(--gold);">
                        <?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorised'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($related)): ?>
    <div style="margin-top:80px;">
        <h2 class="section-title" style="margin-bottom:32px;">Related Parts</h2>
        <div class="products-grid">
            <?php foreach ($related as $r): ?>
            <a href="product.php?slug=<?php echo urlencode($r['slug']); ?>" class="product-card">
                <div class="product-img">
                    <img src="<?php echo htmlspecialchars($r['image_url']); ?>" alt="<?php echo htmlspecialchars($r['name']); ?>">
                </div>
                <div class="product-body">
                    <div class="product-brand"><?php echo htmlspecialchars($r['brand']); ?></div>
                    <div class="product-name"><?php echo htmlspecialchars($r['name']); ?></div>
                    <div class="product-footer">
                        <div class="product-price"><?php echo format_price($r['price']); ?></div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
