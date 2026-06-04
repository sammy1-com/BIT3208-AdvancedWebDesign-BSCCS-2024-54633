<?php
// WEEK 5 - Admin: Edit product (UPDATE)
$page_title = 'Edit Product';
require_once '../includes/header.php';
if (!is_admin()) redirect('/pitstop/index.php');

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('/pitstop/admin/products.php');

$product    = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
if (!$product) redirect('/pitstop/admin/products.php');

$categories = get_categories($conn);
$error      = '';
$success    = '';

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
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!$name || !$brand || !$price) {
        $error = 'Name, brand, and price are required.';
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, brand=?, price=?, stock=?, category_id=?, part_number=?, description=?, condition_type=?, image_url=?, is_active=? WHERE id=?");
        $stmt->bind_param('ssdiiisssii', $name, $brand, $price, $stock, $cat_id, $part_no, $desc, $condition, $image_url, $is_active, $id);
        if ($stmt->execute()) {
            $success = 'Product updated successfully.';
            $product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
        } else {
            $error = 'Update failed.';
        }
    }
}
?>
<div class="page-header"><h1>Edit Product</h1></div>
<div class="page-body" style="max-width:700px;">
    <?php if ($error): ?><div class="alert-error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
    <form method="POST">
        <label class="form-label">Product Name *</label>
        <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($product['name']); ?>">
        <label class="form-label">Brand *</label>
        <input type="text" name="brand" class="form-control" required value="<?php echo htmlspecialchars($product['brand']); ?>">
        <label class="form-label">Part Number</label>
        <input type="text" name="part_number" class="form-control" value="<?php echo htmlspecialchars($product['part_number']); ?>">
        <label class="form-label">Price (KSh) *</label>
        <input type="number" name="price" class="form-control" step="0.01" required value="<?php echo $product['price']; ?>">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" min="0" value="<?php echo $product['stock']; ?>">
        <label class="form-label">Category</label>
        <select name="category_id" class="form-control">
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>" <?php echo $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <label class="form-label">Condition</label>
        <select name="condition_type" class="form-control">
            <?php foreach (['new','oem','aftermarket','refurbished'] as $c): ?>
            <option value="<?php echo $c; ?>" <?php echo $product['condition_type'] === $c ? 'selected' : ''; ?>><?php echo ucfirst($c); ?></option>
            <?php endforeach; ?>
        </select>
        <label class="form-label">Image URL</label>
        <input type="text" name="image_url" class="form-control" value="<?php echo htmlspecialchars($product['image_url']); ?>">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
        <label style="display:flex;align-items:center;gap:8px;margin-top:12px;">
            <input type="checkbox" name="is_active" <?php echo $product['is_active'] ? 'checked' : ''; ?>> Active (visible in shop)
        </label>
        <button type="submit" class="btn-submit" style="margin-top:16px;">Save Changes</button>
        <a href="products.php" style="margin-left:16px;color:var(--taupe);">Cancel</a>
    </form>
</div>
<?php require_once '../includes/footer.php'; ?>
