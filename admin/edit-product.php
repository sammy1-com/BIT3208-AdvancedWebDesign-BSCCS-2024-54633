<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('/admin/products.php');

$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();
if (!$product) redirect('/admin/products.php');

$categories = get_categories($conn);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $part_number = trim($_POST['part_number'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $condition_type = $_POST['condition_type'] ?? 'new';
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $image_url = $product['image_url'];

    if (!$name || !$price) {
        $error = 'Product name and price are required.';
    } else {
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($ext, $allowed)) {
                $error = 'Invalid image format.';
            } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                $error = 'Image must be under 5MB.';
            } else {
                $filename = 'part-' . time() . '.' . $ext;
                $dest = '../uploads/parts/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    if ($image_url && strpos($image_url, 'uploads/parts/') === 0 && file_exists('../' . $image_url)) {
                        unlink('../' . $image_url);
                    }
                    $image_url = 'uploads/parts/' . $filename;
                } else {
                    $error = 'Failed to upload image.';
                }
            }
        }

        if (!$error) {
            $stmt = $conn->prepare("UPDATE products SET name=?, part_number=?, brand=?, category_id=?, description=?, price=?, stock=?, condition_type=?, image_url=?, is_featured=? WHERE id=?");
            $stmt->bind_param('sssisdssii', $name, $part_number, $brand, $category_id, $description, $price, $stock, $condition_type, $image_url, $is_featured, $id);
            if ($stmt->execute()) {
                $success = 'Product updated successfully.';
                $product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
            } else {
                $error = 'Failed to update product.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Product — Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=EB+Garamond:ital,wght@0,400;0,500&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-logo">Pit<span>Stop</span></div>
        <nav class="sidebar-nav">
            <a href="/admin/index.php">Dashboard</a>
            <a href="/admin/products.php" class="active">Products</a>
            <a href="/admin/add-product.php">Add Product</a>
            <a href="/admin/orders.php">Orders</a>
            <a href="/index.php">View Store</a>
            <a href="/logout.php">Logout</a>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-topbar">
            <h1>Edit Product</h1>
            <a href="/admin/products.php" class="btn-admin btn-admin-dark">Back to Products</a>
        </div>
        <div class="admin-content">
            <?php if ($error): ?>
            <div class="alert-error" style="margin-bottom:24px;"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
            <div class="alert-success" style="margin-bottom:24px;"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form class="admin-form" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" class="form-control" value="<?php echo htmlspecialchars($product['brand']); ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Part Number</label>
                        <input type="text" name="part_number" class="form-control" value="<?php echo htmlspecialchars($product['part_number']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $product['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Price (KES) *</label>
                        <input type="number" name="price" class="form-control" step="0.01" min="0" required value="<?php echo $product['price']; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock" class="form-control" min="0" value="<?php echo $product['stock']; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Condition</label>
                        <select name="condition_type" class="form-select">
                            <option value="new" <?php echo $product['condition_type'] === 'new' ? 'selected' : ''; ?>>New</option>
                            <option value="oem" <?php echo $product['condition_type'] === 'oem' ? 'selected' : ''; ?>>OEM</option>
                            <option value="aftermarket" <?php echo $product['condition_type'] === 'aftermarket' ? 'selected' : ''; ?>>Aftermarket</option>
                            <option value="refurbished" <?php echo $product['condition_type'] === 'refurbished' ? 'selected' : ''; ?>>Refurbished</option>
                        </select>
                    </div>
                </div>
                <label class="form-label">Description</label>
                <textarea name="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                <?php if ($product['image_url']): ?>
                <div style="margin-bottom:12px;">
                    <img src="/<?php echo htmlspecialchars($product['image_url']); ?>" style="height:100px;width:auto;border-radius:3px;object-fit:cover;">
                </div>
                <?php endif; ?>
                <label class="form-label">Replace Image (optional)</label>
                <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
                <div style="margin:16px 0 24px;">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-family:var(--font-ui);font-size:11px;letter-spacing:1px;">
                        <input type="checkbox" name="is_featured" value="1" <?php echo $product['is_featured'] ? 'checked' : ''; ?>>
                        Mark as Featured Product
                    </label>
                </div>
                <button type="submit" class="btn-admin btn-admin-gold" style="padding:13px 36px;font-size:11px;letter-spacing:2px;">Update Product</button>
            </form>
        </div>
    </main>
</div>
</body>
</html>
