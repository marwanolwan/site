<?php// filepath: C:/wamp64/www/site/controllers/HomeController.php?>
<?php
// تضمين الملفات المطلوبة
require_once 'models/SettingModel.php';
require_once 'controllers/BaseController.php';
require_once 'models/SliderModel.php';
require_once 'models/PartnerModel.php'; // <--- أضف هذا السطر هنا

class HomeController extends BaseController {
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $home_data = [];
        
        // جلب شرائح السلايدر
        $sliderModel = new SliderModel();
        $home_data['slides'] = $sliderModel->getSlides(true); // جلب الشرائح النشطة فقط
        
        // (جديد) جلب الشركاء
        $partnerModel = new PartnerModel();
        $home_data['partners'] = $partnerModel->getPartners();
        
        $this->render('home', $home_data);
    }
}