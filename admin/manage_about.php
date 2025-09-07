<?php// filepath: C:/wamp64/www/site/admin/manage_about.php?>
<?php
require_once '../config/config.php';
require_once '../config/Database.php';
require_once '../models/SettingModel.php';
require_once '../models/AboutModel.php';

$settingModel = new SettingModel();
$aboutModel = new AboutModel();
$update_success = false;
$message = '';

// --- معالجة إرسال النماذج ---

// 1. التعامل مع الإعدادات العامة ورفع الصور
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_general_settings'])) {
    $settings_data = $_POST['settings'];
    $translations_data = $_POST['translations'];

    // التعامل مع رفع الصور
    $upload_dir = '../assets/images/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    if (isset($_FILES['home_about_image_file']) && $_FILES['home_about_image_file']['error'] == 0) {
        $img_name = 'home_about_img_' . time() . '.' . pathinfo($_FILES['home_about_image_file']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['home_about_image_file']['tmp_name'], $upload_dir . $img_name)) {
            $settings_data['home_about_image'] = $img_name;
        }
    }
    if (isset($_FILES['about_page_intro_image_file']) && $_FILES['about_page_intro_image_file']['error'] == 0) {
        $img_name = 'about_intro_img_' . time() . '.' . pathinfo($_FILES['about_page_intro_image_file']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['about_page_intro_image_file']['tmp_name'], $upload_dir . $img_name)) {
            $settings_data['about_page_intro_image'] = $img_name;
        }
    }
    
    $settingModel->updateSettings($settings_data);
    $settingModel->updateTranslations($translations_data);
    $update_success = true;
}

// 2. التعامل مع إضافة "تاب" جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_tab'])) {
    if ($aboutModel->addTab($_POST, $_FILES['image_url'] ?? null)) {
        $message = '<div class="success-message">تمت إضافة التبويب بنجاح!</div>';
    } else {
        $message = '<div class="error-message">فشلت عملية إضافة التبويب.</div>';
    }
}

// 3. التعامل مع حذف "تاب"
if (isset($_GET['action']) && $_GET['action'] == 'delete_tab' && isset($_GET['id'])) {
    if ($aboutModel->deleteTab($_GET['id'])) {
        header('Location: manage_about.php?message=deleted');
        exit;
    }
}

if (isset($_GET['message']) && $_GET['message'] == 'deleted') {
    $message = '<div class="success-message">تم حذف التبويب بنجاح!</div>';
}

// --- جلب البيانات للعرض ---
$all_settings = $settingModel->getSettings();
$all_translations_raw = $settingModel->getAllTranslations();
$about_tabs = $aboutModel->getAllTabs();

// دالة مساعدة لجلب ترجمة معينة
$translations = [];
foreach($all_translations_raw as $item) {
    $translations[$item['text_key']] = $item;
}

$page_title = 'إدارة قسم "من نحن"';
$page_description = 'تحكم في كل المحتوى المتعلق بقسم "من نحن" في الصفحة الرئيسية والصفحة الداخلية.';
include 'templates/header.php';
?>

<?php if ($update_success): ?>
<div class="success-message"><i class="fas fa-check-circle"></i> تم تحديث الإعدادات بنجاح!</div>
<?php endif; ?>
<?php echo $message; ?>


