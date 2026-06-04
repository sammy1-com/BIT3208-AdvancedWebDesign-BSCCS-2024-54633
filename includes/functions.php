<?php
// WEEK 3 - Helper Functions: Basic PHP functions practice

/**
 * Redirect to a URL
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Format a price in Kenyan Shillings
 */
function format_price($amount) {
    return 'KSh ' . number_format($amount, 2);
}

/**
 * Sanitize user input
 */
function sanitize($input) {
    return htmlspecialchars(trim($input));
}

/**
 * Validate email format
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// WEEK 3: Categories returned as hardcoded array (DB integration comes Week 4)
function get_categories($conn) {
    return [
        ['name' => 'Engine Parts',     'slug' => 'engine',    'image_url' => 'assets/images/cat-engine.jpg'],
        ['name' => 'Brakes',           'slug' => 'brakes',    'image_url' => 'assets/images/cat-brakes.jpg'],
        ['name' => 'Suspension',       'slug' => 'suspension','image_url' => 'assets/images/cat-suspension.jpg'],
        ['name' => 'Electrical',       'slug' => 'electrical','image_url' => 'assets/images/cat-electrical.jpg'],
        ['name' => 'Body Parts',       'slug' => 'body',      'image_url' => 'assets/images/cat-body.jpg'],
        ['name' => 'Filters & Fluids', 'slug' => 'filters',   'image_url' => 'assets/images/cat-filters.jpg'],
    ];
}

// WEEK 3: Featured products hardcoded (DB integration comes Week 4)
function get_featured_products($conn, $limit = 4) {
    $products = [
        ['id'=>1,'name'=>'Toyota Alternator','brand'=>'Denso', 'price'=>4500,'part_number'=>'27060-0P010','slug'=>'toyota-alternator','image_url'=>'assets/images/product1.jpg','condition_type'=>'new'],
        ['id'=>2,'name'=>'Brake Pads Set',   'brand'=>'Brembo','price'=>2800,'part_number'=>'BP-TS2210',  'slug'=>'brake-pads-set', 'image_url'=>'assets/images/product2.jpg','condition_type'=>'oem'],
        ['id'=>3,'name'=>'Air Filter',       'brand'=>'K&N',   'price'=>1200,'part_number'=>'33-2842',     'slug'=>'air-filter',     'image_url'=>'assets/images/product3.jpg','condition_type'=>'new'],
        ['id'=>4,'name'=>'Shock Absorber',   'brand'=>'KYB',   'price'=>3600,'part_number'=>'344390',      'slug'=>'shock-absorber', 'image_url'=>'assets/images/product4.jpg','condition_type'=>'aftermarket'],
    ];
    return array_slice($products, 0, $limit);
}

// WEEK 3: Makes for vehicle search
function get_makes($conn) {
    return [
        ['id'=>1, 'name'=>'Toyota'],
        ['id'=>2, 'name'=>'Nissan'],
        ['id'=>3, 'name'=>'Subaru'],
        ['id'=>4, 'name'=>'Mazda'],
        ['id'=>5, 'name'=>'Honda'],
        ['id'=>6, 'name'=>'Mitsubishi'],
    ];
}
?>
