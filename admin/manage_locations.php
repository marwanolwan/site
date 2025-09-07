<?php// filepath: C:/wamp64/www/site/admin/manage_locations.php?>
<?php
// تضمين Composer autoload
require_once '../vendor/autoload.php';

require_once '../config/config.php';
require_once '../config/Database.php';
require_once '../models/SettingModel.php';
require_once '../models/SalesPointModel.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$settingModel = new SettingModel();
$salesPointModel = new SalesPointModel();
$message = '';

// 1. حفظ الإعدادات
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $settings_data = $_POST['settings'];
    $translations_data = $_POST['translations'];
    
    if (isset($_FILES['locations_banner_image_file']) && $_FILES['locations_banner_image_file']['error'] == 0) {
        $upload_dir = '../assets/images/';
        $img_name = 'locations_banner_img_' . time() . '.' . pathinfo($_FILES['locations_banner_image_file']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['locations_banner_image_file']['tmp_name'], $upload_dir . $img_name)) {
            $settings_data['locations_banner_image'] = $img_name;
        }
    }

    $settingModel->updateSettings($settings_data);
    $settingModel->updateTranslations($translations_data);
    $message = '<div class="success-message">تم تحديث الإعدادات بنجاح!</div>';
}

// 2. استيراد ملف Excel
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_excel'])) {
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
        $allowed_types = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv', 'application/vnd.ms-excel'];
        if (in_array($_FILES['excel_file']['type'], $allowed_types)) {
            try {
                $spreadsheet = IOFactory::load($_FILES['excel_file']['tmp_name']);
                $sheet = $spreadsheet->getActiveSheet();
                $salesPointModel->clearAllPoints();
                $rowCount = 0;
                foreach ($sheet->getRowIterator(2) as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);
                    $data = [];
                    foreach ($cellIterator as $cell) { $data[] = $cell->getValue(); }
                    if (isset($data[3]) && isset($data[4]) && is_numeric($data[3]) && is_numeric($data[4])) {
                        $salesPointModel->addPoint($data[0] ?? '', $data[1] ?? '', $data[2] ?? '', $data[3], $data[4]);
                        $rowCount++;
                    }
                }
                $message = '<div class="success-message">تم استيراد ' . $rowCount . ' نقطة بيع بنجاح!</div>';
            } catch(Exception $e) {
                $message = '<div class="error-message">حدث خطأ: ' . $e->getMessage() . '</div>';
            }
        } else { $message = '<div class="error-message">صيغة الملف غير مدعومة.</div>'; }
    } else { $message = '<div class="error-message">يرجى اختيار ملف لرفعه.</div>'; }
}

$all_settings = $settingModel->getSettings();
$all_translations_raw = $settingModel->getAllTranslations();
$translations = [];
foreach($all_translations_raw as $item) { $translations[$item['text_key']] = $item; }

$page_title = 'إدارة نقاط البيع';
$page_description = 'التحكم في قسم "أين تجد منتجاتنا" ورفع بيانات نقاط البيع.';
include 'templates/header.php';
?>

<?php echo $message; ?>

