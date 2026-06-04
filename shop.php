<?php
// WEEK 4 - Shop: Now connected to real DB with basic filtering
$page_title = 'Shop';
require_once 'includes/header.php';

$where_clauses = [];
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$category_slug = isset($_GET['category']) ? trim($_GET['category']) : '';

if ($q) {
    $q_safe = $conn->real_escape_string($q);
    $where_clauses[] = "(p.name LIKE '%$q_safe%' OR p.part_number LIKE '%$q_safe%' OR p.brand LIKE '%$q_safe%')";
}
if ($category_slug) {
    $cat_safe = $conn->real_escape_string($category_slug);
    $where_clauses[] = "c.slug = '$cat_safe'";
}

$where    = implode(' AND ', $where_clauses);
$products = get_all_products($conn, $where, 12, 0);
$categories = get_categories($conn);

$count_sql = "SELECT COUNT(*) AS total FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1" . ($where ? " AND $where" : "");
$total = $conn->query($count_sql)->fetch_assoc()['total'];
?>
<div class="shop-header">
    <h1>The Catalogue</h1>
    <p>Browse our full range of genuine and aftermarket parts</p>
</div>
<div class="shop-body">
    <div class="shop-filters">
        <form method="GET" action="shop.php">
            <div class="filter-row">
                <input type="text" name="q" class="filter-input" placeholder="Search part name or number" value="<?php echo htmlspecialchars($q); ?>">
                <select name="category" class="filter-select">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat['slug']); ?>" <?php echo $category_slug === $cat['slug'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="filter-btn">Filter</button>
                <a href="shop.php" class="filter-btn" style="background:transparent;color:var(--taupe);border:1px solid var(--taupe);">Clear</a>
            </div>
        </form>
    </div>
    <div class="results-info"><?php echo $total; ?> PRODUCTS FOUND</div>
    <?php if (empty($products)): ?>
    <div style="text-align:center;padding:80px 0;">
        <p style="color:var(--taupe);font-size:20px;">No parts found.</p>
        <a href="shop.php" class="btn-gold" style="display:inline-block;margin-top:24px;">Browse All Parts</a>
    </div>
    <?php else: ?>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
        <a href="product.php?slug=<?php echo urlencode($product['slug']); ?>" class="product-card">
            <div class="product-img">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <div class="product-badges">
                    <span class="badge-condition badge-<?php echo $product['condition_type']; ?>"><?php echo ucfirst($product['condition_type']); ?></span>
                </div>
            </div>
            <div class="product-body">
                <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                <div class="product-part-no"><?php echo htmlspecialchars($product['part_number']); ?></div>
                <div class="product-footer">
                    <div class="product-price"><?php echo format_price($product['price']); ?></div>
                    <button class="btn-add-cart">Add to Cart</button>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