<form action="manage_about.php" method="POST" enctype="multipart/form-data">
    <!-- بطاقة قسم "من نحن" في الصفحة الرئيسية -->
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-home fa-fw"></i> قسم "من نحن" في الصفحة الرئيسية</h3></div>
        <div class="card-body">
            <div class="lang-group">
                <div class="form-group"><label>العنوان (الإنجليزية)</label><input type="text" name="translations[home_about_title][en]" value="<?php echo htmlspecialchars($translations['home_about_title']['text_en'] ?? ''); ?>"></div>
                <div class="form-group"><label>العنوان (العربية)</label><input type="text" name="translations[home_about_title][ar]" value="<?php echo htmlspecialchars($translations['home_about_title']['text_ar'] ?? ''); ?>" dir="rtl"></div>
            </div>
            <div class="lang-group">
                <div class="form-group"><label>المحتوى (الإنجليزية)</label><textarea name="translations[home_about_content][en]"><?php echo htmlspecialchars($translations['home_about_content']['text_en'] ?? ''); ?></textarea></div>
                <div class="form-group"><label>المحتوى (العربية)</label><textarea name="translations[home_about_content][ar]" dir="rtl"><?php echo htmlspecialchars($translations['home_about_content']['text_ar'] ?? ''); ?></textarea></div>
            </div>
            <div class="form-group">
                <label>صورة القسم</label>
                <input type="file" name="home_about_image_file">
                <input type="hidden" name="settings[home_about_image]" value="<?php echo htmlspecialchars($all_settings['home_about_image'] ?? ''); ?>">
                <?php if (!empty($all_settings['home_about_image'])): ?>
                    <p><small>الصورة الحالية:</small> <img src="<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($all_settings['home_about_image']); ?>" height="80"></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- بطاقة صفحة "من نحن" التفصيلية -->
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-file-alt fa-fw"></i> صفحة "من نحن" التفصيلية</h3></div>
        <div class="card-body">
            <h4>إعدادات البانر</h4>
            <div class="form-group">
                <label>لون خلفية البانر</label>
                <input type="color" name="settings[about_page_banner_color]" value="<?php echo htmlspecialchars($all_settings['about_page_banner_color'] ?? '#007bff'); ?>">
            </div>
            <div class="lang-group">
                <div class="form-group"><label>عنوان البانر (الإنجليزية)</label><input type="text" name="translations[about_page_banner_title][en]" value="<?php echo htmlspecialchars($translations['about_page_banner_title']['text_en'] ?? ''); ?>"></div>
                <div class="form-group"><label>عنوان البانر (العربية)</label><input type="text" name="translations[about_page_banner_title][ar]" value="<?php echo htmlspecialchars($translations['about_page_banner_title']['text_ar'] ?? ''); ?>" dir="rtl"></div>
            </div>
            <hr>
            <h4>القسم التمهيدي</h4>
            <div class="lang-group">
                <div class="form-group"><label>العنوان التمهيدي (الإنجليزية)</label><input type="text" name="translations[about_page_intro_title][en]" value="<?php echo htmlspecialchars($translations['about_page_intro_title']['text_en'] ?? ''); ?>"></div>
                <div class="form-group"><label>العنوان التمهيدي (العربية)</label><input type="text" name="translations[about_page_intro_title][ar]" value="<?php echo htmlspecialchars($translations['about_page_intro_title']['text_ar'] ?? ''); ?>" dir="rtl"></div>
            </div>
            <div class="lang-group">
                <div class="form-group"><label>المحتوى التمهيدي (الإنجليزية)</label><textarea name="translations[about_page_intro_content][en]"><?php echo htmlspecialchars($translations['about_page_intro_content']['text_en'] ?? ''); ?></textarea></div>
                <div class="form-group"><label>المحتوى التمهيدي (العربية)</label><textarea name="translations[about_page_intro_content][ar]" dir="rtl"><?php echo htmlspecialchars($translations['about_page_intro_content']['text_ar'] ?? ''); ?></textarea></div>
            </div>
            <div class="form-group">
                <label>الصورة التمهيدية</label>
                <input type="file" name="about_page_intro_image_file">
                <input type="hidden" name="settings[about_page_intro_image]" value="<?php echo htmlspecialchars($all_settings['about_page_intro_image'] ?? ''); ?>">
                <?php if (!empty($all_settings['about_page_intro_image'])): ?>
                    <p><small>الصورة الحالية:</small> <img src="<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($all_settings['about_page_intro_image']); ?>" height="80"></p>
                <?php endif; ?>
            </div>
        </div>
             <!-- بطاقة عداد الأرقام -->
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-tachometer-alt fa-fw"></i> عداد الأرقام في الصفحة الرئيسية</h3></div>
        <div class="card-body">
            <p>هذه الأرقام تظهر تحت نص "من نحن" في الصفحة الرئيسية.</p>
            
            <!-- العداد الأول -->
            <div class="lang-group">
                <div class="form-group">
                    <label>العداد الأول: الرقم</label>
                    <input type="number" name="settings[counter_1_number]" value="<?php echo htmlspecialchars($all_settings['counter_1_number'] ?? '0'); ?>">
                </div>
                <div class="form-group">
                    <label>العداد الأول: النص (EN)</label>
                    <input type="text" name="translations[counter_1_text][en]" value="<?php echo htmlspecialchars($translations['counter_1_text']['text_en'] ?? ''); ?>">
                </div>
                 <div class="form-group">
                    <label>العداد الأول: النص (AR)</label>
                    <input type="text" name="translations[counter_1_text][ar]" value="<?php echo htmlspecialchars($translations['counter_1_text']['text_ar'] ?? ''); ?>" dir="rtl">
                </div>
            </div>
            <hr>
            
            <!-- العداد الثاني -->
            <div class="lang-group">
                <div class="form-group">
                    <label>العداد الثاني: الرقم</label>
                    <input type="number" name="settings[counter_2_number]" value="<?php echo htmlspecialchars($all_settings['counter_2_number'] ?? '0'); ?>">
                </div>
                <div class="form-group">
                    <label>العداد الثاني: النص (EN)</label>
                    <input type="text" name="translations[counter_2_text][en]" value="<?php echo htmlspecialchars($translations['counter_2_text']['text_en'] ?? ''); ?>">
                </div>
                 <div class="form-group">
                    <label>العداد الثاني: النص (AR)</label>
                    <input type="text" name="translations[counter_2_text][ar]" value="<?php echo htmlspecialchars($translations['counter_2_text']['text_ar'] ?? ''); ?>" dir="rtl">
                </div>
            </div>
            <hr>

            <!-- العداد الثالث -->
            <div class="lang-group">
                <div class="form-group">
                    <label>العداد الثالث: الرقم</label>
                    <input type="number" name="settings[counter_3_number]" value="<?php echo htmlspecialchars($all_settings['counter_3_number'] ?? '0'); ?>">
                </div>
                <div class="form-group">
                    <label>العداد الثالث: النص (EN)</label>
                    <input type="text" name="translations[counter_3_text][en]" value="<?php echo htmlspecialchars($translations['counter_3_text']['text_en'] ?? ''); ?>">
                </div>
                 <div class="form-group">
                    <label>العداد الثالث: النص (AR)</label>
                    <input type="text" name="translations[counter_3_text][ar]" value="<?php echo htmlspecialchars($translations['counter_3_text']['text_ar'] ?? ''); ?>" dir="rtl">
                </div>
            </div>

        </div>
    </div>
        <div class="submit-container">
            <button type="submit" name="save_general_settings" class="submit-btn"><i class="fas fa-save"></i> حفظ الإعدادات العامة</button>
        </div>
    </div>
