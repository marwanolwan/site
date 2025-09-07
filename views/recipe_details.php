<?php// filepath: C:/wamp64/www/site/views/recipe_details.php?>
<?php require_once 'layouts/header.php'; ?>

<!-- 1. البانر الموحد - سيستخدم ألوان قسم الوصفات من لوحة التحكم -->
<div class="products-page-banner" style="background: linear-gradient(135deg, <?php echo htmlspecialchars($settings['recipe_banner_color1'] ?? '#27ae60'); ?>, <?php echo htmlspecialchars($settings['recipe_banner_color2'] ?? '#2ecc71'); ?>);">
    <div class="container banner-grid">
        <div class="banner-text">
            <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
            <p><?php echo htmlspecialchars($recipe['category_name']); ?></p>
        </div>
        <div class="banner-image">
            </div>
        </div>
    </div>
</div>

<!-- 2. محتوى تفاصيل الوصفة -->
<div class="recipe-details-content-v2 container">
    <div class="details-card-v2">
        <div class="recipe-main-image">
            <img src="<?php echo BASE_URL . 'assets/images/recipes/' . htmlspecialchars($recipe['image_url']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
        </div>

        <div class="recipe-meta-v2">
            <?php if (!empty($recipe['prep_time'])): ?>
            <div class="meta-item-v2"><i class="fas fa-clock"></i><div><span>الوقت</span><strong><?php echo htmlspecialchars($recipe['prep_time']); ?></strong></div></div>
            <?php endif; ?>
            <?php if (!empty($recipe['servings'])): ?>
            <div class="meta-item-v2"><i class="fas fa-users"></i><div><span>تكفي لـ</span><strong><?php echo htmlspecialchars($recipe['servings']); ?></strong></div></div>
            <?php endif; ?>
            <?php if (!empty($recipe['difficulty'])): ?>
            <div class="meta-item-v2"><i class="fas fa-signal"></i><div><?php echo htmlspecialchars($translations['difficulty'] ?? 'Difficulty'); ?><strong><?php echo htmlspecialchars($recipe['difficulty']); ?></strong></div></div>
            <?php endif; ?>
        </div>

        <div class="recipe-body-grid">
            <div class="ingredients-column">
                <h3 class="section-title-details"><?php echo htmlspecialchars($translations['ingredients'] ?? 'ingredients'); ?></h3>
                <ul class="ingredients-list-v2">
                    <?php $ingredients = preg_split('/\\r\\n|\\r|\\n/', $recipe['ingredients']); foreach ($ingredients as $ingredient): if(trim($ingredient)): ?>
                    <li><?php echo htmlspecialchars(trim($ingredient)); ?></li>
                    <?php endif; endforeach; ?>
                </ul>
            
                <?php if (!empty($recipe['additional_images'])): ?>
<h3 class="section-titl1e-details" style="margin-top: 30px;">معرض الصور</h3>
<div class="recipe-gallery-v2">
    <?php foreach($recipe['additional_images'] as $image): ?>
    <a href="<?php echo BASE_URL . 'assets/images/recipes/gallery/' . $image['image_url']; ?>" data-fancybox="recipe-gallery" class="gallery-item">
        <img src="<?php echo BASE_URL . 'assets/images/recipes/gallery/' . $image['image_url']; ?>" alt="صورة من الوصفة" />
    </a>
    <?php endforeach; ?>
</div>

                <?php endif; ?>
            </div>

            <div class="instructions-column">
                <h3 class="section-title-details"><?php echo htmlspecialchars($translations['instructions'] ?? 'instructions'); ?></h3>
                <ol class="instructions-list-v2">
                    <?php $instructions = preg_split('/\\r\\n|\\r|\\n/', $recipe['instructions']); foreach ($instructions as $index => $step): if(trim($step)): ?>
                    <li>
                        <span class="step-content"><?php echo htmlspecialchars(trim($step)); ?></span>
                    </li>
                    <?php endif; endforeach; ?>
                </ol>
            </div>
        </div>
    </div>

    <div class="back-link-container">
        <a href="<?php echo BASE_URL; ?>recipes" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            <?php echo htmlspecialchars($translations['btn_back_to_recipes'] ?? 'Back to Recipes'); ?>
        </a>
    </div>
