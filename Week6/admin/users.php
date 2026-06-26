<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_admin();
$current_user_id = (int)$_SESSION['user_id'];
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['new_role'])) {
    $target_id = (int)$_POST['user_id'];
    $new_role = $_POST['new_role'];
    if (!in_array($new_role, ['customer', 'manager', 'admin'], true)) {
        $error = 'Invalid role.';
    } elseif ($target_id === $current_user_id) {
        $error = 'You cannot change your own role.';
    } else {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param('si', $new_role, $target_id);
        if ($stmt->execute()) {
            $success = 'Role updated successfully.';
        } else {
            $error = 'Failed to update role.';
        }
    }
}
$users = $conn->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users — Admin</title>
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
            <a href="/admin/products.php">Products</a>
            <a href="/admin/add-product.php">Add Product</a>
            <a href="/admin/orders.php">Orders</a>
            <a href="/admin/users.php" class="active">Manage Users</a>
            <a href="/index.php">View Store</a>
            <a href="/logout.php">Logout</a>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-topbar"><h1>Manage Users</h1></div>
        <div class="admin-content">
            <?php if ($error): ?><div class="alert-error" style="margin-bottom:24px;"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert-success" style="margin-bottom:24px;"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Update Role</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($u['name']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><span class="role-badge role-<?php echo $u['role']; ?>"><?php echo ucfirst($u['role']); ?></span></td>
                            <td><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
                            <td>
                                <?php if ((int)$u['id'] === $current_user_id): ?>
                                <span style="color:var(--taupe);font-size:12px;">This is you</span>
                                <?php else: ?>
                                <form method="POST" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                    <select name="new_role" class="filter-select" style="padding:4px 8px;font-size:12px;">
                                        <option value="customer" <?php echo $u['role']==='customer'?'selected':''; ?>>Customer</option>
                                        <option value="manager" <?php echo $u['role']==='manager'?'selected':''; ?>>Manager</option>
                                        <option value="admin" <?php echo $u['role']==='admin'?'selected':''; ?>>Admin</option>
                                    </select>
                                    <button type="submit" class="btn-admin btn-admin-gold" style="padding:4px 12px;font-size:11px;">Save</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>
