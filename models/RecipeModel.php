<?php// filepath: C:/wamp64/www/site/models/RecipeModel.php?>
<?php
class RecipeModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // --- Category Management ---
    public function getCategories() {
        $stmt = $this->conn->prepare("SELECT * FROM recipe_categories ORDER BY name_ar ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCategory($name_ar, $name_en) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO recipe_categories (name_ar, name_en) VALUES (:name_ar, :name_en)");
            $stmt->bindParam(':name_ar', $name_ar);
            $stmt->bindParam(':name_en', $name_en);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function deleteCategory($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM recipe_categories WHERE id = :id");
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    // --- Recipe Management (Admin) ---

    public function getRecipes() {
        $query = 'SELECT r.*, c.name_ar as category_name FROM recipes r LEFT JOIN recipe_categories c ON r.category_id = c.id ORDER BY r.id DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecipeById($id) {
        $query = 'SELECT * FROM recipes WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addRecipe($data, $files) {
        $this->conn->beginTransaction();
        try {
            $query = 'INSERT INTO recipes (category_id, title_ar, title_en, image_url, prep_time, servings, difficulty, ingredients_ar, ingredients_en, instructions_ar, instructions_en) 
                      VALUES (:category_id, :title_ar, :title_en, :image_url, :prep_time, :servings, :difficulty, :ingredients_ar, :ingredients_en, :instructions_ar, :instructions_en)';
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':category_id' => $data['category_id'],
                ':title_ar' => $data['title_ar'],
                ':title_en' => $data['title_en'],
                ':image_url' => $data['image_url'],
                ':prep_time' => $data['prep_time'],
                ':servings' => $data['servings'],
                ':difficulty' => $data['difficulty'],
                ':ingredients_ar' => $data['ingredients_ar'],
                ':ingredients_en' => $data['ingredients_en'],
                ':instructions_ar' => $data['instructions_ar'],
                ':instructions_en' => $data['instructions_en']
            ]);
            $recipeId = $this->conn->lastInsertId();
            $this->uploadAdditionalImages($recipeId, $files);
            $this->conn->commit();
            return $recipeId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
  
    public function updateRecipe($id, $data, $files) {
        try {
            $query = 'UPDATE recipes SET 
                        category_id = :category_id, title_ar = :title_ar, title_en = :title_en, 
                        image_url = :image_url, prep_time = :prep_time, servings = :servings, 
                        difficulty = :difficulty, ingredients_ar = :ingredients_ar, ingredients_en = :ingredients_en, 
                        instructions_ar = :instructions_ar, instructions_en = :instructions_en
                      WHERE id = :id';
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':category_id' => $data['category_id'],
                ':title_ar' => $data['title_ar'],
                ':title_en' => $data['title_en'],
                ':image_url' => $data['image_url'],
                ':prep_time' => $data['prep_time'],
                ':servings' => $data['servings'],
                ':difficulty' => $data['difficulty'],
                ':ingredients_ar' => $data['ingredients_ar'],
                ':ingredients_en' => $data['ingredients_en'],
                ':instructions_ar' => $data['instructions_ar'],
                ':instructions_en' => $data['instructions_en']
            ]);
            $this->uploadAdditionalImages($id, $files);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteRecipe($id) {
        $recipe = $this->getRecipeById($id);
        if ($recipe) {
            if (!empty($recipe['image_url']) && file_exists(ROOT_PATH . '/assets/images/recipes/' . $recipe['image_url'])) {
                unlink(ROOT_PATH . '/assets/images/recipes/' . $recipe['image_url']);
            }
            $additionalImages = $this->getAdditionalImages($id);
            foreach ($additionalImages as $img) {
                $this->deleteAdditionalImage($img['id']);
            }
        }
        $stmt = $this->conn->prepare("DELETE FROM recipes WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function uploadAdditionalImages($recipeId, $files) {
        if (isset($files['additional_images']) && !empty($files['additional_images']['name'][0])) {
            $upload_dir = ROOT_PATH . '/assets/images/recipes/gallery/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            foreach ($files['additional_images']['tmp_name'] as $key => $tmp_name) {
                if ($files['additional_images']['error'][$key] == 0) {
                    $file_name = time() . '_' . uniqid() . '_' . basename($files['additional_images']['name'][$key]);
                    if (move_uploaded_file($tmp_name, $upload_dir . $file_name)) {
                        $stmt = $this->conn->prepare("INSERT INTO recipe_images (recipe_id, image_url) VALUES (?, ?)");
                        $stmt->execute([$recipeId, $file_name]);
                    }
                }
            }
        }
    }

    public function getAdditionalImages($recipeId) {
        $stmt = $this->conn->prepare("SELECT * FROM recipe_images WHERE recipe_id = ? ORDER BY id ASC");
        $stmt->execute([$recipeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function deleteAdditionalImage($id) {
        $stmt = $this->conn->prepare("SELECT image_url FROM recipe_images WHERE id = ?");
        $stmt->execute([$id]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($image) {
            $file_path = ROOT_PATH . '/assets/images/recipes/gallery/' . $image['image_url'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $stmt = $this->conn->prepare("DELETE FROM recipe_images WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // --- Public Site Functions ---
    public function getRecipesGroupedByCategory($lang = 'ar', $searchTerm = null) {
        $sql = "SELECT r.id, r.title_{$lang} as title, r.image_url, r.prep_time, c.name_{$lang} as category_name
                FROM recipes r
                LEFT JOIN recipe_categories c ON r.category_id = c.id";
        $params = [];
        if ($searchTerm) {
            $sql .= " WHERE r.title_ar LIKE :searchTerm OR r.title_en LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }
        $sql .= " ORDER BY c.id, r.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // --- (الإصلاح هنا) ---
        $grouped = []; // تمت إعادة السطر المفقود
        // --- (نهاية الإصلاح) ---

        foreach ($recipes as $recipe) {
            $categoryName = $recipe['category_name'] ?? 'Uncategorized Recipes';
            $grouped[$categoryName][] = $recipe;
        }
        return $grouped;
    }

    public function getPublicRecipeDetails($id, $lang = 'ar') {
         $sql = "SELECT 
                    r.title_{$lang} as title, 
                    r.image_url, 
                    r.ingredients_{$lang} as ingredients, 
                    r.instructions_{$lang} as instructions, 
                    r.prep_time, 
                    r.servings, 
                    r.difficulty,
                    c.name_{$lang} as category_name
                FROM recipes r 
                LEFT JOIN recipe_categories c ON r.category_id = c.id 
                WHERE r.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($recipe) {
            $recipe['additional_images'] = $this->getAdditionalImages($id);
        }
        return $recipe;
    }
}
?>