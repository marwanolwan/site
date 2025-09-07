<?php// filepath: C:/wamp64/www/site/views/about.php?>
<?php require_once 'layouts/header.php'; ?>

<!-- 1. About Page Banner -->
<div class="about-page-banner" style="background-color: <?php echo htmlspecialchars($settings['about_page_banner_color']); ?>;">
    <div class="container">
        <h1><?php echo htmlspecialchars($translations['about_page_banner_title']); ?></h1>
    </div>
</div>

<div class="container about-page-content">
    <!-- 2. Intro Section -->
    <section class="about-intro-section">
        <div class="intro-image">
            <div class="intro-image-circle" style="background-image: url('<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($settings['about_page_intro_image']); ?>');"></div>
        </div>
        <div class="intro-text">
            <h2><?php echo htmlspecialchars($translations['about_page_intro_title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($translations['about_page_intro_content'])); ?></p>
        </div>
    </section>

    <!-- 3. Tabs Section -->
    <?php if (!empty($tabs)): ?>
    <section class="about-tabs-section">
        <div class="tabs-container">
            <div class="tab-buttons">
                <?php foreach ($tabs as $index => $tab): ?>
                    <button class="tab-button <?php echo ($index === 0) ? 'active' : ''; ?>" data-tab="tab-<?php echo $tab['id']; ?>">
                        <?php echo htmlspecialchars($tab['title']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <div class="tab-contents">
                <?php foreach ($tabs as $index => $tab): ?>
                    <div id="tab-<?php echo $tab['id']; ?>" class="tab-content <?php echo ($index === 0) ? 'active' : ''; ?>">
                        <h3><?php echo htmlspecialchars($tab['title']); ?></h3>
                        <div class="tab-content-inner <?php echo !empty($tab['image_url']) ? 'has-image' : ''; ?>">
                            <div class="tab-text-content">
                                <?php echo nl2br(htmlspecialchars($tab['content'])); ?>
                            </div>
                            <?php if (!empty($tab['image_url'])): ?>
                                <div class="tab-image-content">
                                    <img src="<?php echo BASE_URL . 'assets/images/about/' . htmlspecialchars($tab['image_url']); ?>" alt="<?php echo htmlspecialchars($tab['title']); ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 4. Back Button -->
    <div class="back-home-btn-container">
        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary"><?php echo htmlspecialchars($translations['btn_back_to_home']); ?></a>
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