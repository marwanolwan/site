<?php// filepath: C:/wamp64/www/site/admin/manage_recipe_categories.php?>
<?php
require_once '../config/config.php';
require_once '../config/Database.php';
require_once '../models/RecipeModel.php';

$recipeModel = new RecipeModel();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    if (!empty($_POST['name_ar']) && !empty($_POST['name_en'])) {
        if ($recipeModel->addCategory($_POST['name_ar'], $_POST['name_en'])) {
            $message = '<div class="success-message">تمت إضافة الصنف بنجاح!</div>';
        }
    }
}
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $recipeModel->deleteCategory((int)$_GET['id']);
    header('Location: manage_recipe_categories.php?message=deleted');
    exit;
}
if (isset($_GET['message']) && $_GET['message'] == 'deleted') {
    $message = '<div class="success-message">تم حذف الصنف بنجاح!</div>';
}

$categories = $recipeModel->getCategories();
$page_title = 'إدارة أصناف الوصفات';
$page_description = 'إضافة، تعديل أو حذف أصناف الوصفات.';
include 'templates/header.php';
?>

<?php echo $message; ?>
<div class="grid-container">
    <div class="grid-item">
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-plus-circle"></i> إضافة صنف جديد</h3></div>
            <div class="card-body">
                <form action="manage_recipe_categories.php" method="POST">
                    <div class="form-group"><label for="name_en">الاسم (الإنجليزية)</label><input type="text" id="name_en" name="name_en" required></div>
                    <div class="form-group"><label for="name_ar">الاسم (العربية)</label><input type="text" id="name_ar" name="name_ar" dir="rtl" required></div>
                    <button type="submit" name="add_category" class="submit-btn full-width"><i class="fas fa-save"></i> إضافة الصنف</button>
                </form>
            </div>
        </div>
    </div>
    <div class="grid-item">
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-list-ul"></i> الأصناف الحالية</h3></div>
            <div class="card-body">
                <table class="data-table">
                    <thead><tr><th>المعرف</th><th>الاسم (الإنجليزية)</th><th>الاسم (العربية)</th><th>الإجراءات</th></tr></thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr><td colspan="4">لا توجد أصناف.</td></tr>
                        <?php else: ?>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo $category['id']; ?></td>
                                    <td><?php echo htmlspecialchars($category['name_en']); ?></td>
                                    <td><?php echo htmlspecialchars($category['name_ar']); ?></td>
                                    <td>
                                        <a href="#" class="action-btn edit-btn" title="تعديل (قريباً)"><i class="fas fa-edit"></i></a>
                                        <a href="manage_recipe_categories.php?action=delete&id=<?php echo $category['id']; ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد من الحذف؟');" title="حذف"><i class="fas fa-trash-alt"></i></a>
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