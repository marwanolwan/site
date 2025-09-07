<?php// filepath: C:/wamp64/www/site/views/layouts/home.php?>
<?php 
require_once 'layouts/header.php'; 
?>

<!-- ======================= Hero Slider Section (REBUILT) ======================= -->
<section class="hero-section">
    <div id="hero-swiper" class="swiper"
     data-speed="<?php echo htmlspecialchars($settings['slider_speed'] ?? '5000'); ?>"
     data-effect="<?php echo htmlspecialchars($settings['slider_effect'] ?? 'slide'); ?>"
     data-loop="<?php echo htmlspecialchars($settings['slider_loop'] ?? '1'); ?>"
     data-autoplay="<?php echo htmlspecialchars($settings['slider_autoplay'] ?? '1'); ?>">
        <div class="swiper-wrapper">
            <?php if (!empty($slides)): ?>
                <?php foreach ($slides as $slide): ?>
                    <div class="swiper-slide">
                        <div class="slide-background" style="background-image: url('<?php echo BASE_URL . 'assets/images/slider/' . htmlspecialchars($slide['image_url']); ?>');"></div>
                        <div class="slide-content-wrapper">
                            <div class="hero-content">
                                <div class="hero-text">
                                    <?php 
                                        $title = $current_lang == 'ar' ? $slide['title_ar'] : $slide['title_en'];
                                        $subtitle = $current_lang == 'ar' ? $slide['subtitle_ar'] : $slide['subtitle_en'];
                                    ?>
                                    <?php if (!empty($title)): ?><h2><?php echo htmlspecialchars($title); ?></h2><?php endif; ?>
                                    <?php if (!empty($subtitle)): ?><h3><?php echo htmlspecialchars($subtitle); ?></h3><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="swiper-slide"><div class="slide-background" style="background-image: url('https://i.imgur.com/kS5xJtG.jpeg');"></div></div>
            <?php endif; ?>
        </div>
        <div class="swiper-pagination"></div>
\    </div>
</section>