<div class="grid-container">
    <!-- العمود الأيمن - نموذج الإعدادات -->
    <div class="grid-item">
        <form action="manage_locations.php" method="POST" enctype="multipart/form-data">
            <!-- بطاقة مظهر الصفحة الرئيسية -->
            <div class="card">
                <div class="card-header"><h3><i class="fas fa-home"></i> قسم الصفحة الرئيسية</h3></div>
                <div class="card-body">
                    <div class="form-group"><label>العنوان (EN)</label><input type="text" name="translations[find_products_title][en]" value="<?php echo htmlspecialchars($translations['find_products_title']['text_en'] ?? ''); ?>"></div>
                    <div class="form-group"><label>العنوان (AR)</label><input type="text" name="translations[find_products_title][ar]" value="<?php echo htmlspecialchars($translations['find_products_title']['text_ar'] ?? ''); ?>" dir="rtl"></div>
                    <div class="form-group"><label>النص (EN)</label><textarea name="translations[find_products_p1][en]"><?php echo htmlspecialchars($translations['find_products_p1']['text_en'] ?? ''); ?></textarea></div>
                    <div class="form-group"><label>النص (AR)</label><textarea name="translations[find_products_p1][ar]" dir="rtl"><?php echo htmlspecialchars($translations['find_products_p1']['text_ar'] ?? ''); ?></textarea></div>
                    <hr>
                    <div class="form-group"><label>لون التدرج 1</label><input type="color" name="settings[find_home_gradient1]" value="<?php echo htmlspecialchars($all_settings['find_home_gradient1'] ?? '#4ba5e3'); ?>"></div>
                    <div class="form-group"><label>لون التدرج 2</label><input type="color" name="settings[find_home_gradient2]" value="<?php echo htmlspecialchars($all_settings['find_home_gradient2'] ?? '#3498db'); ?>"></div>
                    <div class="form-group"><label>لون الإطار</label><input type="color" name="settings[find_home_border]" value="<?php echo htmlspecialchars($all_settings['find_home_border'] ?? '#f36f21'); ?>"></div>
                </div>
            </div>

            <!-- بطاقة بانر صفحة الخريطة -->
            <div class="card">
                <div class="card-header"><h3><i class="fas fa-map"></i> بانر صفحة الخريطة</h3></div>
                <div class="card-body">
                    <div class="form-group"><label>العنوان (EN)</label><input type="text" name="translations[locations_banner_title][en]" value="<?php echo htmlspecialchars($translations['locations_banner_title']['text_en'] ?? ''); ?>"></div>
                    <div class="form-group"><label>العنوان (AR)</label><input type="text" name="translations[locations_banner_title][ar]" value="<?php echo htmlspecialchars($translations['locations_banner_title']['text_ar'] ?? ''); ?>" dir="rtl"></div>
                    <div class="form-group"><label>النص (EN)</label><textarea name="translations[locations_banner_subtitle][en]"><?php echo htmlspecialchars($translations['locations_banner_subtitle']['text_en'] ?? ''); ?></textarea></div>
                    <div class="form-group"><label>النص (AR)</label><textarea name="translations[locations_banner_subtitle][ar]" dir="rtl"><?php echo htmlspecialchars($translations['locations_banner_subtitle']['text_ar'] ?? ''); ?></textarea></div>
                    <hr>
                    <div class="form-group"><label>لون التدرج 1</label><input type="color" name="settings[locations_banner_color1]" value="<?php echo htmlspecialchars($all_settings['locations_banner_color1'] ?? '#1d4ed8'); ?>"></div>
                    <div class="form-group"><label>لون التدرج 2</label><input type="color" name="settings[locations_banner_color2]" value="<?php echo htmlspecialchars($all_settings['locations_banner_color2'] ?? '#3b82f6'); ?>"></div>
                    <hr>
                     <div class="form-group"><label>صورة البانر</label><input type="file" name="locations_banner_image_file"><input type="hidden" name="settings[locations_banner_image]" value="<?php echo htmlspecialchars($all_settings['locations_banner_image'] ?? ''); ?>"><?php if (!empty($all_settings['locations_banner_image'])): ?><p><small>الحالية:</small> <img src="<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($all_settings['locations_banner_image']); ?>" height="80"></p><?php endif; ?></div>
                </div>
            </div>
            
             <div class="card">
                <div class="card-header"><h3><i class="fas fa-key"></i> إعدادات API</h3></div>
                <div class="card-body">
                    <div class="form-group"><label>مفتاح Google Maps API</label><input type="text" name="settings[google_maps_api_key]" value="<?php echo htmlspecialchars($all_settings['google_maps_api_key'] ?? ''); ?>"></div>
                </div>
            </div>

            <div class="card">
                <div class="submit-container">
                    <button type="submit" name="save_settings" class="submit-btn"><i class="fas fa-save"></i> حفظ جميع الإعدادات</button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- العمود الأيسر - نموذج استيراد الملف -->
    <div class="grid-item">
        <form action="manage_locations.php" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header"><h3><i class="fas fa-file-excel"></i> استيراد نقاط البيع</h3></div>
                <div class="card-body">
                    <p>يرجى تجهيز ملف Excel أو CSV بالترتيب التالي للأعمدة (مع تخطي الصف الأول للعناوين):</p>
                    <ol style="line-height: 2;">
                        <li><strong>العمود A:</strong> اسم نقطة البيع</li>
                        <li><strong>العمود B:</strong> رقم الهاتف</li>
                        <li><strong>العمود C:</strong> العنوان</li>
                        <li><strong>العمود D:</strong> خط العرض (Latitude)</li>
                        <li><strong>العمود E:</strong> خط الطول (Longitude)</li>
                    </ol>
                    <hr>
                    <div class="form-group">
                        <label for="excel_file">اختر ملف (xlsx, csv):</label>
                        <input type="file" id="excel_file" name="excel_file" required accept=".xlsx, .csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                    </div>
                </div>
                <div class="submit-container">
                    <button type="submit" name="import_excel" class="submit-btn"><i class="fas fa-upload"></i> رفع واستيراد</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'templates/footer.php'; ?>