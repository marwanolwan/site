<?php// filepath: C:/wamp64/www/site/views/home.php?>
<?php 
require_once 'layouts/header.php'; 

?>

<!-- ======================= Hero Slider Section ======================= -->
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
                        <div class="slide-background" 
                             style="background-image: url('<?php echo BASE_URL . 'assets/images/slider/' . htmlspecialchars($slide['image_url']); ?>');"></div>
                        <div class="slide-overlay"></div>
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
                <div class="swiper-slide">
                    <div class="slide-background" style="background-image: url('https://i.imgur.com/kS5xJtG.jpeg');"></div>
                    <div class="slide-overlay"></div>
                </div>
            <?php endif; ?>
        </div>


        <!-- النقاط -->
        <div class="swiper-pagination"></div>
    </div>
</section>


<!-- ======================= About Section ======================= -->
<section id="about" class="content-section">
    <div class="container grid-2">
        <div class="about-text">
            <h2><?php echo htmlspecialchars($translations['home_about_title'] ?? 'About Us'); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($translations['home_about_content'] ?? '')); ?></p>
            <a href="<?php echo BASE_URL; ?>about" class="btn btn-primary"><?php echo htmlspecialchars($translations['btn_read_more'] ?? 'Read More'); ?></a>
        </div>
        <div class="about-image">
            <img src="<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($settings['home_about_image'] ?? 'default_about_logo.png'); ?>" alt="Al-Safi Logo">
        </div>
    </div>
</section>


<!-- ======================= Our Products Section ======================= -->
<section id="products" class="clipped-section" style="
    background-color: <?php echo htmlspecialchars($settings['products_home_border'] ?? '#ffc107'); ?>;
    clip-path: polygon(0 8vw, 100% 0, 100% 100%, 0 100%);
">
    <div class="clipped-content" style="
        background: linear-gradient(135deg, <?php echo htmlspecialchars($settings['products_home_gradient1'] ?? '#2ed9ff'); ?>, <?php echo htmlspecialchars($settings['products_home_gradient2'] ?? '#00bce4'); ?>);
        clip-path: inherit;
    ">
        <div class="container grid-2">
            <div class="section-image-wrapper">
                <div class="dynamic-image-circle" style="background-image: url('<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($settings['home_products_image'] ?? ''); ?>');"></div>
            </div>
            <div class="section-text">
                <h2><?php echo htmlspecialchars($translations['products_title'] ?? 'Our Products'); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($translations['products_p1'] ?? '')); ?></p>
                <a href="<?php echo BASE_URL; ?>products" class="btn btn-secondary"><?php echo htmlspecialchars($translations['btn_discover_me'] ?? 'Discover Me'); ?></a>
            </div>
        </div>
    </div>
</section>
<!-- ======================= Partners Section ======================= -->
<section id="partners" class="content-section">
    <div class="container">
        <h2 class="section-title"><?php echo htmlspecialchars($translations['partners_title'] ?? 'Our Partners'); ?></h2>
        <?php if (!empty($partners)): ?>
        <div class="swiper-container partners-slider">
            <div class="swiper-wrapper">
                <?php foreach($partners as $partner): ?>
                    <div class="swiper-slide">
                        <a href="<?php echo htmlspecialchars($partner['website_url'] ?? '#'); ?>" target="_blank" rel="noopener noreferrer">
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

<!-- ======================= Recipes Section ======================= -->
<section id="recipes" class="clipped-section" style="
    background-color: <?php echo htmlspecialchars($settings['recipes_home_border'] ?? '#ffffff'); ?>;
    clip-path: <?php echo htmlspecialchars($settings['home_recipes_clip_path'] ?? 'polygon(0 0, 100% 8vw, 100% 100%, 0% 100%)'); ?>;
">
    <div class="clipped-content" style="
        background: linear-gradient(135deg, <?php echo htmlspecialchars($settings['recipes_home_gradient1'] ?? '#98d85f'); ?>, <?php echo htmlspecialchars($settings['recipes_home_gradient2'] ?? '#8bc34a'); ?>);
        clip-path: inherit;
    ">
        <div class="container grid-2">
            <div class="section-image-wrapper">
                <div class="dynamic-image-circle" style="background-image: url('<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($settings['home_recipes_image'] ?? ''); ?>');"></div>
            </div>
            <div class="section-text">
                <h2><?php echo htmlspecialchars($translations['recipes_title'] ?? 'Our Recipes'); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($translations['recipes_p1'] ?? '')); ?></p>
                <a href="<?php echo BASE_URL; ?>recipes" class="btn btn-secondary"><?php echo htmlspecialchars($translations['btn_discover_me'] ?? 'Discover Me'); ?></a>
            </div>
        </div>
    </div>
