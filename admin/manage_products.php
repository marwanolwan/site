<?php// filepath: C:/wamp64/www/site/admin/manage_products.php?>
<?php
require_once '../config/config.php';
require_once '../config/Database.php';
require_once '../models/ProductModel.php';

$productModel = new ProductModel();

// معالجة حذف المنتج
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    // لاحقًا، سنضيف كود حذف الصورة من السيرفر
    $productModel->deleteProduct($_GET['id']);
    header('Location: manage_products.php');
    exit;
}

$products = $productModel->getProducts();

$page_title = 'إدارة المنتجات';
$page_description = 'إضافة، تعديل أو حذف منتجاتك.';
include 'templates/header.php';
?>

<div class="card">
    <div class="card-header" style="display:flex; justify-content: space-between; align-items: center;">
        <h3><i class="fas fa-box-open"></i> جميع المنتجات</h3>
        <a href="product_form.php" class="submit-btn" style="text-decoration: none;">
            <i class="fas fa-plus-circle"></i> إضافة منتج جديد
        </a>
    </div>
    <div class="card-body">
        <table class="data-table">
            <thead>
                <tr>
                    <th>الصورة</th>
                    <th>الاسم (العربية)</th>
                    <th>الصنف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr><td colspan="4">لا توجد منتجات.</td></tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <img src="<?php echo BASE_URL . 'assets/images/products/' . htmlspecialchars($product['image_url'] ?? ''); ?>" alt="<?php echo htmlspecialchars($product['name_en'] ?? ''); ?>" width="60" style="border-radius: 8px;">
                            </td>
                            <!-- (الإصلاح هنا) -->
                            <td><?php echo htmlspecialchars($product['name_ar'] ?? 'لا يوجد اسم'); ?></td>
                            <!-- (الإصلاح هنا) -->
                            <td><?php echo htmlspecialchars($product['category_name'] ?? 'بدون صنف'); ?></td>
                            <td>
                                <a href="product_form.php?id=<?php echo $product['id']; ?>" class="action-btn edit-btn" title="تعديل"><i class="fas fa-edit"></i></a>
                                <a href="manage_products.php?action=delete&id=<?php echo $product['id']; ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد؟');" title="حذف"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include 'templates/footer.php';
?>