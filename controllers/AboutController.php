<?php// filepath: C:/wamp64/www/site/controllers/AboutController.php?>
<?php
require_once 'controllers/BaseController.php';
require_once 'models/AboutModel.php';

class AboutController extends BaseController {
    private $aboutModel;
    
    public function __construct() {
        parent::__construct();
        $this->aboutModel = new AboutModel();
    }

    public function index() {
        $page_data = [];
        $page_data['tabs'] = $this->aboutModel->getActiveTabs($this->data['current_lang']);
        $this->render('about', $page_data);
    }
}