<?php
// WEEK 5 - Admin: List all products (READ)
$page_title = 'Manage Products';
require_once '../includes/header.php';
if (!is_admin()) redirect('/pitstop/index.php');

$products = $conn->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<div class="page-header"><h1>Manage Products</h1></div>
<div class="page-body">
    <a href="add-product.php" class="btn-gold" style="display:inline-block;margin-bottom:28px;">+ Add New Product</a>
    <table class="cart-table">
        <thead>
            <tr><th>Name</th><th>Brand</th><th>Price</th><th>Stock</th><th>Category</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo htmlspecialchars($p['brand']); ?></td>
                <td><?php echo format_price($p['price']); ?></td>
                <td><?php echo $p['stock']; ?></td>
                <td><?php echo htmlspecialchars($p['category_name'] ?? '—'); ?></td>
                <td><?php echo $p['is_active'] ? 'Active' : 'Hidden'; ?></td>
                <td>
                    <a href="edit-product.php?id=<?php echo $p['id']; ?>" class="filter-btn" style="padding:4px 10px;font-size:11px;">Edit</a>
                    <a href="delete-product.php?id=<?php echo $p['id']; ?>" class="btn-remove" onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../includes/footer.php'; ?>
