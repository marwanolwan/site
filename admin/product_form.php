<?php// filepath: C:/wamp64/www/site/admin/product_form.php?>
<?php
require_once '../config/config.php';
require_once '../config/Database.php';
require_once '../models/ProductModel.php';

$productModel = new ProductModel();

$edit_mode = false;
$product = [];
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $edit_mode = true;
    $product = $productModel->getProductById($_GET['id']);
}

$categories = $productModel->getCategories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../assets/images/products/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name);
        $data['image_url'] = $image_name;
    } else {
        $data['image_url'] = $data['current_image'] ?? null;
    }
    unset($data['current_image']);

    if ($edit_mode) {
        $productModel->updateProduct($_GET['id'], $data);
    } else {
        $productModel->addProduct($data);
    }
    header('Location: manage_products.php');
    exit;
}

$page_title = $edit_mode ? 'تعديل منتج' : 'إضافة منتج جديد';
// فك ترميز بيانات الحقائق الغذائية للعرض في النموذج
$nutritional_facts = ($edit_mode && !empty($product['nutritional_facts'])) ? json_decode($product['nutritional_facts'], true) : [];

include 'templates/header.php';
?>

<form action="" method="POST" enctype="multipart/form-data">
    <!-- (تمت استعادة هذه البطاقة) -->
    <div class="card">
        <div class="card-header"><h3>المعلومات الأساسية</h3></div>
        <div class="card-body">
            <div class="form-group">
                <label for="category_id">الصنف</label>
                <select id="category_id" name="category_id" required>
                    <option value="">-- اختر صنف --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo ($edit_mode && ($product['category_id'] ?? '') == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name_ar']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="lang-group">
                <div class="form-group"><label for="name_en">اسم المنتج (EN)</label><input type="text" id="name_en" name="name_en" value="<?php echo htmlspecialchars($product['name_en'] ?? ''); ?>" required></div>
                <div class="form-group"><label for="name_ar">اسم المنتج (AR)</label><input type="text" id="name_ar" name="name_ar" value="<?php echo htmlspecialchars($product['name_ar'] ?? ''); ?>" dir="rtl" required></div>
            </div>
            <div class="form-group">
                <label for="image">صورة المنتج الرئيسية (للبطاقة الداخلية)</label>
                <input type="file" id="image" name="image" accept="image/*" <?php echo !$edit_mode ? 'required' : ''; ?>>
                <?php if ($edit_mode && !empty($product['image_url'])): ?>
                    <p>الحالية: <img src="<?php echo BASE_URL . 'assets/images/products/' . $product['image_url']; ?>" width="100" style="border-radius: 8px;"></p>
                    <input type="hidden" name="current_image" value="<?php echo $product['image_url']; ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- (تمت استعادة هذه البطاقة) -->
    <div class="card">
        <div class="card-header"><h3>محتوى بانر صفحة التفاصيل</h3></div>
        <div class="card-body">
            <div class="lang-group">
                <div class="form-group"><label for="banner_title_en">العنوان التسويقي (EN)</label><input type="text" id="banner_title_en" name="banner_title_en" placeholder="e.g., The creamiest cheese spread..." value="<?php echo htmlspecialchars($product['banner_title_en'] ?? ''); ?>"></div>
                <div class="form-group"><label for="banner_title_ar">العنوان التسويقي (AR)</label><input type="text" id="banner_title_ar" name="banner_title_ar" dir="rtl" placeholder="مثال: جبنة كريم مطبوخة قابلة للدهن..." value="<?php echo htmlspecialchars($product['banner_title_ar'] ?? ''); ?>"></div>
            </div>
            <div class="lang-group">
                <div class="form-group"><label for="banner_tagline_en">الوصف التسويقي المختصر (EN)</label><textarea id="banner_tagline_en" name="banner_tagline_en" placeholder="e.g., Gather your family every morning..."><?php echo htmlspecialchars($product['banner_tagline_en'] ?? ''); ?></textarea></div>
                <div class="form-group"><label for="banner_tagline_ar">الوصف التسويقي المختصر (AR)</label><textarea id="banner_tagline_ar" name="banner_tagline_ar" dir="rtl" placeholder="مثال: اجمعي عائلتك كل صباح..."><?php echo htmlspecialchars($product['banner_tagline_ar'] ?? ''); ?></textarea></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>معلومات المنتج (للصفحة الداخلية)</h3></div>
        <div class="card-body">
            <div class="lang-group">
                <div class="form-group"><label>الوصف الكامل (EN)</label><textarea name="description_en"><?php echo htmlspecialchars($product['description_en'] ?? ''); ?></textarea></div>
                <div class="form-group"><label>الوصف الكامل (AR)</label><textarea name="description_ar" dir="rtl"><?php echo htmlspecialchars($product['description_ar'] ?? ''); ?></textarea></div>
            </div>
            <hr>
            <h4>معلومات الوزن</h4>
            <div class="lang-group">
                <div class="form-group"><label>معلومات الوزن (EN)</label><textarea name="weight_info_en"><?php echo htmlspecialchars($product['weight_info_en'] ?? ''); ?></textarea></div>
                <div class="form-group"><label>معلومات الوزن (AR)</label><textarea name="weight_info_ar" dir="rtl"><?php echo htmlspecialchars($product['weight_info_ar'] ?? ''); ?></textarea></div>
            </div>
            <hr>
            <div class="lang-group">
                <div class="form-group"><label>المكونات (EN)</label><textarea name="ingredients_en"><?php echo htmlspecialchars($product['ingredients_en'] ?? ''); ?></textarea></div>
                <div class="form-group"><label>المكونات (AR)</label><textarea name="ingredients_ar" dir="rtl"><?php echo htmlspecialchars($product['ingredients_ar'] ?? ''); ?></textarea></div>
            </div>
            <div class="lang-group">
                <div class="form-group"><label>كيفية التخزين (EN)</label><textarea name="storage_en"><?php echo htmlspecialchars($product['storage_en'] ?? ''); ?></textarea></div>
                <div class="form-group"><label>كيفية التخزين (AR)</label><textarea name="storage_ar" dir="rtl"><?php echo htmlspecialchars($product['storage_ar'] ?? ''); ?></textarea></div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header"><h3>الحقائق الغذائية (ديناميكي)</h3></div>
        <div class="card-body">
            <div id="facts-container">
                <?php if (!empty($nutritional_facts) && is_array($nutritional_facts)): ?>
                    <?php foreach ($nutritional_facts as $index => $fact): ?>
                    <div class="fact-row">
                        <div class="form-group"><label>اسم الحقيقة (AR)</label><input type="text" name="facts[<?php echo $index; ?>][name_ar]" value="<?php echo htmlspecialchars($fact['name_ar'] ?? ''); ?>"></div>
                        <div class="form-group"><label>اسم الحقيقة (EN)</label><input type="text" name="facts[<?php echo $index; ?>][name_en]" value="<?php echo htmlspecialchars($fact['name_en'] ?? ''); ?>"></div>
                        <div class="form-group"><label>القيمة</label><input type="text" name="facts[<?php echo $index; ?>][value]" value="<?php echo htmlspecialchars($fact['value'] ?? ''); ?>"></div>
                        <button type="button" class="remove-fact-btn action-btn delete-btn" title="إزالة">×</button>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" id="add-fact-btn" class="submit-btn" style="background: var(--accent-secondary);"><i class="fas fa-plus"></i> إضافة حقيقة غذائية</button>
        </div>
        <div class="submit-container">
            <button type="submit" class="submit-btn"><i class="fas fa-save"></i> حفظ المنتج</button>
        </div>
    </div>
</form>

<style>
.fact-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 50px;
    gap: 15px;
    align-items: flex-end;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}
.remove-fact-btn {
    margin-bottom: 25px; /* لموازنة الحقول */
    height: 48px;
    width: 48px;
    font-size: 1.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('add-fact-btn');
    const container = document.getElementById('facts-container');
    let factIndex = <?php echo count($nutritional_facts); ?>;

    addBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'fact-row';
        newRow.innerHTML = `
            <div class="form-group"><label>اسم الحقيقة (AR)</label><input type="text" name="facts[${factIndex}][name_ar]"></div>
            <div class="form-group"><label>اسم الحقيقة (EN)</label><input type="text" name="facts[${factIndex}][name_en]"></div>
            <div class="form-group"><label>القيمة</label><input type="text" name="facts[${factIndex}][value]"></div>
            <button type="button" class="remove-fact-btn action-btn delete-btn" title="إزالة">×</button>
        `;
        container.appendChild(newRow);
        factIndex++;
    });

    container.addEventListener('click', function(e) {
        // التأكد من أن الزر موجود وأن العنصر الأب موجود قبل الحذف
        if (e.target && e.target.classList.contains('remove-fact-btn') && e.target.closest('.fact-row')) {
            e.target.closest('.fact-row').remove();
        }
    });
});
</script>

<?php include 'templates/footer.php'; ?>