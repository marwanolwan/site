<?php// filepath: C:/wamp64/www/site/index.php?>
<?php
require_once 'config/config.php';
require_once 'config/Database.php';

// --- Improved Routing Logic ---
$request_uri = $_SERVER['REQUEST_URI'];
$script_path = dirname($_SERVER['SCRIPT_NAME']);
if (strpos($request_uri, $script_path) === 0) {
    $path = substr($request_uri, strlen($script_path));
} else {
    $path = $request_uri;
}
$path_without_query = strtok($path, '?');
$path = trim($path_without_query, '/');
// --- End of Improved Routing Logic ---


// Simple routing based on the clean path
switch ($path) {
    case '':
    case 'home':
        require_once 'controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
    
    // --- (جديد) ---
    case 'about':
        require_once 'controllers/AboutController.php';
        $controller = new AboutController();
        $controller->index();
        break;
    // --- (نهاية الجديد) ---

    case 'products':
        require_once 'controllers/ProductsController.php';
        $controller = new ProductsController();
        $controller->index();
        break;

    case 'product':
        // (الإصلاح) استدعاء المتحكم دائمًا
        require_once 'controllers/ProductsController.php';
        $controller = new ProductsController();
        
        // مرر الـ ID إذا كان موجودًا، وإلا مرر null
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $controller->show($id);
        break;
    case 'recipes':
        require_once 'controllers/RecipesController.php';
        $controller = new RecipesController();
        $controller->index();
        break;

    case 'recipe':
        require_once 'controllers/RecipesController.php';
        $controller = new RecipesController();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $controller->show($id);
        break;
    case 'locations':
        require_once 'controllers/LocationsController.php';
        $controller = new LocationsController();
        $controller->index();
        break;
    case 'contact':
        require_once 'controllers/ContactController.php';
        $controller = new ContactController();
        $controller->index();
        break;
    default:
        require_once 'controllers/BaseController.php';
        $controller = new BaseController();
        http_response_code(404);
        $controller->render('404');
        break;
}