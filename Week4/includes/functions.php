<?php
// WEEK 4 - Helper Functions: Now includes session-based authentication

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function format_price($amount) {
    return 'KSh ' . number_format($amount, 2);
}

function sanitize($input) {
    return htmlspecialchars(trim($input));
}

// AUTH FUNCTIONS (NEW IN WEEK 4)
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        redirect('/pitstop/login.php');
    }
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// DB QUERY FUNCTIONS (upgraded from Week 3 hardcoded arrays)
function get_categories($conn) {
    $result = $conn->query("SELECT * FROM categories ORDER BY name ASC");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function get_makes($conn) {
    $result = $conn->query("SELECT * FROM makes ORDER BY name ASC");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function get_featured_products($conn, $limit = 8) {
    $limit = (int)$limit;
    $result = $conn->query("SELECT p.*, c.name AS category_name
        FROM products p LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_active = 1 ORDER BY p.created_at DESC LIMIT $limit");
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function get_all_products($conn, $where = '', $limit = 12, $offset = 0) {
    $limit  = (int)$limit;
    $offset = (int)$offset;
    $sql = "SELECT p.*, c.name AS category_name
            FROM products p LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.is_active = 1" . ($where ? " AND $where" : "") . "
            ORDER BY p.created_at DESC LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function get_product_by_slug($conn, $slug) {
    $slug = $conn->real_escape_string($slug);
    $result = $conn->query("SELECT p.*, c.name AS category_name
        FROM products p LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.slug = '$slug' AND p.is_active = 1 LIMIT 1");
    return $result ? $result->fetch_assoc() : null;
}
?>