<!-- ======================= About Section ======================= -->
<section id="about" class="content-section">
    <div class="container grid-2">
        <div class="about-text">
            <h2><?php echo htmlspecialchars($translations['about_title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($translations['about_p1'])); ?></p>
            <p><?php echo nl2br(htmlspecialchars($translations['about_p2'])); ?></p>
            <a href="#" class="btn btn-primary"><?php echo htmlspecialchars($translations['btn_read_more']); ?></a>
        </div>
        <div class="about-image">
            <img src="https://i.imgur.com/5V3XG7l.png" alt="Al-Safi Logo">
        </div>
    </div>
</section>

<!-- ======================= Partners Section ======================= -->
<section id="partners" class="content-section">
    <div class="container">
        <h2 class="section-title"><?php echo htmlspecialchars($translations['partners_title']); ?></h2>
        <?php if (!empty($partners)): ?>
        <div class="swiper-container partners-slider">
            <div class="swiper-wrapper">
                <?php foreach($partners as $partner): ?>
                    <div class="swiper-slide">
                        <a href="<?php echo htmlspecialchars($partner['website_url']); ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo BASE_URL . 'assets/images/partners/' . htmlspecialchars($partner['logo_url']); ?>" alt="<?php echo htmlspecialchars($partner['name']); ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ======================= Our Products Section ======================= -->
<section id="products" class="clipped-section" style="
    clip-path: polygon(0 8vw, 100% 0, 100% 100%, 0% 100%);
    --border-color: <?php echo htmlspecialchars($settings['products_home_border'] ?? '#ffc107'); ?>;
    background: linear-gradient(135deg, <?php echo htmlspecialchars($settings['products_home_gradient1'] ?? '#2ed9ff'); ?>, <?php echo htmlspecialchars($settings['products_home_gradient2'] ?? '#00bce4'); ?>);
">
    <div class="container grid-2">
        <div class="section-image-wrapper">
            <div class="dynamic-image-circle" style="background-image: url('<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($settings['home_products_image'] ?? ''); ?>');">
            </div>
        </div>
        <div class="section-text" style="
            background: rgba(255,255,255,0.15);
            border-radius: 16px;
            padding: 32px 24px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            color: #fff;
            font-family: 'Cairo', sans-serif;
        ">
            <h2 style="
                font-size:2.2rem;
                font-weight:700;
                margin-bottom:18px;
                letter-spacing:1px;
                text-align:<?php echo $current_lang == 'ar' ? 'right' : 'left'; ?>;
                display:flex;
                flex-direction:<?php echo $current_lang == 'ar' ? 'row-reverse' : 'row'; ?>;
                align-items:center;
            ">
                <i class="fas fa-box-open" style="color:#ffc107; margin-<?php echo $current_lang == 'ar' ? 'left' : 'right'; ?>:10px;"></i>
                <?php echo htmlspecialchars($translations['products_title']); ?>
            </h2>
            <p style="font-size:1.15rem; line-height:2; margin-bottom:22px; text-align:justify;">
                <?php echo nl2br(htmlspecialchars($translations['products_p1'])); ?>
            </p>
            <a href="<?php echo BASE_URL; ?>products" class="btn btn-secondary" style="font-size:1rem; padding:12px 32px; border-radius:8px;">
                <?php echo htmlspecialchars($translations['btn_discover_me']); ?>
            </a>
        </div>
    </div>
</section>

<!-- ======================= Recipes Section ======================= -->
<section id="recipes" class="clipped-section" 
         style="
            clip-path: <?php echo htmlspecialchars($settings['home_recipes_clip_path'] ?? 'polygon(0 8vw, 100% 0, 100% 100%, 0% 100%)'); ?>;
            --border-color: <?php echo htmlspecialchars($settings['recipes_home_border'] ?? '#ffffff'); ?>;
            background: linear-gradient(135deg, <?php echo htmlspecialchars($settings['recipes_home_gradient1'] ?? '#98d85f'); ?>, <?php echo htmlspecialchars($settings['recipes_home_gradient2'] ?? '#8bc34a'); ?>);
">
    <div class="container grid-2">
        <div class="section-image-wrapper">
            <div class="dynamic-image-circle" style="background-image: url('<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($settings['home_recipes_image'] ?? ''); ?>');">
            </div>
        </div>
        <div class="section-text">
            <h2><?php echo htmlspecialchars($translations['recipes_title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($translations['recipes_p1'])); ?></p>
            <a href="<?php echo BASE_URL; ?>recipes" class="btn btn-secondary"><?php echo htmlspecialchars($translations['btn_discover_me']); ?></a>
        </div>
    </div>
</section>

<!-- ======================= Find Products Section ======================= -->
<section id="find-products" class="clipped-section" style="
    clip-path: polygon(0 8vw, 100% 0, 100% 100%, 0% 100%);
    --border-color: <?php echo htmlspecialchars($settings['find_home_border'] ?? '#f36f21'); ?>;
    background: linear-gradient(135deg, <?php echo htmlspecialchars($settings['find_home_gradient1'] ?? '#4ba5e3'); ?>, <?php echo htmlspecialchars($settings['find_home_gradient2'] ?? '#3498db'); ?>);
">
    <div class="container text-center">
        <h2><?php echo htmlspecialchars($translations['find_products_title']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($translations['find_products_p1'])); ?></p>
        <a href="#" class="btn btn-green"><?php echo htmlspecialchars($translations['btn_view_map']); ?> <i class="fas fa-map-marker-alt"></i></a>
    </div>
</section>

<!-- ======================= Contact Section ======================= -->
<section id="contact" class="content-section">
     <div class="container grid-2">
        <div class="map-wrapper"><img src="https://i.imgur.com/C3fJ6y9.png" alt="Map Location" style="width:100%; height:100%; object-fit: cover; border-radius: 8px;"></div>
        <div class="contact-details">
            <h3><?php echo htmlspecialchars($translations['contact_title']); ?></h3>
            <ul>
                <li><i class="fas fa-map-pin"></i> <strong>Ramallah - Palestine</strong><br>Hifa road Birnaballah</li>
                <li><i class="fas fa-phone-alt"></i> <strong>+970-59-8863638</strong><br>Mon to Fri 9am to 8 pm</li>
                <li><i class="fas fa-envelope"></i> <strong>info@elhelo.com</strong></li>
            </ul>
            <a href="#" class="btn btn-primary"><?php echo htmlspecialchars($translations['btn_more_details']); ?></a>
        </div>
    </div>
</section>

<?php 
require_once 'layouts/footer.php'; 
?>