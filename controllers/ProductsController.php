<?php// filepath: C:/wamp64/www/site/controllers/ProductsController.php?>
<?php
require_once 'controllers/BaseController.php';

class ProductsController extends BaseController {
    private $productModel;
    
    public function __construct() {
        parent::__construct();
        $this->productModel = new ProductModel(); // ما زلنا نحتاج هذا الكائن
    }

    public function index() {
    // جلب مصطلح البحث من الرابط
    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : null;
    
    // جلب البيانات الخاصة بهذه الصفحة فقط باستخدام الدالة الجديدة
    $page_data['grouped_products'] = $this->productModel->getProductsGroupedByCategory($this->data['current_lang'], $searchTerm);
    
    // حساب العدد الإجمالي للمنتجات
    $total_products = 0;
    foreach ($page_data['grouped_products'] as $products_in_category) {
        $total_products += count($products_in_category);
    }
    $page_data['total_products'] = $total_products;

    $this->render('products', $page_data);
}

    public function show($id) { // الدالة تستقبل الآن ID قد يكون null
    // (الإصلاح) التحقق من الـ ID في بداية الدالة
    if (is_null($id)) {
        // إذا كان الـ ID مفقودًا، اعرض صفحة 404
        http_response_code(400); // Bad Request
        $this->render('404', ['error_message' => 'Product ID is missing.']); // يمكنك تمرير رسالة خطأ مخصصة
        exit;
    }

    $product = $this->productModel->getPublicProductDetails($id, $this->data['current_lang']);
    
    if (!$product) {
        http_response_code(404);
        $this->render('404');
        exit;
    }

    $page_data['product'] = $product;
    $this->render('product_details', $page_data);
}
}