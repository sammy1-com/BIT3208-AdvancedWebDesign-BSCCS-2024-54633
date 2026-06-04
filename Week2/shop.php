<?php
// WEEK 2 - Static Shop Page (No database yet)
$page_title = 'Shop';
require_once 'includes/header.php';

// Hardcoded products for GUI design purposes
$products = [
    ['name'=>'Toyota Alternator','brand'=>'Denso','price'=>4500,'part_number'=>'27060-0P010','image_url'=>'assets/images/product1.jpg','condition_type'=>'new'],
    ['name'=>'Brake Pads Set',  'brand'=>'Brembo','price'=>2800,'part_number'=>'BP-TS2210',  'image_url'=>'assets/images/product2.jpg','condition_type'=>'oem'],
    ['name'=>'Air Filter',      'brand'=>'K&N',   'price'=>1200,'part_number'=>'33-2842',     'image_url'=>'assets/images/product3.jpg','condition_type'=>'new'],
    ['name'=>'Shock Absorber',  'brand'=>'KYB',   'price'=>3600,'part_number'=>'344390',      'image_url'=>'assets/images/product4.jpg','condition_type'=>'aftermarket'],
    ['name'=>'Radiator',        'brand'=>'Nissens','price'=>7800,'part_number'=>'638862',      'image_url'=>'assets/images/product5.jpg','condition_type'=>'new'],
    ['name'=>'Starter Motor',   'brand'=>'Bosch', 'price'=>5200,'part_number'=>'0001107436',  'image_url'=>'assets/images/product6.jpg','condition_type'=>'refurbished'],
];
?>

<div class="shop-header">
    <h1>The Catalogue</h1>
    <p>Browse our full range of genuine and aftermarket parts</p>
</div>

<div class="shop-body">
    <!-- WEEK 2: Static filter bar - no functionality yet -->
    <div class="shop-filters">
        <div class="filter-row">
            <input type="text" class="filter-input" placeholder="Search part name or number">
            <select class="filter-select">
                <option value="">All Categories</option>
                <option>Engine Parts</option>
                <option>Brakes</option>
                <option>Suspension</option>
                <option>Electrical</option>
            </select>
            <select class="filter-select">
                <option value="">All Conditions</option>
                <option>New</option>
                <option>OEM</option>
                <option>Aftermarket</option>
                <option>Refurbished</option>
            </select>
            <button type="button" class="filter-btn">Filter</button>
        </div>
    </div>

    <div class="results-info"><?php echo count($products); ?> PRODUCTS FOUND</div>

    <div class="products-grid">
        <?php foreach ($products as $p): ?>
        <div class="product-card">
            <div class="product-img">
                <img src="<?php echo $p['image_url']; ?>" alt="<?php echo $p['name']; ?>">
                <div class="product-badges">
                    <span class="badge-condition badge-<?php echo $p['condition_type']; ?>"><?php echo ucfirst($p['condition_type']); ?></span>
                </div>
            </div>
            <div class="product-body">
                <div class="product-brand"><?php echo $p['brand']; ?></div>
                <div class="product-name"><?php echo $p['name']; ?></div>
                <div class="product-part-no"><?php echo $p['part_number']; ?></div>
                <div class="product-footer">
                    <div class="product-price">KSh <?php echo number_format($p['price']); ?></div>
                    <button class="btn-add-cart">Add to Cart</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
