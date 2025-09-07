<?php// filepath: C:/wamp64/www/site/admin/manage_recipes_section.php?>
<?php
require_once '../config/config.php';
require_once '../config/Database.php';
require_once '../models/SettingModel.php';

$settingModel = new SettingModel();
$message = '';

// معالجة إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_recipes_section'])) {
    $settings_data = $_POST['settings'];
    $translations_data = $_POST['translations'];

    // معالجة رفع الصور
    $upload_dir = '../assets/images/';
    if (isset($_FILES['home_recipes_image_file']) && $_FILES['home_recipes_image_file']['error'] == 0) {
        $img_name = 'home_recipes_img_' . time() . '.' . pathinfo($_FILES['home_recipes_image_file']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['home_recipes_image_file']['tmp_name'], $upload_dir . $img_name)) {
            $settings_data['home_recipes_image'] = $img_name;
        }
    }
    if (isset($_FILES['recipes_banner_image_file']) && $_FILES['recipes_banner_image_file']['error'] == 0) {
        $img_name = 'recipes_banner_img_' . time() . '.' . pathinfo($_FILES['recipes_banner_image_file']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['recipes_banner_image_file']['tmp_name'], $upload_dir . $img_name)) {
            $settings_data['recipes_banner_image'] = $img_name;
        }
    }
    
    $settingModel->updateSettings($settings_data);
    $settingModel->updateTranslations($translations_data);
    $message = '<div class="success-message">تم تحديث إعدادات قسم الوصفات بنجاح!</div>';
}

// جلب البيانات
$all_settings = $settingModel->getSettings();
$all_translations_raw = $settingModel->getAllTranslations();
$translations = [];
foreach($all_translations_raw as $item) {
    $translations[$item['text_key']] = $item;
}

$page_title = 'إدارة قسم الوصفات';
$page_description = 'التحكم في مظهر ومحتوى قسم الوصفات في الصفحة الرئيسية والصفحات الداخلية.';
include 'templates/header.php';
?>

<?php echo $message; ?>

<form action="manage_recipes_section.php" method="POST" enctype="multipart/form-data">

    <div class="grid-container">
        <!-- العمود الأيمن -->
        <div class="grid-item">
            <div class="card">
                <div class="card-header"><h3><i class="fas fa-home fa-fw"></i> قسم الصفحة الرئيسية</h3></div>
                <div class="card-body">
                    <div class="form-group"><label>العنوان (الإنجليزية)</label><input type="text" name="translations[recipes_title][en]" value="<?php echo htmlspecialchars($translations['recipes_title']['text_en'] ?? ''); ?>"></div>
                    <div class="form-group"><label>العنوان (العربية)</label><input type="text" name="translations[recipes_title][ar]" value="<?php echo htmlspecialchars($translations['recipes_title']['text_ar'] ?? ''); ?>" dir="rtl"></div>
                    <div class="form-group"><label>النص (الإنجليزية)</label><textarea name="translations[recipes_p1][en]"><?php echo htmlspecialchars($translations['recipes_p1']['text_en'] ?? ''); ?></textarea></div>
                    <div class="form-group"><label>النص (العربية)</label><textarea name="translations[recipes_p1][ar]" dir="rtl"><?php echo htmlspecialchars($translations['recipes_p1']['text_ar'] ?? ''); ?></textarea></div>
                    <hr>
                    <div class="form-group"><label>لون التدرج 1</label><input type="color" name="settings[recipes_home_gradient1]" value="<?php echo htmlspecialchars($all_settings['recipes_home_gradient1'] ?? '#98d85f'); ?>"></div>
                    <div class="form-group"><label>لون التدرج 2</label><input type="color" name="settings[recipes_home_gradient2]" value="<?php echo htmlspecialchars($all_settings['recipes_home_gradient2'] ?? '#8bc34a'); ?>"></div>
                    <div class="form-group"><label>لون الإطار السفلي</label><input type="color" name="settings[recipes_home_border]" value="<?php echo htmlspecialchars($all_settings['recipes_home_border'] ?? '#ffffff'); ?>"></div>
                    <hr>
                    <div class="form-group"><label>صورة القسم</label><input type="file" name="home_recipes_image_file"><input type="hidden" name="settings[home_recipes_image]" value="<?php echo htmlspecialchars($all_settings['home_recipes_image'] ?? ''); ?>"><?php if (!empty($all_settings['home_recipes_image'])): ?><p><small>الحالية:</small> <img src="<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($all_settings['home_recipes_image']); ?>" height="80"></p><?php endif; ?></div>
                </div>
            </div>
        </div>

        <!-- العمود الأيسر -->
        <div class="grid-item">
            <div class="card">
                <div class="card-header"><h3><i class="fas fa-cogs fa-fw"></i> إدارة المحتوى</h3></div>
                <div class="card-body dashboard-grid" style="grid-template-columns: 1fr 1fr;">
                    <a href="manage_recipe_categories.php" class="stat-card" style="text-decoration:none; color:inherit; border: 1px solid var(--border-color); border-radius: var(--border-radius); text-align: center;">
                        <div class="card-body" style="padding: 20px;"><i class="fas fa-bookmark" style="font-size:2rem; margin-bottom:10px; color: var(--accent-primary);"></i><h3>الأصناف</h3><p style="font-size:0.9rem;">إضافة أو حذف أصناف الوصفات.</p></div>
                    </a>
                    <a href="manage_recipes.php" class="stat-card" style="text-decoration:none; color:inherit; border: 1px solid var(--border-color); border-radius: var(--border-radius); text-align: center;">
                        <div class="card-body" style="padding: 20px;"><i class="fas fa-book-open" style="font-size:2rem; margin-bottom:10px; color: var(--accent-primary);"></i><h3>الوصفات</h3><p style="font-size:0.9rem;">إضافة، تعديل أو حذف الوصفات.</p></div>
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3><i class="fas fa-palette fa-fw"></i> مظهر الصفحات الداخلية</h3></div>
                <div class="card-body">
                    <h4>صفحة عرض الوصفات (/recipes)</h4>
                    <div class="form-group"><label>لون البانر</label><input type="color" name="settings[recipes_banner_color]" value="<?php echo htmlspecialchars($all_settings['recipes_banner_color'] ?? '#27ae60'); ?>"></div>
                    <div class="form-group"><label>صورة البانر</label><input type="file" name="recipes_banner_image_file"><input type="hidden" name="settings[recipes_banner_image]" value="<?php echo htmlspecialchars($all_settings['recipes_banner_image'] ?? ''); ?>"><?php if (!empty($all_settings['recipes_banner_image'])): ?><p><small>الحالية:</small> <img src="<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($all_settings['recipes_banner_image']); ?>" height="80"></p><?php endif; ?></div>
                    <hr>
                    <h4>صفحة تفاصيل الوصفة (/recipe)</h4>
                    <div class="form-group"><label>لون البانر العلوي</label><input type="color" name="settings[recipe_banner_color1]" value="<?php echo htmlspecialchars($all_settings['recipe_banner_color1'] ?? '#27ae60'); ?>"></div>
                    <div class="form-group"><label>لون البانر السفلي</label><input type="color" name="settings[recipe_banner_color2]" value="<?php echo htmlspecialchars($all_settings['recipe_banner_color2'] ?? '#2ecc71'); ?>"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="submit-container">
            <button type="submit" name="save_recipes_section" class="submit-btn"><i class="fas fa-save"></i> حفظ جميع الإعدادات</button>
        </div>
    </div>
</form>

<?php include 'templates/footer.php'; ?>