</section>
<!-- ======================= Counters Section ======================= -->
<section id="counters" class="content-section">
    <div class="container">
        <div class="counters-grid">
            <div class="counter-item">
                <h3 class="counter-number" data-target="<?php echo htmlspecialchars($settings['counter_1_number'] ?? '0'); ?>">0</h3>
                <p><?php echo htmlspecialchars($translations['counter_1_text'] ?? ''); ?></p>
            </div>
            <div class="counter-item">
                <h3 class="counter-number" data-target="<?php echo htmlspecialchars($settings['counter_2_number'] ?? '0'); ?>">0</h3>
                <p><?php echo htmlspecialchars($translations['counter_2_text'] ?? ''); ?></p>
            </div>
            <div class="counter-item">
                <h3 class="counter-number" data-target="<?php echo htmlspecialchars($settings['counter_3_number'] ?? '0'); ?>">0</h3>
                <p><?php echo htmlspecialchars($translations['counter_3_text'] ?? ''); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ======================= Find Products Section ======================= -->
<section id="find-products" class="clipped-section" style="
    background-color: <?php echo htmlspecialchars($settings['find_home_border'] ?? '#f36f21'); ?>;
    clip-path: polygon(0 8vw, 100% 0, 100% 100%, 0% 100%);
">
    <div class="clipped-content" style="
        background: linear-gradient(135deg, <?php echo htmlspecialchars($settings['find_home_gradient1'] ?? '#4ba5e3'); ?>, <?php echo htmlspecialchars($settings['find_home_gradient2'] ?? '#3498db'); ?>);
        clip-path: inherit;
    ">
        <div class="container text-center">
            <h2><?php echo htmlspecialchars($translations['find_products_title'] ?? 'Find Our Products'); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($translations['find_products_p1'] ?? '')); ?></p>
            <a href="<?php echo BASE_URL; ?>locations" class="btn btn-green"><?php echo htmlspecialchars($translations['btn_view_map'] ?? 'اعرض الخريطة'); ?> <i class="fas fa-map-marker-alt"></i></a>
        </div>
    </div>
</section>


<!-- ======================= Contact Section (الإصلاح) ======================= -->
<section id="contact" class="content-section">
     <div class="container grid-2">
        <div class="map-wrapper">
            <iframe 
                width="100%" 
                height="100%" 
                style="border:0; border-radius: 8px;" 
                loading="lazy" 
                allowfullscreen 
                src="https://www.google.com/maps/embed/v1/place?key=<?php echo htmlspecialchars($settings['google_maps_api_key'] ?? ''); ?>&q=<?php echo htmlspecialchars($settings['contact_latitude'] ?? ''); ?>,<?php echo htmlspecialchars($settings['contact_longitude'] ?? ''); ?>">
            </iframe>
        </div>
        <div class="contact-details">
            <h3><?php echo htmlspecialchars($translations['contact_title'] ?? 'Get in Touch'); ?></h3>
            <ul>
                <li><i class="fas fa-map-pin"></i> <strong><?php echo htmlspecialchars($translations['contact_label_address'] ?? 'Address:'); ?></strong><br><?php echo nl2br(htmlspecialchars($settings['contact_address'] ?? '')); ?></li>
                <li><i class="fas fa-phone-alt"></i> <strong><?php echo htmlspecialchars($translations['contact_label_phone'] ?? 'Phone:'); ?></strong><br><?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?></li>
                <li><i class="fas fa-envelope"></i> <strong><?php echo htmlspecialchars($translations['contact_label_email'] ?? 'Email:'); ?></strong><br><?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?></li>
            </ul>
            <a href="<?php echo BASE_URL; ?>contact" class="btn btn-primary"><?php echo htmlspecialchars($translations['btn_more_details'] ?? 'More Details'); ?></a>
        </div>
    </div>
</section>

<?php 
require_once 'layouts/footer.php'; 
?>