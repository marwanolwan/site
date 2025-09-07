<?php// filepath: C:/wamp64/www/site/controllers/BaseController.php?>
<?php
// ابدأ الجلسة دائمًا في المتحكم الأساسي
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'models/SettingModel.php';
require_once 'models/ProductModel.php';

class BaseController {
    protected $data = [];

    public function __construct() {
    // --- إدارة اللغة الجديدة باستخدام الجلسات (نسخة محسّنة) ---
    
    // 1. إذا قام المستخدم بتغيير اللغة عبر الرابط
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'en'])) {
        $_SESSION['lang'] = $_GET['lang'];

        // (إصلاح) إعادة بناء الرابط بدون باراميتر اللغة مع الحفاظ على البقية
        $uri = strtok($_SERVER['REQUEST_URI'], '?'); // المسار بدون أي باراميترات
        $queryParams = $_GET;
        unset($queryParams['lang']); // إزالة باراميتر اللغة فقط

        $redirectUrl = $uri;
        if (!empty($queryParams)) {
            // إعادة بناء الرابط مع الباراميترات المتبقية (مثل id=3)
            $redirectUrl .= '?' . http_build_query($queryParams);
        }
        
        header("Location: " . $redirectUrl);
        exit;
    }

    // 2. حدد اللغة الحالية (كما كان)
    $current_lang = $_SESSION['lang'] ?? 'ar';
    $this->data['current_lang'] = $current_lang;
    
    // ... بقية الكود يبقى كما هو (جلب الإعدادات والترجمات)
    $settingModel = new SettingModel();
    $this->data['settings'] = $settingModel->getSettings();
    $this->data['translations'] = $settingModel->getTranslations($current_lang);

    $productModel = new ProductModel();
    $this->data['categories'] = $productModel->getCategories();
}


    public function render($viewName, $viewData = []) {
        $data_to_render = array_merge($this->data, $viewData);
        extract($data_to_render);
        require_once "views/{$viewName}.php";
    }
}