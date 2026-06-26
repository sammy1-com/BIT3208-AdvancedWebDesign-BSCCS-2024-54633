<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_manager_or_above();
$products_count = $conn->query("SELECT COUNT(*) AS c FROM products")->fetch_assoc()['c'];
$orders_count   = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'];
$users_count    = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role = 'customer'")->fetch_assoc()['c'];
$revenue        = $conn->query("SELECT SUM(total) AS r FROM orders WHERE status != 'cancelled'")->fetch_assoc()['r'] ?? 0;
$recent_orders  = $conn->query("SELECT o.*, u.name AS user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — PitStop Parts</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=EB+Garamond:ital,wght@0,400;0,500&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{background:#F4F1EA;color:#1a1a1a;font-family:'Montserrat',sans-serif;min-height:100vh;}
.ws-wrapper{display:flex;min-height:100vh;}
.ws-sidebar{width:220px;background:#1a1a1a;display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:100;}
.ws-sidebar-logo{font-family:'Cinzel',serif;font-size:18px;color:#F4F1EA;padding:28px 24px 24px;border-bottom:1px solid #2A2A2A;letter-spacing:2px;}
.ws-sidebar-logo span{color:#D4A853;}
.ws-sidebar-role{font-size:9px;letter-spacing:3px;text-transform:uppercase;color:#888;padding:12px 24px;border-bottom:1px solid #2A2A2A;}
.ws-nav{padding:16px 0;flex:1;}
.ws-nav a{display:block;padding:11px 24px;font-size:11px;letter-spacing:1.5px;text-transform:uppercase;color:#888;text-decoration:none;transition:color 0.2s,background 0.2s;}
.ws-nav a:hover,.ws-nav a.active{color:#D4A853;background:rgba(212,168,83,0.08);}
.ws-nav .nav-divider{height:1px;background:#2A2A2A;margin:8px 24px;}
.ws-sidebar-foot{padding:16px 24px;border-top:1px solid #2A2A2A;font-size:11px;color:#888;text-transform:uppercase;letter-spacing:1px;}
.ws-main{margin-left:220px;flex:1;padding:40px;}
.ws-topbar{display:flex;justify-content:space-between;align-items:baseline;padding-bottom:24px;border-bottom:2px solid #D4A853;margin-bottom:36px;}
.ws-topbar-tag{font-size:9px;letter-spacing:3px;text-transform:uppercase;color:#D4A853;margin-bottom:4px;}
.ws-topbar-title{font-family:'Cinzel',serif;font-size:26px;letter-spacing:3px;color:#1a1a1a;text-transform:uppercase;}
.ws-topbar-right{font-size:11px;letter-spacing:1px;color:#888;border:1px solid #ddd;padding:6px 14px;border-radius:3px;text-transform:uppercase;}
.ws-board{display:grid;grid-template-columns:1.5fr 1fr;gap:1px;background:#ddd;margin-bottom:1px;}
.ws-revenue{background:#fff;padding:32px 36px;}
.ws-revenue-label{font-size:9px;letter-spacing:3px;text-transform:uppercase;color:#888;margin-bottom:10px;}
.ws-revenue-amount{font-family:'Cinzel',serif;font-size:42px;color:#D4A853;letter-spacing:2px;line-height:1;margin-bottom:8px;}
.ws-revenue-sub{font-size:12px;color:#1a1a1a;text-transform:uppercase;letter-spacing:1px;}
.ws-ledger{background:#fff;padding:28px 32px;display:flex;flex-direction:column;justify-content:center;}
.ws-ledger-row{display:flex;justify-content:space-between;align-items:center;padding:13px 0;border-bottom:1px solid #eee;}
.ws-ledger-row:last-child{border-bottom:none;}
.ws-ledger-label{font-size:12px;color:#888;letter-spacing:0.5px;text-transform:uppercase;}
.ws-ledger-value{font-size:18px;color:#1a1a1a;font-family:'Cinzel',serif;}
.ws-toolbelt-label{font-size:9px;letter-spacing:3px;text-transform:uppercase;color:#888;margin:28px 0 12px;}
.ws-toolbelt{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:10px;margin-bottom:40px;}
.ws-tool{background:#fff;border:1px solid #ddd;border-radius:3px;padding:16px 20px;text-decoration:none;transition:border-color 0.2s,box-shadow 0.2s;}
.ws-tool:hover{border-color:#D4A853;box-shadow:0 2px 12px rgba(212,168,83,0.12);}
.ws-tool-name{font-size:11px;letter-spacing:1.5px;text-transform:uppercase;color:#D4A853;margin-bottom:4px;}
.ws-tool-desc{font-size:11px;color:#888;}
.ws-recent-label{font-family:'Cinzel',serif;font-size:14px;letter-spacing:3px;text-transform:uppercase;color:#1a1a1a;margin-bottom:16px;}
.ws-table{width:100%;border-collapse:collapse;font-size:12px;background:#fff;}
.ws-table th{font-size:9px;letter-spacing:2px;text-transform:uppercase;color:#888;padding:12px 16px;border-bottom:2px solid #eee;text-align:left;font-weight:500;}
.ws-table td{padding:14px 16px;border-bottom:1px solid #eee;color:#1a1a1a;vertical-align:middle;}
.ws-table tr:last-child td{border-bottom:none;}
.ws-table tr:hover td{background:#fafaf8;}
.ws-status{display:inline-block;padding:3px 10px;border-radius:3px;font-size:10px;letter-spacing:1px;text-transform:uppercase;}
.ws-status-pending{background:#FFF3CD;color:#856404;}
.ws-status-processing{background:#D1ECF1;color:#0c5460;}
.ws-status-shipped{background:#D4EDDA;color:#155724;}
.ws-status-delivered{background:#1a1a1a;color:#D4A853;}
.ws-status-cancelled{background:#F8D7DA;color:#721c24;}
.ws-view-link{font-size:10px;letter-spacing:1.5px;text-transform:uppercase;color:#D4A853;text-decoration:none;border:1px solid #ddd;padding:4px 10px;border-radius:3px;transition:border-color 0.2s;}
.ws-view-link:hover{border-color:#D4A853;}
@media(max-width:900px){.ws-sidebar{display:none;}.ws-main{margin-left:0;padding:24px 16px;}.ws-board{grid-template-columns:1fr;}.ws-revenue-amount{font-size:32px;}}
@media(max-width:600px){.ws-toolbelt{grid-template-columns:1fr 1fr;}}
</style>
</head>
<body>
<div class="ws-wrapper">
    <aside class="ws-sidebar">
        <div class="ws-sidebar-logo">Pit<span>Stop</span></div>
        <div class="ws-sidebar-role"><?php echo strtoupper(get_role()); ?> Panel</div>
        <nav class="ws-nav">
            <a href="/admin/index.php" class="active">Dashboard</a>
            <a href="/admin/products.php">Products</a>
            <a href="/admin/add-product.php">Add Product</a>
            <a href="/admin/orders.php">Orders</a>
            <?php if (is_admin()): ?>
            <div class="nav-divider"></div>
            <a href="/admin/users.php">Manage Users</a>
            <?php endif; ?>
            <div class="nav-divider"></div>
            <a href="/index.php">View Store</a>
            <a href="/logout.php">Logout</a>
        </nav>
        <div class="ws-sidebar-foot"><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></div>
    </aside>
    <main class="ws-main">
        <div class="ws-topbar">
            <div>
                <div class="ws-topbar-tag">PitStop Parts — Workshop Board</div>
                <div class="ws-topbar-title"><?php echo strtoupper(date('l, d F')); ?></div>
            </div>
            <div class="ws-topbar-right"><?php echo strtoupper(get_role()); ?></div>
        </div>
        <div class="ws-board">
            <div class="ws-revenue">
                <div class="ws-revenue-label">Revenue, All Time</div>
                <div class="ws-revenue-amount"><?php echo format_price($revenue); ?></div>
                <div class="ws-revenue-sub">From <?php echo $orders_count; ?> order<?php echo $orders_count != 1 ? 's' : ''; ?></div>
            </div>
            <div class="ws-ledger">
                <div class="ws-ledger-row"><span class="ws-ledger-label">Products Listed</span><span class="ws-ledger-value"><?php echo $products_count; ?></span></div>
                <div class="ws-ledger-row"><span class="ws-ledger-label">Orders Placed</span><span class="ws-ledger-value"><?php echo $orders_count; ?></span></div>
                <div class="ws-ledger-row"><span class="ws-ledger-label">Customers</span><span class="ws-ledger-value"><?php echo $users_count; ?></span></div>
            </div>
        </div>
        <div class="ws-toolbelt-label">Toolbelt</div>
        <div class="ws-toolbelt">
            <a href="/admin/add-product.php" class="ws-tool"><div class="ws-tool-name">+ Add Product</div><div class="ws-tool-desc">List a new part</div></a>
            <a href="/admin/products.php" class="ws-tool"><div class="ws-tool-name">Products</div><div class="ws-tool-desc">Manage catalogue</div></a>
            <a href="/admin/orders.php" class="ws-tool"><div class="ws-tool-name">Orders</div><div class="ws-tool-desc">View & update status</div></a>
            <?php if (is_admin()): ?>
            <a href="/admin/users.php" class="ws-tool"><div class="ws-tool-name">Users</div><div class="ws-tool-desc">Manage roles & accounts</div></a>
            <?php endif; ?>
        </div>
        <div class="ws-recent-label">Recent Orders</div>
        <table class="ws-table">
            <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($recent_orders as $order): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['user_name'] ?? 'Guest'); ?></td>
                    <td><?php echo format_price($order['total']); ?></td>
                    <td><span class="ws-status ws-status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                    <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                    <td><a href="/admin/orders.php?id=<?php echo $order['id']; ?>" class="ws-view-link">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>
