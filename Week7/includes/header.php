<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
$cart_count = get_cart_count();
$base = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? $page_title . ' — PitStop Parts' : 'PitStop Parts — Kenya\'s Premier Auto Parts Store'; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=EB+Garamond:ital,wght@0,400;0,500;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $base; ?>/assets/css/style.css">
</head>
<body>
<nav id="main-nav">
    <div class="nav-inner">
        <a href="<?php echo $base; ?>/index.php" class="nav-logo">Pit<span>Stop</span></a>
        <button class="nav-hamburger" id="nav-hamburger" aria-label="Toggle menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
        <ul class="nav-links" id="nav-links">
            <li><a href="<?php echo $base; ?>/index.php">Home</a></li>
            <li><a href="<?php echo $base; ?>/shop.php">Shop</a></li>
            <li><a href="<?php echo $base; ?>/shop.php?category=engine">Engine</a></li>
            <li><a href="<?php echo $base; ?>/shop.php?category=brakes">Brakes</a></li>
            <li><a href="<?php echo $base; ?>/shop.php?category=body-parts">Body Parts</a></li>
            <?php if (is_logged_in()): ?>
            <li><a href="<?php echo $base; ?>/my-orders.php">My Orders</a></li>
            <?php endif; ?>
        </ul>
        <div class="nav-actions" id="nav-actions">
            <a href="<?php echo $base; ?>/cart.php" class="nav-cart">
                Cart
                <?php if ($cart_count > 0): ?>
                <span class="cart-badge"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>
            <?php if (is_logged_in()): ?>
                <?php if (is_manager_or_above()): ?>
                <a href="<?php echo $base; ?>/admin/index.php" class="nav-btn"><?php echo is_admin() ? 'Admin' : 'Manager'; ?></a>
                <?php endif; ?>
                <span class="nav-username">Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></span>
                <a href="<?php echo $base; ?>/logout.php" class="nav-btn">Logout</a>
            <?php else: ?>
                <a href="<?php echo $base; ?>/login.php" class="nav-btn">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<script>
(function(){var btn=document.getElementById('nav-hamburger');var links=document.getElementById('nav-links');var actions=document.getElementById('nav-actions');if(!btn)return;btn.addEventListener('click',function(){var open=links.classList.toggle('nav-open');actions.classList.toggle('nav-open');btn.setAttribute('aria-expanded',open?'true':'false');});})();
</script>