</form>

<!-- إدارة التبويبات -->
<div class="grid-container">
    <div class="grid-item">
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-plus-circle"></i> إضافة تبويب جديد</h3></div>
            <div class="card-body">
                <form action="manage_about.php" method="POST" enctype="multipart/form-data">
                    <div class="lang-group">
                        <div class="form-group"><label>العنوان (الإنجليزية)</label><input type="text" name="title_en" required></div>
                        <div class="form-group"><label>العنوان (العربية)</label><input type="text" name="title_ar" required dir="rtl"></div>
                    </div>
                    <div class="lang-group">
                        <div class="form-group"><label>المحتوى (الإنجليزية)</label><textarea name="content_en"></textarea></div>
                        <div class="form-group"><label>المحتوى (العربية)</label><textarea name="content_ar" dir="rtl"></textarea></div>
                    </div>
                    <div class="form-group">
                        <label>صورة (اختياري - مثال: لرئيس مجلس الإدارة)</label>
                        <input type="file" name="image_url" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label>ترتيب العرض</label>
                        <input type="number" name="sort_order" value="0">
                    </div>
                     <div class="form-group">
                        <label><input type="checkbox" name="is_active" value="1" checked> فعال</label>
                    </div>
                    <button type="submit" name="add_tab" class="submit-btn full-width"><i class="fas fa-save"></i> إضافة تبويب</button>
                </form>
            </div>
        </div>
    </div>
    <div class="grid-item">
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-list-ul"></i> التبويبات الحالية</h3></div>
            <div class="card-body">
                <table class="data-table">
                    <thead><tr><th>الترتيب</th><th>العنوان</th><th>الصورة</th><th>الإجراءات</th></tr></thead>
                    <tbody>
                        <?php foreach ($about_tabs as $tab): ?>
                        <tr>
                            <td><?php echo $tab['sort_order']; ?></td>
                            <td><?php echo htmlspecialchars($tab['title_ar']); ?></td>
                            <td><?php if($tab['image_url']): ?><img src="../assets/images/about/<?php echo $tab['image_url']; ?>" height="40"><?php else: ?>-<?php endif; ?></td>
                            <td>
                                <a href="manage_about.php?action=delete_tab&id=<?php echo $tab['id']; ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد من الحذف؟');"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include 'templates/footer.php'; ?>