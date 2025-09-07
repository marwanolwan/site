<?php// filepath: C:/wamp64/www/site/views/product_details.php?>
<?php require_once 'layouts/header.php'; ?>

<!-- 1. البانر المحسن (تمت إضافة الـ style هنا) -->
<div class="products-page-banner" style="background: linear-gradient(135deg, <?php echo htmlspecialchars($settings['products_banner_color1'] ?? '#2c3e50'); ?>, <?php echo htmlspecialchars($settings['products_banner_color2'] ?? '#3498db'); ?>);">
    <div class="banner-decoration"></div>
    <div class="banner-container">
        <div class="banner-text">
            <h1><?php echo htmlspecialchars(!empty($product['banner_title']) ? $product['banner_title'] : $product['name']); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($product['banner_tagline'] ?? '')); ?></p>
        </div>
        <div class="banner-image">
            <div class="banner-image-circle">
                <img src="<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($settings['products_banner_image']); ?>" alt="Products">
            </div>
        </div>
    </div>
</div>

<!-- 2. بطاقة تفاصيل المنتج الجديدة كليًا -->
<div class="product-details-content fade-in">
    <div class="details-card">
        <div class="details-main-info">
            <div class="main-info-image">
                <img src="<?php echo BASE_URL . 'assets/images/products/' . htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="main-info-text">
                <span class="details-category-badge"><?php echo htmlspecialchars($product['category_name']); ?></span>
                <h2 class="details-product-title"><?php echo htmlspecialchars($product['name']); ?></h2>
                <?php if (!empty($product['description'])): ?>
                    <p class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="details-grid">
            <div class="info-column">
                <h3 class="section-title"><?php echo htmlspecialchars($translations['details-product-title'] ?? 'details-product-title'); ?></h3>
                <?php if(!empty($product['weight_info'])): ?>
                    <div class="info-block"><h4><?php echo htmlspecialchars($translations['product-weight'] ?? 'product-weight'); ?></h4><p><?php echo nl2br(htmlspecialchars($product['weight_info'])); ?></p></div>
                <?php endif; ?>
                <?php if(!empty($product['ingredients'])): ?>
                    <div class="info-block"><h4><?php echo htmlspecialchars($translations['ingredients'] ?? 'ingredients'); ?></h4><p><?php echo nl2br(htmlspecialchars($product['ingredients'])); ?></p></div>
                <?php endif; ?>
                <?php if(!empty($product['storage'])): ?>
                    <div class="info-block"><h4><?php echo htmlspecialchars($translations['storage'] ?? 'storage'); ?></h4><p><?php echo nl2br(htmlspecialchars($product['storage'])); ?></p></div>
                <?php endif; ?>
            </div>
            <div class="nutrition-column">
                <h3 class="section-title"><?php echo htmlspecialchars($translations['nutrition-title'] ?? 'nutrition-title'); ?></h3>
                <?php if (!empty($product['nutritional_facts_decoded'])): ?>
                <table class="nutrition-table">
                    <thead>
                        <tr>
                            <th><?php echo htmlspecialchars($translations['Item_facts'] ?? 'Item_facts'); ?></th>
                            <th><?php echo htmlspecialchars($translations['Amount_per_serving'] ?? 'Amount_per_serving'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($product['nutritional_facts_decoded'] as $fact): ?>
                            <?php $fact_name = ($current_lang == 'ar' && !empty($fact['name_ar'])) ? $fact['name_ar'] : ($fact['name_en'] ?? ''); ?>
                            <?php if(!empty($fact_name)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fact_name); ?></td>
                                <td><?php echo htmlspecialchars($fact['value'] ?? '-'); ?></td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p>لا توجد بيانات حقائق غذائية لهذا المنتج.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="back-link-container">
        <a href="<?php echo BASE_URL; ?>products" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <?php echo htmlspecialchars($translations['btn_back_to_products'] ?? 'Back to Products'); ?>
        </a>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
<style>
    :root {
    --dark-blue: #09132a;
    --primary-orange: #104175ff;
    --secondary-yellow: #ffc107;
    --brand-cyan: #00bce4;
    --brand-light-green: #8bc34a;
    --brand-light-blue: #3498db;
    --text-color: #555;
    --heading-color: #333;
    --bg-light: #f9f9f9;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
.main-nav a { color: #f9f9f9; text-decoration: none; font-weight: 500; padding-bottom: 8px; position: relative; transition: all 0.3s ease; }
.main-nav a:hover { color: var(--secondary-yellow); }
.main-nav a:hover, .main-nav a.active { color: #ffc107; transform: translateY(-2px); }
.btn-primary { background-color: var(--primary-orange); color: #fff; }
.btn-primary:hover { background-color: #09132a; transform: translateY(-2px); }
</style>