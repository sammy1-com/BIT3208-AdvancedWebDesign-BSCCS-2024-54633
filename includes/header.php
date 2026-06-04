<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? $page_title . ' — PitStop Parts' : 'PitStop Parts'; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=EB+Garamond:ital,wght@0,400;0,500&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/pitstop/assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <a href="/pitstop/index.php" class="nav-logo">Pit<span>Stop</span></a>
    <div class="nav-links">
        <a href="/pitstop/index.php">Home</a>
        <a href="/pitstop/shop.php">Shop</a>
        <?php if (is_logged_in()): ?>
            <a href="/pitstop/cart.php" class="nav-cart">
                Cart <?php $cc = array_sum($_SESSION['cart'] ?? []); if ($cc > 0): ?><span class="cart-badge"><?php echo $cc; ?></span><?php endif; ?>
            </a>
            <?php if (is_admin()): ?><a href="/pitstop/admin/index.php">Admin Panel</a><?php endif; ?>
            <span class="nav-username">Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></span>
            <a href="/pitstop/logout.php">Logout</a>
        <?php else: ?>
            <a href="/pitstop/cart.php" class="nav-cart">Cart</a>
            <a href="/pitstop/login.php">Login</a>
            <a href="/pitstop/register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>
