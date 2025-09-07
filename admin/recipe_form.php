<?php// filepath: C:/wamp64/www/site/admin/recipe_form.php?>
<?php
require_once '../config/config.php';
require_once '../config/Database.php';
require_once '../models/RecipeModel.php';

$recipeModel = new RecipeModel();
$edit_mode = false;
$recipe = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $edit_mode = true;
    $recipe = $recipeModel->getRecipeById((int)$_GET['id']);
    if (!$recipe) {
        header('Location: manage_recipes.php');
        exit;
    }
}

// معالجة حذف صورة إضافية
if (isset($_GET['delete_img']) && is_numeric($_GET['delete_img']) && $edit_mode) {
    if ($recipeModel->deleteAdditionalImage((int)$_GET['delete_img'])) {
        header('Location: recipe_form.php?id=' . $_GET['id'] . '&message=img_deleted');
        exit;
    }
}

$categories = $recipeModel->getCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    
    // التعامل مع الصورة الرئيسية
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../assets/images/recipes/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $image_name = time() . '_' . str_replace(' ', '_', basename($_FILES['image']['name']));
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
            $data['image_url'] = $image_name;
        } else {
            $data['image_url'] = $data['current_image'] ?? null;
        }
    } else {
        $data['image_url'] = $data['current_image'] ?? null;
    }
    unset($data['current_image']);

    // الحفظ أو التحديث
    if ($edit_mode) {
        if ($recipeModel->updateRecipe($_GET['id'], $data, $_FILES)) {
             header('Location: manage_recipes.php?message=updated');
        }
    } else {
        $newId = $recipeModel->addRecipe($data, $_FILES);
        if ($newId) {
            header('Location: manage_recipes.php?message=added');
        }
    }
    // في حالة الفشل، سيتم عرض الصفحة مجدداً مع البيانات المدخلة
    // (يجب إضافة رسالة خطأ هنا في المستقبل)
}


// جلب الصور الإضافية في حالة التعديل
$additional_images = [];
if ($edit_mode) {
    $additional_images = $recipeModel->getAdditionalImages($recipe['id']);
}

$page_title = $edit_mode ? 'تعديل وصفة' : 'إضافة وصفة جديدة';
include 'templates/header.php';
?>

<form action="" method="POST" enctype="multipart/form-data">
    <div class="card">
        <div class="card-header"><h3>المعلومات الأساسية للوصفة</h3></div>
        <div class="card-body">
            <div class="lang-group">
                <div class="form-group"><label for="title_en">عنوان الوصفة (EN)</label><input type="text" id="title_en" name="title_en" value="<?php echo htmlspecialchars($recipe['title_en'] ?? ''); ?>" required></div>
                <div class="form-group"><label for="title_ar">عنوان الوصفة (AR)</label><input type="text" id="title_ar" name="title_ar" value="<?php echo htmlspecialchars($recipe['title_ar'] ?? ''); ?>" dir="rtl" required></div>
            </div>
            <div class="form-group">
                <label for="category_id">الصنف</label>
                <select id="category_id" name="category_id" required>
                    <option value="">-- اختر صنف --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo (($recipe['category_id'] ?? '') == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name_ar']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <hr>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label for="prep_time">وقت التحضير</label>
                    <input type="text" id="prep_time" name="prep_time" value="<?php echo htmlspecialchars($recipe['prep_time'] ?? ''); ?>" placeholder="مثال: 25 دقيقة">
                </div>
                <div class="form-group">
                    <label for="servings">تكفي لـ (أشخاص)</label>
                    <input type="text" id="servings" name="servings" value="<?php echo htmlspecialchars($recipe['servings'] ?? ''); ?>" placeholder="مثال: 4 أشخاص">
                </div>
                <div class="form-group">
                    <label for="difficulty">مستوى الصعوبة</label>
                    <select id="difficulty" name="difficulty">
                        <option value="سهلة" <?php echo (($recipe['difficulty'] ?? '') == 'سهلة') ? 'selected' : ''; ?>>سهلة</option>
                        <option value="متوسطة" <?php echo (($recipe['difficulty'] ?? 'متوسطة') == 'متوسطة') ? 'selected' : ''; ?>>متوسطة</option>
                        <option value="صعبة" <?php echo (($recipe['difficulty'] ?? '') == 'صعبة') ? 'selected' : ''; ?>>صعبة</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header"><h3>الصور والمحتوى</h3></div>
        <div class="card-body">
            <div class="form-group">
                <label for="image">الصورة الرئيسية للوصفة</label>
                <input type="file" id="image" name="image" accept="image/*" <?php echo !$edit_mode ? 'required' : ''; ?>>
                <?php if ($edit_mode && !empty($recipe['image_url'])): ?>
                    <p style="margin-top:10px;">الحالية: <img src="<?php echo BASE_URL . 'assets/images/recipes/' . $recipe['image_url']; ?>" width="120" style="border-radius: 8px;"></p>
                    <input type="hidden" name="current_image" value="<?php echo $recipe['image_url']; ?>">
                <?php endif; ?>
            </div>
            <hr>
            <div class="form-group">
                <label for="additional_images">صور إضافية (اختياري - يمكنك تحديد أكثر من صورة)</label>
                <input type="file" id="additional_images" name="additional_images[]" multiple accept="image/*">
            </div>
            <?php if (!empty($additional_images)): ?>
            <div class="gallery-preview">
                <?php foreach($additional_images as $img): ?>
                <div class="gallery-item">
                    <img src="<?php echo BASE_URL . 'assets/images/recipes/gallery/' . $img['image_url']; ?>" alt="صورة إضافية">
                    <a href="recipe_form.php?id=<?php echo $_GET['id']; ?>&delete_img=<?php echo $img['id']; ?>" class="delete-img-btn" onclick="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">&times;</a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <hr>
            <div class="lang-group">
                <div class="form-group"><label>المكونات (EN) - كل مكون في سطر</label><textarea name="ingredients_en" rows="8"><?php echo htmlspecialchars($recipe['ingredients_en'] ?? ''); ?></textarea></div>
                <div class="form-group"><label>المكونات (AR) - كل مكون في سطر</label><textarea name="ingredients_ar" dir="rtl" rows="8"><?php echo htmlspecialchars($recipe['ingredients_ar'] ?? ''); ?></textarea></div>
            </div>
             <div class="lang-group">
                <div class="form-group"><label>طريقة التحضير (EN) - كل خطوة في سطر</label><textarea name="instructions_en" rows="10"><?php echo htmlspecialchars($recipe['instructions_en'] ?? ''); ?></textarea></div>
                <div class="form-group"><label>طريقة التحضير (AR) - كل خطوة في سطر</label><textarea name="instructions_ar" dir="rtl" rows="10"><?php echo htmlspecialchars($recipe['instructions_ar'] ?? ''); ?></textarea></div>
            </div>
        </div>
        <div class="submit-container">
            <button type="submit" class="submit-btn"><i class="fas fa-save"></i> حفظ الوصفة</button>
        </div>
    </div>
</form>

<style>
.gallery-preview { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 15px; padding: 15px; background: var(--bg-main); border-radius: 8px; }
.gallery-item { position: relative; width: 120px; height: 120px; }
.gallery-item img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
.delete-img-btn { position: absolute; top: -8px; right: -8px; background: #ef4444; color: white; border: 2px solid white; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: bold; cursor: pointer; text-decoration: none; transition: all 0.2s ease; }
.delete-img-btn:hover { background: #dc2626; transform: scale(1.1); }
</style>

<?php include 'templates/footer.php'; ?>