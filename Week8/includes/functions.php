<?php
function redirect($url) {
    header('Location: ' . $url);
    exit;
}
function sanitize($conn, $value) {
    return $conn->real_escape_string(htmlspecialchars(trim($value)));
}
function is_logged_in() {
    return isset($_SESSION['user_id']);
}
function get_role() {
    return $_SESSION['role'] ?? null;
}
function is_admin() {
    return get_role() === 'admin';
}
function is_manager_or_above() {
    return in_array(get_role(), ['admin', 'manager'], true);
}
function require_login() {
    if (!is_logged_in()) {
        redirect('/login.php');
    }
}
function require_admin() {
    if (!is_admin()) {
        redirect('/index.php');
    }
}
function require_manager_or_above() {
    if (!is_manager_or_above()) {
        redirect('/index.php');
    }
}
function get_cart_count() {
    if (!isset($_SESSION['cart'])) return 0;
    return array_sum($_SESSION['cart']);
}
function get_cart_total($conn) {
    if (empty($_SESSION['cart'])) return 0;
    $total = 0;
    foreach ($_SESSION['cart'] as $product_id => $qty) {
        $id = (int)$product_id;
        $result = $conn->query("SELECT price FROM products WHERE id = $id");
        if ($row = $result->fetch_assoc()) {
            $total += $row['price'] * $qty;
        }
    }
    return $total;
}
function format_price($amount) {
    return 'KES ' . number_format($amount, 2);
}
function get_featured_products($conn, $limit = 6) {
    $result = $conn->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_featured = 1 AND p.is_active = 1 LIMIT $limit");
    return $result->fetch_all(MYSQLI_ASSOC);
}
function get_all_products($conn, $where = '', $limit = 12, $offset = 0) {
    $sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1";
    if ($where) $sql .= " AND $where";
    $sql .= " ORDER BY p.created_at DESC LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
function get_product_by_slug($conn, $slug) {
    $slug = $conn->real_escape_string($slug);
    $result = $conn->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.slug = '$slug' AND p.is_active = 1");
    return $result->fetch_assoc();
}
function get_categories($conn) {
    $result = $conn->query("SELECT * FROM categories ORDER BY name ASC");
    return $result->fetch_all(MYSQLI_ASSOC);
}
function get_makes($conn) {
    $result = $conn->query("SELECT * FROM makes ORDER BY name ASC");
    return $result->fetch_all(MYSQLI_ASSOC);
}
function get_models_by_make($conn, $make_id) {
    $id = (int)$make_id;
    $result = $conn->query("SELECT * FROM models WHERE make_id = $id ORDER BY name ASC");
    return $result->fetch_all(MYSQLI_ASSOC);
}