</div>
<div class="lightbox-overlay" style="display:none;">
    <div class="lightbox-content">
        <img src="" alt="عرض الصورة">
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
<style>
 /* ======================= Recipe Details Page (NEW STYLE V2) ======================= */
 .section-titl1e-details {
    text-align: center; /* العنوان في المنتصف */
    margin-top: 50px;
    margin-bottom: 20px;
    font-size: 1.8rem;
    color: #333;
}
.recipe-details-content-v2 {
    margin-top: -80px;
    padding-bottom: 40px;
    position: relative;
    z-index: 10;
}
.recipe-main-image {
    width: 100%;
    height: 450px;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.recipe-main-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.details-card-v2:hover .recipe-main-image img {
    transform: scale(1.05);
}
.recipe-meta-v2 {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 40px;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    flex-wrap: wrap;
}
.meta-item-v2 {
    display: flex;
    align-items: center;
    gap: 15px;
    background: #fff;
    padding: 10px 20px;
    border-radius: 50px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}
.meta-item-v2 i {
    font-size: 1.5rem;
    color: var(--primary-orange);
}
.meta-item-v2 div {
    display: flex;
    flex-direction: column;
}
.meta-item-v2 span {
    font-size: 0.85rem;
    color: #6c757d;
}
.meta-item-v2 strong {
    font-size: 1.1rem;
    font-weight: 700;
}

.recipe-body-grid {
    display: grid;
    grid-template-columns: 40% 1fr;
    gap: 40px;
}
.ingredients-list-v2 {
    list-style-type: none;
    padding: 0;
}
.ingredients-list-v2 li {
    padding: 12px 15px;
    background-color: #f8f9fa;
    margin-bottom: 8px;
    border-radius: 8px;
    border-right: 3px solid var(--brand-light-green);
    font-weight: 500;
}
html[dir="ltr"] .ingredients-list-v2 li {
    border-right: none;
    border-left: 3px solid var(--brand-light-green);
}

.instructions-list-v2 {
    list-style-type: none;
    counter-reset: instructions-counter;
    padding: 0;
}
.instructions-list-v2 li {
    counter-increment: instructions-counter;
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 25px;
    line-height: 1.8;
}
.instructions-list-v2 li::before {
    content: counter(instructions-counter);
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    background-color: var(--dark-blue);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.recipe-gallery-v2 {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center; /* يجعل الصور في المنتصف */
}

.recipe-gallery-v2 img {
    width: 100%;
    height: 140px;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}
.recipe-gallery-v2 a:hover img {
    transform: scale(1.08);
}
.recipe-gallery-v2 a {
    display: block;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
@media (max-width: 992px) {
    .recipe-body-grid { grid-template-columns: 1fr; }
    .ingredients-column { order: 2; }
    .instructions-column { order: 1; }
    .recipe-main-image { height: 350px; }
}
.recipe-gallery-v2 a:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
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
.lightbox-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.lightbox-content img {
    max-width: 90%;
    max-height: 90%;
    border-radius: 10px;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const images = document.querySelectorAll(".recipe-gallery-v2 img");
    const lightbox = document.querySelector(".lightbox-overlay");
    const lightboxImg = document.querySelector(".lightbox-content img");

    images.forEach(img => {
        img.addEventListener("click", (e) => {
            e.preventDefault();
            lightboxImg.src = img.src;
            lightbox.style.display = "flex";
        });
    });

    // إغلاق عند النقر خارج الصورة
    lightbox.addEventListener("click", (e) => {
        if (!e.target.closest(".lightbox-content")) {
            lightbox.style.display = "none";
        }
    });

    // إغلاق عند الضغط على ESC
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") lightbox.style.display = "none";
    });
});
</script>