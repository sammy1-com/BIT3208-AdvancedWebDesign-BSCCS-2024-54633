<?php
// WEEK 5 - Admin: Delete product (DELETE)
require_once '../includes/header.php';
if (!is_admin()) redirect('/pitstop/index.php');

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $conn->query("UPDATE products SET is_active = 0 WHERE id = $id");
}
redirect('/pitstop/admin/products.php');
