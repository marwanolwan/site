<?php// filepath: C:/wamp64/www/site/admin/manage_contact.php?>
<?php
require_once '../config/config.php';
require_once '../config/Database.php';
require_once '../models/SettingModel.php';
require_once '../models/ContactModel.php';

$settingModel = new SettingModel();
$contactModel = new ContactModel();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings_data = $_POST['settings'];
    $translations_data = $_POST['translations'];
    
    if (isset($_FILES['contact_banner_image_file']) && $_FILES['contact_banner_image_file']['error'] == 0) {
        $upload_dir = '../assets/images/';
        $img_name = 'contact_banner_img_' . time() . '.' . pathinfo($_FILES['contact_banner_image_file']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES['contact_banner_image_file']['tmp_name'], $upload_dir . $img_name)) {
            $settings_data['contact_banner_image'] = $img_name;
        }
    }

    $settingModel->updateSettings($settings_data);
    $settingModel->updateTranslations($translations_data);
    $message = '<div class="success-message">تم تحديث الإعدادات بنجاح!</div>';
}

$all_settings = $settingModel->getSettings();
$all_translations_raw = $settingModel->getAllTranslations();
$translations = [];
foreach($all_translations_raw as $item) { $translations[$item['text_key']] = $item; }
$contact_messages = $contactModel->getMessages();

$page_title = 'إدارة قسم التواصل';
$page_description = 'التحكم في بيانات التواصل، الخريطة، ونماذج الرسائل الواردة.';
include 'templates/header.php';
?>

<?php echo $message; ?>
            <div class="card">
                <div class="card-header"><h3><i class="fas fa-share-alt"></i> روابط التواصل الاجتماعي</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label>رابط فيسبوك (Facebook)</label>
                        <input type="text" name="settings[social_facebook]" value="<?php echo htmlspecialchars($all_settings['social_facebook'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>رابط انستغرام (Instagram)</label>
                        <input type="text" name="settings[social_instagram]" value="<?php echo htmlspecialchars($all_settings['social_instagram'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>رابط تويتر (Twitter)</label>
                        <input type="text" name="settings[social_twitter]" value="<?php echo htmlspecialchars($all_settings['social_twitter'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>رابط لينكد إن (LinkedIn)</label>
                        <input type="text" name="settings[social_linkedin]" value="<?php echo htmlspecialchars($all_settings['social_linkedin'] ?? ''); ?>">
                    </div>
                </div>
            </div>
<div class="grid-container">
    <div class="grid-item">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header"><h3><i class="fas fa-phone-alt"></i> بيانات التواصل والخريطة</h3></div>
                <div class="card-body">
                    <div class="form-group"><label>البريد الإلكتروني</label><input type="text" name="settings[contact_email]" value="<?php echo htmlspecialchars($all_settings['contact_email'] ?? ''); ?>"></div>
                    <div class="form-group"><label>رقم الهاتف</label><input type="text" name="settings[contact_phone]" value="<?php echo htmlspecialchars($all_settings['contact_phone'] ?? ''); ?>"></div>
                    <div class="form-group"><label>العنوان</label><textarea name="settings[contact_address]"><?php echo htmlspecialchars($all_settings['contact_address'] ?? ''); ?></textarea></div>
                    <hr>
                    <p><strong>إحداثيات الخريطة:</strong></p>
                    <div class="lang-group">
                        <div class="form-group"><label>خط العرض (Latitude)</label><input type="text" name="settings[contact_latitude]" value="<?php echo htmlspecialchars($all_settings['contact_latitude'] ?? ''); ?>"></div>
                        <div class="form-group"><label>خط الطول (Longitude)</label><input type="text" name="settings[contact_longitude]" value="<?php echo htmlspecialchars($all_settings['contact_longitude'] ?? ''); ?>"></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3><i class="fas fa-envelope"></i> إعدادات نموذج التواصل</h3></div>
                <div class="card-body">
                    <div class="form-group"><label>البريد الإلكتروني لاستلام الرسائل</label><input type="text" name="settings[contact_form_email]" value="<?php echo htmlspecialchars($all_settings['contact_form_email'] ?? ''); ?>"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h3><i class="fas fa-file-image"></i> بانر صفحة التواصل</h3></div>
                <div class="card-body">
                    <div class="form-group"><label>العنوان (EN)</label><input type="text" name="translations[contact_banner_title][en]" value="<?php echo htmlspecialchars($translations['contact_banner_title']['text_en'] ?? ''); ?>"></div>
                    <div class="form-group"><label>العنوان (AR)</label><input type="text" name="translations[contact_banner_title][ar]" value="<?php echo htmlspecialchars($translations['contact_banner_title']['text_ar'] ?? ''); ?>" dir="rtl"></div>
                    <div class="form-group"><label>النص (EN)</label><textarea name="translations[contact_banner_subtitle][en]"><?php echo htmlspecialchars($translations['contact_banner_subtitle']['text_en'] ?? ''); ?></textarea></div>
                    <div class="form-group"><label>النص (AR)</label><textarea name="translations[contact_banner_subtitle][ar]" dir="rtl"><?php echo htmlspecialchars($translations['contact_banner_subtitle']['text_ar'] ?? ''); ?></textarea></div>
                    <hr>
                    <div class="form-group"><label>لون التدرج 1</label><input type="color" name="settings[contact_banner_color1]" value="<?php echo htmlspecialchars($all_settings['contact_banner_color1'] ?? '#6366f1'); ?>"></div>
                    <div class="form-group"><label>لون التدرج 2</label><input type="color" name="settings[contact_banner_color2]" value="<?php echo htmlspecialchars($all_settings['contact_banner_color2'] ?? '#818cf8'); ?>"></div>
                    <hr>
                     <div class="form-group"><label>صورة البانر</label><input type="file" name="contact_banner_image_file"><input type="hidden" name="settings[contact_banner_image]" value="<?php echo htmlspecialchars($all_settings['contact_banner_image'] ?? ''); ?>"><?php if (!empty($all_settings['contact_banner_image'])): ?><p><small>الحالية:</small> <img src="<?php echo BASE_URL . 'assets/images/' . htmlspecialchars($all_settings['contact_banner_image']); ?>" height="80"></p><?php endif; ?></div>
                </div>
                <div class="submit-container"><button type="submit" class="submit-btn"><i class="fas fa-save"></i> حفظ الإعدادات</button></div>
            </div>
        </form>
    </div>
    <div class="grid-item">
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-inbox"></i> الرسائل الواردة</h3></div>
            <div class="card-body">
                <table class="data-table">
                    <thead><tr><th>الاسم</th><th>البريد الإلكتروني</th><th>الموضوع</th><th>تاريخ الإرسال</th></tr></thead>
                    <tbody>
                        <?php if(empty($contact_messages)): ?>
                            <tr><td colspan="4">لا توجد رسائل واردة.</td></tr>
                        <?php else: ?>
                            <?php foreach($contact_messages as $msg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($msg['created_at'])); ?></td>
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