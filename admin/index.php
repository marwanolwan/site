<?php// filepath: C:/wamp64/www/site/admin/index.php?>
<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    include 'login_form.php';
    exit;
}

$page_title = 'لوحة التحكم';
$page_description = 'مرحباً, ' . htmlspecialchars($_SESSION['admin_name']) . '! نظرة عامة سريعة على موقعك.';

include 'templates/header.php';
?>

<div class="card-body dashboard-grid">
    
    <div class="stat-card" style="border: 1px solid var(--border-color); border-radius: var(--border-radius); text-align: center;">
        <div class="card-body">
            <i class="fas fa-images" style="font-size: 2.5rem; color: var(--accent-primary); margin-bottom: 20px;"></i>
            <h3>إدارة السلايدر</h3>
            <p>التحكم في شرائح العرض الرئيسية والإعدادات.</p>
            <a href="manage_slider.php" class="submit-btn" style="text-decoration:none; padding: 10px 25px;">الانتقال</a>
        </div>
    </div>

    <div class="stat-card" style="border: 1px solid var(--border-color); border-radius: var(--border-radius); text-align: center;">
        <div class="card-body">
            <i class="fas fa-info-circle" style="font-size: 2.5rem; color: var(--accent-primary); margin-bottom: 20px;"></i>
            <h3>إدارة قسم "من نحن"</h3>
            <p>تعديل محتوى القسم في الرئيسية والصفحة الداخلية.</p>
            <a href="manage_about.php" class="submit-btn" style="text-decoration:none; padding: 10px 25px;">الانتقال</a>
        </div>
    </div>
    
    <div class="stat-card" style="border: 1px solid var(--border-color); border-radius: var(--border-radius); text-align: center;">
        <div class="card-body">
            <i class="fas fa-box-open" style="font-size: 2.5rem; color: var(--accent-primary); margin-bottom: 20px;"></i>
            <h3>إدارة المنتجات</h3>
            <p>التحكم الشامل في قسم المنتجات وتصنيفاتها.</p>
            <a href="manage_products_section.php" class="submit-btn" style="text-decoration:none; padding: 10px 25px;">الانتقال</a>
        </div>
    </div>

    <div class="stat-card" style="border: 1px solid var(--border-color); border-radius: var(--border-radius); text-align: center;">
        <div class="card-body">
            <i class="fas fa-utensils" style="font-size: 2.5rem; color: var(--accent-primary); margin-bottom: 20px;"></i>
            <h3>إدارة الوصفات</h3>
            <p>التحكم الشامل في قسم الوصفات وتصنيفاتها.</p>
            <a href="manage_recipes_section.php" class="submit-btn" style="text-decoration:none; padding: 10px 25px;">الانتقال</a>
        </div>
    </div>

</div>

<?php
include 'templates/footer.php';
?>