<?php// filepath: C:/wamp64/www/site/admin/manage_partners.php?>
<?php
require_once '../config/config.php';
require_once '../config/Database.php';
require_once '../models/PartnerModel.php';

$partnerModel = new PartnerModel();
$message = '';

// التعامل مع إضافة شريك جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_partner'])) {
    if ($partnerModel->addPartner($_POST, $_FILES['logo'])) {
        $message = '<div class="success-message">تمت إضافة الشريك بنجاح!</div>';
    } else {
        $message = '<div class="error-message">فشلت إضافة الشريك. يرجى التحقق من أذونات رفع الملفات.</div>';
    }
}

// التعامل مع حذف شريك
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    if ($partnerModel->deletePartner($_GET['id'])) {
        header('Location: manage_partners.php?message=deleted');
        exit;
    }
}

// إظهار رسالة نجاح الحذف
if (isset($_GET['message']) && $_GET['message'] == 'deleted') {
     $message = '<div class="success-message">تم حذف الشريك بنجاح!</div>';
}

$partners = $partnerModel->getPartners();
$page_title = 'إدارة الشركاء';
$page_description = 'إضافة أو حذف شركاء الشركة وشعاراتهم.';
include 'templates/header.php';
?>

<?php echo $message; ?>

<div class="grid-container">
    <!-- نموذج لإضافة شريك جديد -->
    <div class="grid-item">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-plus-circle"></i> إضافة شريك جديد</h3>
            </div>
            <div class="card-body">
                <form action="manage_partners.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">اسم الشريك</label>
                        <input type="text" id="name" name="name" required placeholder="مثال: شركة بوك">
                    </div>
                    <div class="form-group">
                        <label for="website_url">رابط الموقع (اختياري)</label>
                        <input type="text" id="website_url" name="website_url" placeholder="https://www.example.com">
                    </div>
                    <div class="form-group">
                        <label for="logo">شعار الشريك</label>
                        <input type="file" id="logo" name="logo" required accept="image/png, image/jpeg, image/svg+xml, image/webp">
                    </div>
                    <button type="submit" name="add_partner" class="submit-btn full-width"><i class="fas fa-save"></i> إضافة الشريك</button>
                </form>
            </div>
        </div>
    </div>

    <!-- جدول الشركاء الحاليين -->
    <div class="grid-item">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-list-ul"></i> الشركاء الحاليون</h3>
            </div>
            <div class="card-body">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>الشعار</th>
                            <th>الاسم</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($partners)): ?>
                            <tr>
                                <td colspan="3">لا يوجد شركاء. أضف واحداً باستخدام النموذج.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($partners as $partner): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo BASE_URL . 'assets/images/partners/' . htmlspecialchars($partner['logo_url']); ?>" alt="<?php echo htmlspecialchars($partner['name']); ?>" height="40">
                                    </td>
                                    <td><?php echo htmlspecialchars($partner['name']); ?></td>
                                    <td>
                                        <a href="manage_partners.php?action=delete&id=<?php echo $partner['id']; ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد من الحذف؟');" title="حذف"><i class="fas fa-trash-alt"></i></a>
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

<?php
include 'templates/footer.php';
?>