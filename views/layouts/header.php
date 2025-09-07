<?php// filepath: C:/wamp64/www/site/views/layouts/header.php?>
<?php
$request_path = strtok(trim(str_replace(basename(dirname(__FILE__, 3)), '', $_SERVER['REQUEST_URI']), '/'), '?');
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo ($current_lang == 'ar') ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Al-Safi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        :root {
            --dark-blue: <?php echo htmlspecialchars($settings['color_dark_blue'] ?? '#09132a'); ?>;
            --primary-orange: <?php echo htmlspecialchars($settings['color_primary_orange'] ?? '#f36f21'); ?>;
            --secondary-yellow: <?php echo htmlspecialchars($settings['color_secondary_yellow'] ?? '#ffc107'); ?>;
            --brand-cyan: <?php echo htmlspecialchars($settings['color_brand_cyan'] ?? '#00bce4'); ?>;
            --brand-light-green: <?php echo htmlspecialchars($settings['color_brand_light_green'] ?? '#8bc34a'); ?>;
            --brand-light-blue: <?php echo htmlspecialchars($settings['color_brand_light_blue'] ?? '#3498db'); ?>;
        }
    </style>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <a href="<?php echo BASE_URL; ?>" class="header-logo"><strong>Al-Safi</strong></a>
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>" class="<?php echo ($request_path == '' || $request_path == 'site' || $request_path == 'home') ? 'active' : ''; ?>"><?php echo htmlspecialchars($translations['nav_home'] ?? 'Home'); ?></a></li>
                    <li><a href="<?php echo BASE_URL; ?>about" class="<?php echo ($request_path == 'about') ? 'active' : ''; ?>"><?php echo htmlspecialchars($translations['nav_about'] ?? 'About Us'); ?></a></li>
                    <li><a href="<?php echo BASE_URL; ?>products" class="<?php echo ($request_path == 'products') ? 'active' : ''; ?>"><?php echo htmlspecialchars($translations['nav_products'] ?? 'Products'); ?></a></li>
                    <li><a href="<?php echo BASE_URL; ?>recipes" class="<?php echo ($request_path == 'recipes') ? 'active' : ''; ?>"><?php echo htmlspecialchars($translations['nav_recipes'] ?? 'Recipes'); ?></a></li>
                    <li><a href="<?php echo BASE_URL; ?>locations" class="<?php echo ($request_path == 'locations') ? 'active' : ''; ?>"><?php echo htmlspecialchars($translations['nav_locations'] ?? 'Locations'); ?></a></li>
                    <li>
                        <?php
                        $queryParams = $_GET;
                        $queryParams['lang'] = ($current_lang == 'ar' ? 'en' : 'ar');
                        $newQueryString = http_build_query($queryParams);
                        ?>
                        <a href="?<?php echo $newQueryString; ?>"><?php echo ($current_lang == 'ar' ? 'English' : 'العربية'); ?></a>
                    </li>
                    <!-- (الكود الجديد يبدأ هنا) -->
                    <li>
                        <a href="<?php echo BASE_URL; ?>contact" class="btn-nav-contact">
                            <?php echo htmlspecialchars($translations['nav_contact'] ?? 'Contact Us'); ?>
                        </a>
                    </li>
                    <!-- (نهاية الكود الجديد) -->
                </ul>
            </nav>
            <div class="header-contact">
                <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?></span>
                <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?></span>
            </div>
        </div>
    </header>
    <main>