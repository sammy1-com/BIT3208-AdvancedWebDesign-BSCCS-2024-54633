<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('/pitstop/admin/products.php');

$result = $conn->query("SELECT image_url FROM products WHERE id = $id");
$product = $result->fetch_assoc();

if ($product) {
    if ($product['image_url'] && strpos($product['image_url'], 'uploads/parts/') === 0 && file_exists('../' . $product['image_url'])) {
        unlink('../' . $product['image_url']);
    }
    $conn->query("DELETE FROM products WHERE id = $id");
}

redirect('/pitstop/admin/products.php');
