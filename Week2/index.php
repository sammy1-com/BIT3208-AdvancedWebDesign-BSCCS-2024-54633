<?php
// WEEK 2 - Static Frontend (No database yet)
$page_title = 'Home';
require_once 'includes/header.php';

// Hardcoded categories for UI design purposes
$categories = [
    ['name' => 'Engine Parts',    'slug' => 'engine',    'image_url' => 'assets/images/cat-engine.jpg'],
    ['name' => 'Brakes',          'slug' => 'brakes',    'image_url' => 'assets/images/cat-brakes.jpg'],
    ['name' => 'Suspension',      'slug' => 'suspension','image_url' => 'assets/images/cat-suspension.jpg'],
    ['name' => 'Electrical',      'slug' => 'electrical','image_url' => 'assets/images/cat-electrical.jpg'],
    ['name' => 'Body Parts',      'slug' => 'body',      'image_url' => 'assets/images/cat-body.jpg'],
    ['name' => 'Filters & Fluids','slug' => 'filters',   'image_url' => 'assets/images/cat-filters.jpg'],
];

// Hardcoded featured products for UI design
$featured = [
    ['name'=>'Toyota Alternator','brand'=>'Denso','price'=>4500,'part_number'=>'27060-0P010','slug'=>'toyota-alternator','image_url'=>'assets/images/product1.jpg','condition_type'=>'new'],
    ['name'=>'Brake Pads Set',  'brand'=>'Brembo','price'=>2800,'part_number'=>'BP-TS2210',  'slug'=>'brake-pads-set', 'image_url'=>'assets/images/product2.jpg','condition_type'=>'oem'],
    ['name'=>'Air Filter',      'brand'=>'K&N',   'price'=>1200,'part_number'=>'33-2842',     'slug'=>'air-filter',     'image_url'=>'assets/images/product3.jpg','condition_type'=>'new'],
    ['name'=>'Shock Absorber',  'brand'=>'KYB',   'price'=>3600,'part_number'=>'344390',      'slug'=>'shock-absorber', 'image_url'=>'assets/images/product4.jpg','condition_type'=>'aftermarket'],
];
?>

<div class="hero">
    <div class="hero-slide" style="background-image:url('assets/images/hero1.jpg')"></div>
    <div class="hero-slide" style="background-image:url('assets/images/hero2.jpg')"></div>
    <div class="hero-slide" style="background-image:url('assets/images/hero3.jpg')"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-tag">Kenya's Premier Auto Parts Store</div>
        <h1 class="hero-title">Right <em>Part.</em><br>Right Fit.</h1>
        <p class="hero-subtitle">Genuine new and premium aftermarket parts for all vehicle makes. Delivered across Kenya in 48 hours.</p>
        <div class="hero-cta">
            <a href="shop.php" class="btn-gold">Shop Now</a>
            <a href="#search" class="btn-outline-light">Find My Parts</a>
        </div>
    </div>
    <div class="hero-stats">
        <div class="stat"><div class="stat-num">12<span>K+</span></div><div class="stat-label">Parts Listed</div></div>
        <div class="stat"><div class="stat-num">4<span>.8</span></div><div class="stat-label">Avg Rating</div></div>
        <div class="stat"><div class="stat-num">48<span>HR</span></div><div class="stat-label">Delivery</div></div>
    </div>
</div>

<section class="categories-section">
    <div class="container-xl">
        <div class="section-header">
            <h2 class="section-title">Shop by Category</h2>
            <a href="shop.php" class="section-link">View All</a>
        </div>
        <div class="cat-grid">
            <?php foreach ($categories as $cat): ?>
            <a href="shop.php?category=<?php echo $cat['slug']; ?>" class="cat-card">
                <div class="cat-img" style="background-image:url('<?php echo $cat['image_url']; ?>')"></div>
                <div class="cat-body">
                    <div class="cat-name"><?php echo $cat['name']; ?></div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="products-section">
    <div class="container-xl">
        <div class="section-header">
            <h2 class="section-title">Featured Parts</h2>
            <a href="shop.php" class="section-link">See All Products</a>
        </div>
        <div class="products-grid">
            <?php foreach ($featured as $p): ?>
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
</section>

<?php require_once 'includes/footer.php'; ?>
