<?php
// WEEK 5 - Admin: Add new product (CREATE)
$page_title = 'Add Product';
require_once '../includes/header.php';
if (!is_admin()) redirect('/pitstop/index.php');

$categories = get_categories($conn);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = trim($_POST['name'] ?? '');
    $brand     = trim($_POST['brand'] ?? '');
    $price     = (float)($_POST['price'] ?? 0);
    $stock     = (int)($_POST['stock'] ?? 0);
    $cat_id    = (int)($_POST['category_id'] ?? 0);
    $part_no   = trim($_POST['part_number'] ?? '');
    $desc      = trim($_POST['description'] ?? '');
    $condition = $_POST['condition_type'] ?? 'new';
    $image_url = trim($_POST['image_url'] ?? '');
    $slug      = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name)) . '-' . time();

    if (!$name || !$brand || !$price) {
        $error = 'Name, brand, and price are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name, brand, price, stock, category_id, part_number, description, condition_type, image_url, slug, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,1)");
        $stmt->bind_param('ssdiissss s', $name, $brand, $price, $stock, $cat_id, $part_no, $desc, $condition, $image_url, $slug);
        if ($stmt->execute()) {
            $success = 'Product added successfully.';
        } else {
            $error = 'Failed to add product.';
        }
    }
}
?>
<div class="page-header"><h1>Add New Product</h1></div>
<div class="page-body" style="max-width:700px;">
    <?php if ($error): ?><div class="alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
    <form method="POST">
        <label class="form-label">Product Name *</label>
        <input type="text" name="name" class="form-control" required>
        <label class="form-label">Brand *</label>
        <input type="text" name="brand" class="form-control" required>
        <label class="form-label">Part Number</label>
        <input type="text" name="part_number" class="form-control">
        <label class="form-label">Price (KSh) *</label>
        <input type="number" name="price" class="form-control" min="0" step="0.01" required>
        <label class="form-label">Stock Quantity</label>
        <input type="number" name="stock" class="form-control" min="0" value="0">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-control">
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <label class="form-label">Condition</label>
        <select name="condition_type" class="form-control">
            <option value="new">New</option>
            <option value="oem">OEM</option>
            <option value="aftermarket">Aftermarket</option>
            <option value="refurbished">Refurbished</option>
        </select>
        <label class="form-label">Image URL</label>
        <input type="text" name="image_url" class="form-control" placeholder="assets/images/product.jpg">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4"></textarea>
        <button type="submit" class="btn-submit" style="margin-top:8px;">Add Product</button>
        <a href="products.php" style="margin-left:16px;color:var(--taupe);">Cancel</a>
    </form>
</div>
<?php require_once '../includes/footer.php'; ?>
