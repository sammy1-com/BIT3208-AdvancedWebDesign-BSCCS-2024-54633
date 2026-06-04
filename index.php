<?php
$page_title = 'Home';
require_once 'includes/header.php';
$categories = get_categories($conn);
$featured = get_featured_products($conn, 8);
$makes = get_makes($conn);
?>

<div class="hero">
    <div class="hero-slide" style="background-image:url('assets/images/hero1.jpg')"></div>
    <div class="hero-slide" style="background-image:url('assets/images/hero2.jpg')"></div>
    <div class="hero-slide" style="background-image:url('assets/images/hero3.jpg')"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-tag">Kenya's Premier Auto Parts Store</div>
        <h1 class="hero-title">Right <em>Part.</em><br>Right Fit.<br> </h1>
        <p class="hero-subtitle">Genuine new and premium aftermarket parts for all vehicle makes. Delivered across Kenya in 48 hours.</p>
        <div class="hero-cta">
            <a href="shop.php" class="btn-gold">Shop Now</a>
            <a href="#search" class="btn-outline-light">Find My Parts</a>
        </div>
    </div>
    <div class="hero-stats">
        <div class="stat">
            <div class="stat-num">12<span>K+</span></div>
            <div class="stat-label">Parts Listed</div>
        </div>
        <div class="stat">
            <div class="stat-num">4<span>.8</span></div>
            <div class="stat-label">Avg Rating</div>
        </div>
        <div class="stat">
            <div class="stat-num">48<span>HR</span></div>
            <div class="stat-label">Delivery</div>
        </div>
    </div>
    <div class="hero-dots">
        <button class="hero-dot" data-index="0"></button>
        <button class="hero-dot" data-index="1"></button>
        <button class="hero-dot" data-index="2"></button>
    </div>
</div>

<div id="search" class="search-section">
    <div class="search-inner">
        <div class="search-tabs">
            <button class="search-tab active" data-target="vehicle">By Vehicle</button>
            <button class="search-tab" data-target="part">By Part</button>
        </div>
        <div class="search-divider"></div>
        <form id="vehicle-search-form" class="search-form" action="shop.php" method="GET">
            <select name="make" id="make-select" class="search-select">
                <option value="">Make</option>
                <?php foreach ($makes as $make): ?>
                <option value="<?php echo $make['id']; ?>"><?php echo htmlspecialchars($make['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="model" id="model-select" class="search-select">
                <option value="">Model</option>
            </select>
            <select name="year" class="search-select">
                <option value="">Year</option>
                <?php for ($y = date('Y'); $y >= 2000; $y--): ?>
                <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="search-submit">Find Parts</button>
        </form>
        <form id="part-search-form" class="search-form" action="shop.php" method="GET" style="display:none">
            <input type="text" name="q" class="search-input" placeholder="Search by part name or part number">
            <button type="submit" class="search-submit">Search</button>
        </form>
    </div>
</div>

<section class="categories-section">
    <div class="container-xl">
        <div class="section-header reveal">
            <h2 class="section-title">Shop by Category</h2>
            <a href="shop.php" class="section-link">View All</a>
        </div>
        <div class="cat-grid">
            <?php foreach ($categories as $cat): ?>
            <a href="shop.php?category=<?php echo urlencode($cat['slug']); ?>" class="cat-card reveal">
                <div class="cat-img" style="background-image:url('<?php echo htmlspecialchars($cat['image_url']); ?>')"></div>
                <div class="cat-body">
                    <div class="cat-name"><?php echo htmlspecialchars($cat['name']); ?></div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="products-section">
    <div class="container-xl">
        <div class="section-header reveal">
            <h2 class="section-title">Featured Parts</h2>
            <a href="shop.php" class="section-link">See All Products</a>
        </div>
        <div class="products-grid">
            <?php foreach ($featured as $product): ?>
            <a href="product.php?slug=<?php echo urlencode($product['slug']); ?>" class="product-card">
                <div class="product-img">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="product-badges">
                        <span class="badge-condition badge-<?php echo $product['condition_type']; ?>">
                            <?php echo ucfirst($product['condition_type']); ?>
                        </span>
                    </div>
                </div>
                <div class="product-body">
                    <div class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></div>
                    <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                    <div class="product-part-no"><?php echo htmlspecialchars($product['part_number']); ?></div>
                    <div class="product-footer">
                        <div class="product-price"><?php echo format_price($product['price']); ?></div>
                        <button class="btn-add-cart">Add to Cart</button>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="stock-strip">
    <div class="container-xl">
        <div class="section-header reveal" style="border-bottom:1px solid rgba(255,255,255,0.06); padding-bottom:40px; margin-bottom:40px;">
            <h2 class="section-title" style="color:var(--white);">Our Stock</h2>
            <a href="shop.php" class="section-link">Browse Catalogue</a>
        </div>
        <div class="stock-grid">
            <div class="stock-item reveal">
                <div class="stock-img" style="background-image:url('assets/images/engines-wall.jpg')"></div>
                <div class="stock-label">Engine Stock</div>
                <div class="stock-desc">Hundreds of reconditioned and tested engines for Japanese and European vehicles.</div>
            </div>
            <div class="stock-item reveal">
                <div class="stock-img" style="background-image:url('assets/images/headlights.jpg')"></div>
                <div class="stock-label">Electrical Parts</div>
                <div class="stock-desc">LED headlights, alternators, starter motors and full electrical components in stock.</div>
            </div>
            <div class="stock-item reveal">
                <div class="stock-img" style="background-image:url('assets/images/bumper-kits.jpg')"></div>
                <div class="stock-label">Body Parts</div>
                <div class="stock-desc">Front and rear bumpers, grilles, bull bars and full body kits for all popular makes.</div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
