<?php// filepath: C:/wamp64/www/site/admin/templates/header.php?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../config/config.php';
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'لوحة التحكم'; ?> - الصافي</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>admin/css/style.css">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">الصافي</div>
        <nav class="sidebar-nav">
            <a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt fa-fw"></i> لوحة التحكم
            </a>
            
            <hr style="border-color: #334155; margin: 15px 20px;">
            
            <a href="manage_slider.php" class="<?php echo ($current_page == 'manage_slider.php') ? 'active' : ''; ?>">
                <i class="fas fa-images fa-fw"></i> السلايدر الرئيسي
            </a>
            <a href="manage_about.php" class="<?php echo ($current_page == 'manage_about.php') ? 'active' : ''; ?>">
                <i class="fas fa-info-circle fa-fw"></i> قسم من نحن
            </a>
            <a href="manage_partners.php" class="<?php echo ($current_page == 'manage_partners.php') ? 'active' : ''; ?>">
                <i class="fas fa-handshake fa-fw"></i> قسم الشركاء
            </a>
            <a href="manage_products_section.php" class="<?php echo in_array($current_page, ['manage_products_section.php', 'manage_categories.php', 'manage_products.php', 'product_form.php']) ? 'active' : ''; ?>">
                <i class="fas fa-box-open fa-fw"></i> قسم المنتجات
            </a>
            <a href="manage_recipes_section.php" class="<?php echo in_array($current_page, ['manage_recipes_section.php', 'manage_recipe_categories.php', 'manage_recipes.php', 'recipe_form.php']) ? 'active' : ''; ?>">
                <i class="fas fa-utensils fa-fw"></i> قسم الوصفات
            </a>
            <a href="manage_locations.php" class="<?php echo ($current_page == 'manage_locations.php') ? 'active' : ''; ?>">
                <i class="fas fa-map-marker-alt fa-fw"></i> إدارة نقاط البيع
            </a>
             <a href="manage_contact.php" class="<?php echo ($current_page == 'manage_contact.php') ? 'active' : ''; ?>">
                <i class="fas fa-envelope fa-fw"></i> قسم التواصل
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
            </a>
        </div>
    </aside>

    <div class="main-content">
        <div class="page-header">
            <h1><?php echo $page_title ?? 'لوحة التحكم'; ?></h1>
            <p><?php echo $page_description ?? 'مرحباً بك في لوحة تحكم الموقع.'; ?></p>
        </div>