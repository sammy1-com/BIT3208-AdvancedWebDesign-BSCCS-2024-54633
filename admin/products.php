<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_admin();

$products = $conn->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products — Admin</title>
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
            <h1>Products</h1>
            <a href="/admin/add-product.php" class="btn-admin btn-admin-gold">Add New Product</a>
        </div>
        <div class="admin-content">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Part No</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Condition</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <img src="/<?php echo htmlspecialchars($p['image_url']); ?>" alt="" style="width:56px;height:56px;object-fit:cover;border-radius:3px;">
                        </td>
                        <td style="font-family:var(--font-body);font-size:16px;max-width:200px;"><?php echo htmlspecialchars($p['name']); ?></td>
                        <td style="font-family:var(--font-ui);font-size:10px;letter-spacing:1px;"><?php echo htmlspecialchars($p['part_number']); ?></td>
                        <td><?php echo htmlspecialchars($p['category_name'] ?? '-'); ?></td>
                        <td><?php echo format_price($p['price']); ?></td>
                        <td><?php echo $p['stock']; ?></td>
                        <td><?php echo ucfirst($p['condition_type']); ?></td>
                        <td><?php echo $p['is_featured'] ? 'Yes' : 'No'; ?></td>
                        <td style="white-space:nowrap;">
                            <a href="/admin/edit-product.php?id=<?php echo $p['id']; ?>" class="btn-admin btn-admin-dark" style="margin-right:6px;">Edit</a>
                            <a href="/admin/delete-product.php?id=<?php echo $p['id']; ?>" class="btn-admin btn-admin-danger" onclick="return confirm('Delete this product?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
