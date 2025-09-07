<?php// filepath: C:/wamp64/www/site/controllers/RecipesController.php?>
<?php
require_once 'controllers/BaseController.php';
require_once 'models/RecipeModel.php';

class RecipesController extends BaseController {
    private $recipeModel;
    
    public function __construct() {
        parent::__construct();
        $this->recipeModel = new RecipeModel();
    }

    public function index() {
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : null;
        $page_data['grouped_recipes'] = $this->recipeModel->getRecipesGroupedByCategory($this->data['current_lang'], $searchTerm);
        $total_recipes = 0;
        foreach ($page_data['grouped_recipes'] as $recipes_in_category) {
            $total_recipes += count($recipes_in_category);
        }
        $page_data['total_recipes'] = $total_recipes;
        $page_data['recipe_categories'] = $this->recipeModel->getCategories();
        $this->render('recipes', $page_data);
    }

    public function show($id) {
        if (is_null($id)) {
            http_response_code(400);
            $this->render('404', ['error_message' => 'Recipe ID is missing.']);
            exit;
        }
        $recipe = $this->recipeModel->getPublicRecipeDetails($id, $this->data['current_lang']);
        if (!$recipe) {
            http_response_code(404);
            $this->render('404');
            exit;
        }
        $page_data['recipe'] = $recipe;
        $this->render('recipe_details', $page_data);
    }
}
?>