<?php
// Router for PHP development server
// Serves static files from Week5/assets, then routes to Week5/index.php

$requested_file = __DIR__ . '/Week5' . $_SERVER['REQUEST_URI'];

// If it's a static file that exists, serve it
if (file_exists($requested_file) && is_file($requested_file)) {
    return false; // Let the server serve the file
}

// Otherwise, route to index.php
require __DIR__ . '/Week5/index.php';
?>

