<?php// filepath: C:/wamp64/www/site/views/recipes.php?>
<?php require_once 'layouts/header.php'; ?>

<!-- 1. بانر صفحة الوصفات -->
<div class="recipes-page-banner">
    <div class="container banner-grid">
        <div class="banner-text">
            <h1><?php echo htmlspecialchars($translations['recipes_banner_title']); ?></h1>
            <p><?php echo htmlspecialchars($translations['recipes_banner_subtitle']); ?></p>
        </div>
        <div class="banner-image">
            <div class="banner-image-circle">
                <img src="<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($settings['recipes_banner_image']); ?>" alt="Recipes">
            </div>
        </div>
    </div>
</div>

<!-- 2. قسم المحتوى الرئيسي -->
<div class="container products-main-content">
    <div class="products-header">
        <h2><?php echo htmlspecialchars($translations['recipes_page_title']); ?></h2>
        <span class="total-count"><?php echo htmlspecialchars($translations['total_recipes']); ?>: <?php echo $total_recipes; ?></span>
    </div>
    <div class="search-filter-bar">
        <form action="<?php echo BASE_URL; ?>recipes" method="GET">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Search recipes..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <button type="button" class="filter-btn"><i class="fas fa-filter"></i> Filter & Sort</button>
            <button type="submit" class="filter-btn apply-btn"><i class="fas fa-check"></i></button>
            <a href="<?php echo BASE_URL; ?>recipes" class="filter-btn reset-btn"><i class="fas fa-sync-alt"></i></a>
        </form>
    </div>

    <?php if (empty($grouped_recipes)): ?>
        <p>No recipes found.</p>
    <?php else: ?>
        <?php foreach($grouped_recipes as $category_name => $recipes_in_category): ?>
            <section class="product-category-group">
                <h3><?php echo htmlspecialchars($category_name); ?></h3>
                <div class="products-grid">
                    <?php foreach($recipes_in_category as $recipe): ?>
                        <div class="product-card">
                            <div class="product-card-image">
                                <a href="<?php echo BASE_URL; ?>recipe?id=<?php echo $recipe['id']; ?>">
                                    <img src="<?php echo BASE_URL . 'assets/images/recipes/' . htmlspecialchars($recipe['image_url']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                                </a>
                            </div>
                            <div class="product-card-body">
                                <h4><?php echo htmlspecialchars($recipe['title']); ?></h4>
                                <a href="<?php echo BASE_URL; ?>recipe?id=<?php echo $recipe['id']; ?>" class="btn-details">
                                    <?php echo htmlspecialchars($translations['btn_view_details']); ?> →
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="back-home-btn-container">
        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary"><?php echo htmlspecialchars($translations['nav_home']); ?></a>
    </div>
</div>
<?php require_once 'layouts/footer.php'; ?>
<style>
    :root {
    --dark-blue: #0b602aff;
    --primary-orange: #007924ff;
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
.main-nav a:hover, .main-nav a.active { color: #0b4310ff; transform: translateY(-2px); }
.btn-primary { background-color: var(--primary-orange); color: #fff; }
.btn-primary:hover { background-color: #09132a; transform: translateY(-2px); }
</style>