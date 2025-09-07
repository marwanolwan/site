<?php// filepath: C:/wamp64/www/site/admin/manage_slider.php?>
<?php
require_once '../config/config.php';
require_once '../config/Database.php';
require_once '../models/SliderModel.php';
require_once '../models/SettingModel.php';

$sliderModel = new SliderModel();
$settingModel = new SettingModel();
$message = '';

// --- معالجة إرسال النماذج ---

// 1. إضافة شريحة جديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_slide'])) {
    $data = $_POST;
    $data['is_active'] = isset($data['is_active']) ? 1 : 0;
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../assets/images/slider/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $image_name = time() . '_' . str_replace(' ', '_', basename($_FILES['image']['name']));
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
        $data['image_url'] = $image_name;
        if($sliderModel->addSlide($data)) {
            $message = '<div class="success-message">تمت إضافة الشريحة بنجاح!</div>';
        }
    }
}

// 2. حذف شريحة
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $slide = $sliderModel->getSlideById($_GET['id']);
    if ($slide && !empty($slide['image_url']) && file_exists('../assets/images/slider/' . $slide['image_url'])) {
        unlink('../assets/images/slider/' . $slide['image_url']);
    }
    $sliderModel->deleteSlide($_GET['id']);
    header('Location: manage_slider.php?message=deleted');
    exit;
}
if(isset($_GET['message']) && $_GET['message'] == 'deleted') {
    $message = '<div class="success-message">تم حذف الشريحة بنجاح!</div>';
}

// 3. حفظ إعدادات السلايدر
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $settings_data = $_POST['settings'];
    $settings_data['slider_loop'] = isset($_POST['settings']['slider_loop']) ? 1 : 0;
    $settings_data['slider_autoplay'] = isset($_POST['settings']['slider_autoplay']) ? 1 : 0;

    if ($settingModel->updateSettings($settings_data)) {
        $message = '<div class="success-message">تم تحديث إعدادات السلايدر بنجاح!</div>';
    }
}

// --- جلب البيانات للعرض ---
$slides = $sliderModel->getSlides();
$all_settings = $settingModel->getSettings();
$page_title = 'إدارة السلايدر الرئيسي';
$page_description = 'إضافة وحذف الشرائح، والتحكم في إعدادات السلايدر.';
include 'templates/header.php';
?>

<?php echo $message; ?>

<div class="grid-container">
    <!-- العمود الأيمن: إضافة شريحة والإعدادات -->
    <div class="grid-item">
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-plus-circle"></i> إضافة شريحة جديدة</h3></div>
            <div class="card-body">
                <form action="manage_slider.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group"><label>الصورة (مستحسن: 1920x800)</label><input type="file" name="image" required></div>
                    <div class="form-group"><label>العنوان (الإنجليزية)</label><input type="text" name="title_en"></div>
                    <div class="form-group"><label>العنوان (العربية)</label><input type="text" name="title_ar" dir="rtl"></div>
                    <div class="form-group"><label>العنوان الفرعي (الإنجليزية)</label><input type="text" name="subtitle_en"></div>
                    <div class="form-group"><label>العنوان الفرعي (العربية)</label><input type="text" name="subtitle_ar" dir="rtl"></div>
                    <div class="form-group"><label>ترتيب العرض</label><input type="number" name="slide_order" value="0"></div>
                    <div class="form-group"><label><input type="checkbox" name="is_active" value="1" checked> فعال</label></div>
                    <button type="submit" name="add_slide" class="submit-btn full-width"><i class="fas fa-plus"></i> إضافة الشريحة</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3><i class="fas fa-cogs fa-fw"></i> إعدادات السلايدر</h3></div>
            <div class="card-body">
                <form action="manage_slider.php" method="POST">
                    <div class="form-group">
                        <label>السرعة (بالمللي ثانية، مثال: 5000 = 5 ثواني)</label>
                        <input type="number" name="settings[slider_speed]" value="<?php echo htmlspecialchars($all_settings['slider_speed'] ?? 5000); ?>">
                    </div>
                    <div class="form-group">
                        <label>تأثير الانتقال</label>
                        <select name="settings[slider_effect]">
                            <option value="slide" <?php echo (($all_settings['slider_effect'] ?? '') == 'slide') ? 'selected' : ''; ?>>انزلاق</option>
                            <option value="fade" <?php echo (($all_settings['slider_effect'] ?? '') == 'fade') ? 'selected' : ''; ?>>تلاشي</option>
                            <option value="cube" <?php echo (($all_settings['slider_effect'] ?? '') == 'cube') ? 'selected' : ''; ?>>مكعب</option>
                            <option value="flip" <?php echo (($all_settings['slider_effect'] ?? '') == 'flip') ? 'selected' : ''; ?>>قلب</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" name="settings[slider_loop]" value="1" <?php echo ($all_settings['slider_loop'] ?? 0) ? 'checked' : ''; ?>> تكرار مستمر</label>
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" name="settings[slider_autoplay]" value="1" <?php echo ($all_settings['slider_autoplay'] ?? 0) ? 'checked' : ''; ?>> تشغيل تلقائي</label>
                    </div>
                    <button type="submit" name="save_settings" class="submit-btn full-width"><i class="fas fa-save"></i> حفظ الإعدادات</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- العمود الأيسر: الشرائح الحالية -->
    <div class="grid-item">
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-list-ul"></i> الشرائح الحالية</h3></div>
            <div class="card-body">
                <table class="data-table">
                    <thead><tr><th>الصورة</th><th>العنوان</th><th>الترتيب</th><th>الحالة</th><th>الإجراءات</th></tr></thead>
                    <tbody>
                        <?php if(empty($slides)): ?>
                            <tr><td colspan="5">لا توجد شرائح. أضف واحدة من النموذج.</td></tr>
                        <?php else: ?>
                            <?php foreach ($slides as $slide): ?>
                            <tr>
                                <td><img src="<?php echo BASE_URL . 'assets/images/slider/' . htmlspecialchars($slide['image_url']); ?>" width="100" style="border-radius: 8px;"></td>
                                <td><?php echo htmlspecialchars($slide['title_ar']); ?></td>
                                <td><?php echo $slide['slide_order']; ?></td>
                                <td><?php echo $slide['is_active'] ? '<span style="color:green;">فعال</span>' : '<span style="color:red;">غير فعال</span>'; ?></td>
                                <td>
                                    <a href="manage_slider.php?action=delete&id=<?php echo $slide['id']; ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد من الحذف؟');" title="حذف"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>