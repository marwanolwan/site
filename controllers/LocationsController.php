<?php// filepath: C:/wamp64/www/site/controllers/LocationsController.php?>
<?php
require_once 'controllers/BaseController.php';
require_once 'models/SalesPointModel.php';

class LocationsController extends BaseController {
    
    public function index() {
        $salesPointModel = new SalesPointModel();
        
        $page_data = [];
        $page_data['apiKey'] = $this->data['settings']['google_maps_api_key'] ?? '';
        $page_data['salesPoints'] = $salesPointModel->getAllPoints();

        $this->render('locations', $page_data);
    }
}