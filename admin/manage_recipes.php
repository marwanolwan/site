<?php// filepath: C:/wamp64/www/site/admin/manage_recipes.php?>
<?php
require_once '../config/config.php';
require_once '../config/Database.php';
require_once '../models/RecipeModel.php';

$recipeModel = new RecipeModel();

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $recipeModel->deleteRecipe($_GET['id']);
    header('Location: manage_recipes.php');
    exit;
}

$recipes = $recipeModel->getRecipes();
$page_title = 'إدارة الوصفات';
$page_description = 'إضافة، تعديل أو حذف وصفاتك.';
include 'templates/header.php';
?>

<div class="card">
    <div class="card-header" style="display:flex; justify-content: space-between; align-items: center;">
        <h3><i class="fas fa-utensils"></i> جميع الوصفات</h3>
        <a href="recipe_form.php" class="submit-btn" style="text-decoration: none;">
            <i class="fas fa-plus-circle"></i> إضافة وصفة جديدة
        </a>
    </div>
    <div class="card-body">
        <table class="data-table">
            <thead><tr><th>الصورة</th><th>العنوان (العربية)</th><th>الصنف</th><th>الإجراءات</th></tr></thead>
            <tbody>
                <?php if (empty($recipes)): ?>
                    <tr><td colspan="4">لا توجد وصفات.</td></tr>
                <?php else: ?>
                    <?php foreach ($recipes as $recipe): ?>
                        <tr>
                            <td><img src="<?php echo BASE_URL . 'assets/images/recipes/' . htmlspecialchars($recipe['image_url']); ?>" alt="<?php echo htmlspecialchars($recipe['title_en']); ?>" width="60" style="border-radius: 8px;"></td>
                            <td><?php echo htmlspecialchars($recipe['title_ar']); ?></td>
                            <td><?php echo htmlspecialchars($recipe['category_name']); ?></td>
                            <td>
                                <a href="recipe_form.php?id=<?php echo $recipe['id']; ?>" class="action-btn edit-btn" title="تعديل"><i class="fas fa-edit"></i></a>
                                <a href="manage_recipes.php?action=delete&id=<?php echo $recipe['id']; ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد؟');" title="حذف"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'templates/footer.php'; ?>