<?php// filepath: C:/wamp64/www/site/views/products.php?>
<?php require_once 'layouts/header.php'; ?>

<!-- 1. البانر المصحح (REBUILT) -->
<div class="products-page-banner" style="background: linear-gradient(135deg, <?php echo htmlspecialchars($settings['products_banner_color1'] ?? '#2c3e50'); ?>, <?php echo htmlspecialchars($settings['products_banner_color2'] ?? '#3498db'); ?>);">
    <div class="container banner-grid">
        <div class="banner-text">
            <h1><?php echo htmlspecialchars($translations['products_banner_title'] ?? 'Our Products'); ?></h1>
            <p><?php echo htmlspecialchars($translations['products_banner_subtitle'] ?? ''); ?></p>
        </div>
        <div class="banner-image">
            <div class="banner-image-circle">
                <img src="<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($settings['products_banner_image'] ?? ''); ?>" alt="Products Basket">
            </div>
        </div>
    </div>
</div>

<!-- 2. قسم المحتوى الرئيسي -->
<div class="container products-main-content">
    
    <div class="products-header">
        <h2><?php echo htmlspecialchars($translations['products_page_title'] ?? 'All Products'); ?></h2>
        <span class="total-count"><?php echo htmlspecialchars($translations['total_products'] ?? 'Total'); ?>: <?php echo $total_products; ?></span>
    </div>

    <!-- 3. شريط البحث والفلترة -->
    <div class="search-filter-bar">
        <form action="<?php echo BASE_URL; ?>products" method="GET">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="<?php echo htmlspecialchars($translations['search_placeholder'] ?? 'Search...'); ?>" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <button type="submit" class="filter-btn apply-btn"><i class="fas fa-check"></i> <?php echo htmlspecialchars($translations['filter_sort_btn'] ?? 'Filter & Sort'); ?></button>
            <a href="<?php echo BASE_URL; ?>products" class="filter-btn reset-btn"><i class="fas fa-sync-alt"></i></a>
        </form>
    </div>

    <!-- 4. عرض المنتجات -->
    <?php if (empty($grouped_products)): ?>
        <p>No products found.</p>
    <?php else: ?>
        <?php foreach($grouped_products as $category_name => $products_in_category): ?>
            <section class="product-category-group">
                <h3><?php echo htmlspecialchars($category_name); ?></h3>
                <div class="products-grid">
                    <?php foreach($products_in_category as $product): ?>
                        <div class="product-card">
                            <div class="product-card-image">
                                <a href="<?php echo BASE_URL; ?>product?id=<?php echo $product['id']; ?>">
                                    <img src="<?php echo BASE_URL . 'assets/images/products/' . htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </a>
                            </div>
                            <div class="product-card-body">
                                <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                                <a href="<?php echo BASE_URL; ?>product?id=<?php echo $product['id']; ?>" class="btn-details">
                                    <?php echo htmlspecialchars($translations['btn_view_details'] ?? 'View Details'); ?> →
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
                        
    <?php endif; ?>

    
    <!-- 4. Back Button -->
    <div class="back-home-btn-container">
        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary"><?php echo htmlspecialchars($translations['btn_back_to_home']); ?></a>
    </div>

<?php require_once 'layouts/footer.php'; ?>
<style>

* { margin: 0; padding: 0; box-sizing: border-box; }
.main-nav a { color: #f9f9f9; text-decoration: none; font-weight: 500; padding-bottom: 8px; position: relative; transition: all 0.3s ease; }
.main-nav a:hover { color: var(--secondary-yellow); }
.main-nav a:hover, .main-nav a.active { color: #ffc107; transform: translateY(-2px); }
.btn-primary { background-color: var(--primary-orange); color: #fff; }
.btn-primary:hover { background-color: #09132a; transform: translateY(-2px); }
.reset-btn { background: #104175ff; color: #fff; border: none; border-radius: 5px; padding: 8px 12px; cursor: pointer; transition: background-color 0.3s ease; }
.reset-btn:hover { background: #09132a; }

</style>