<?php// filepath: C:/wamp64/www/site/views/contact.php?>
<?php require_once 'layouts/header.php'; ?>

<!-- 1. بانر صفحة التواصل -->
<div class="contact-page-banner" style="background: linear-gradient(135deg, <?php echo htmlspecialchars($settings['contact_banner_color1'] ?? '#6366f1'); ?>, <?php echo htmlspecialchars($settings['contact_banner_color2'] ?? '#818cf8'); ?>);">
    <div class="container banner-grid">
        <div class="banner-text">
            <h1><?php echo htmlspecialchars($translations['contact_banner_title'] ?? 'We Are Here to Help!'); ?></h1>
            <p><?php echo htmlspecialchars($translations['contact_banner_subtitle'] ?? ''); ?></p>
        </div>
        <div class="banner-image">
            <div class="banner-image-circle">
                <img src="<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($settings['contact_banner_image'] ?? ''); ?>" alt="Contact Icon">
            </div>
        </div>
    </div>
</div>

<style>
    /* إعادة استخدام وتخصيص كلاسات البانر */
    .contact-page-banner {
        padding: 50px 0;
        clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        color: #fff;
    }
    .contact-main-content {
        padding: 50px 0;
    }
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: flex-start;
    }
    .map-full-width iframe {
        width: 100%;
        height: 450px;
        border-radius: 12px;
        border: 1px solid #eee;
    }
    .contact-form-container {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    .contact-form-container h3 {
        font-size: 1.8rem;
        margin-top: 0;
        margin-bottom: 20px;
    }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-family: inherit;
    }
    .form-group textarea { min-height: 120px; resize: vertical; }
    .success-box {
        padding: 20px;
        background: #e6f9f0;
        border: 1px solid #a3e9c5;
        color: #0d8a50;
        border-radius: 8px;
        text-align: center;
    }
    @media (max-width: 992px) {
        .contact-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="container contact-main-content">
    <div class="contact-grid">
        <div class="map-full-width">
            <h3><?php echo htmlspecialchars($translations['contact_map_title'] ?? 'Our Location'); ?></h3>
            <iframe 
                loading="lazy" 
                allowfullscreen 
                src="https://www.google.com/maps/embed/v1/place?key=<?php echo htmlspecialchars($settings['google_maps_api_key']); ?>&q=<?php echo htmlspecialchars($settings['contact_latitude']); ?>,<?php echo htmlspecialchars($settings['contact_longitude']); ?>">
            </iframe>
        </div>
        <div class="contact-form-container">
            <h3><?php echo htmlspecialchars($translations['contact_form_title'] ?? 'Send us a Message'); ?></h3>
            
            <?php if($form_sent): ?>
                <div class="success-box">
                    <p><?php echo htmlspecialchars($translations['form_success_message'] ?? 'Message sent!'); ?></p>
                </div>
            <?php else: ?>
                <form action="<?php echo BASE_URL; ?>contact" method="POST">
                    <div class="form-group">
                        <label for="name"><?php echo htmlspecialchars($translations['form_label_name'] ?? 'Name'); ?></label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email"><?php echo htmlspecialchars($translations['form_label_email'] ?? 'Email'); ?></label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="subject"><?php echo htmlspecialchars($translations['form_label_subject'] ?? 'Subject'); ?></label>
                        <input type="text" id="subject" name="subject">
                    </div>
                    <div class="form-group">
                        <label for="message"><?php echo htmlspecialchars($translations['form_label_message'] ?? 'Message'); ?></label>
                        <textarea id="message" name="message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo htmlspecialchars($translations['form_btn_send'] ?? 'Send'); ?></button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="back-home-btn-container" style="padding-bottom: 50px;">
    <a href="<?php echo BASE_URL; ?>" class="btn btn-primary"><?php echo htmlspecialchars($translations['btn_back_to_home'] ?? 'Back to Homepage'); ?></a>
</div>

<?php require_once 'layouts/footer.php'; ?>