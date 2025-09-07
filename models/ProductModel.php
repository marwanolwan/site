<?php// filepath: C:/wamp64/www/site/models/ProductModel.php?>
<?php
class ProductModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // --- Category Management ---
    public function getCategories() {
        $query = 'SELECT * FROM categories ORDER BY name_ar ASC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCategory($name_ar, $name_en) {
        $query = 'INSERT INTO categories (name_ar, name_en) VALUES (:name_ar, :name_en)';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name_ar', $name_ar);
        $stmt->bindParam(':name_en', $name_en);
        return $stmt->execute();
    }

    public function deleteCategory($id) {
        $query = 'DELETE FROM categories WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    // --- Product Management (Admin) ---
    public function getProducts() {
        $query = 'SELECT p.*, c.name_ar as category_name 
                  FROM products p
                  LEFT JOIN categories c ON p.category_id = c.id
                  ORDER BY p.id DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $query = 'SELECT * FROM products WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addProduct($data) {
        // تحويل مصفوفة الحقائق إلى JSON
        $nutritional_facts_json = isset($data['facts']) ? json_encode(array_values($data['facts']), JSON_UNESCAPED_UNICODE) : null;

        $query = 'INSERT INTO products (
                    category_id, name_ar, name_en, description_ar, description_en, 
                    banner_title_ar, banner_title_en, banner_tagline_ar, banner_tagline_en, 
                    image_url, ingredients_ar, ingredients_en, storage_ar, storage_en, 
                    weight_info_en, weight_info_ar, nutritional_facts
                  ) VALUES (
                    :category_id, :name_ar, :name_en, :description_ar, :description_en, 
                    :banner_title_ar, :banner_title_en, :banner_tagline_ar, :banner_tagline_en, 
                    :image_url, :ingredients_ar, :ingredients_en, :storage_ar, :storage_en,
                    :weight_info_en, :weight_info_ar, :nutritional_facts
                  )';
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':name_ar', $data['name_ar']);
        $stmt->bindParam(':name_en', $data['name_en']);
        $stmt->bindParam(':description_ar', $data['description_ar']);
        $stmt->bindParam(':description_en', $data['description_en']);
        $stmt->bindParam(':banner_title_ar', $data['banner_title_ar']);
        $stmt->bindParam(':banner_title_en', $data['banner_title_en']);
        $stmt->bindParam(':banner_tagline_ar', $data['banner_tagline_ar']);
        $stmt->bindParam(':banner_tagline_en', $data['banner_tagline_en']);
        $stmt->bindParam(':image_url', $data['image_url']);
        $stmt->bindParam(':ingredients_ar', $data['ingredients_ar']);
        $stmt->bindParam(':ingredients_en', $data['ingredients_en']);
        $stmt->bindParam(':storage_ar', $data['storage_ar']);
        $stmt->bindParam(':storage_en', $data['storage_en']);
        $stmt->bindParam(':weight_info_en', $data['weight_info_en']);
        $stmt->bindParam(':weight_info_ar', $data['weight_info_ar']);
        $stmt->bindParam(':nutritional_facts', $nutritional_facts_json);

        return $stmt->execute();
    }

    public function updateProduct($id, $data) {
        // تحويل مصفوفة الحقائق إلى JSON
        $nutritional_facts_json = isset($data['facts']) ? json_encode(array_values($data['facts']), JSON_UNESCAPED_UNICODE) : null;

        $query = 'UPDATE products SET 
                    category_id = :category_id, name_ar = :name_ar, name_en = :name_en, 
                    description_ar = :description_ar, description_en = :description_en, 
                    banner_title_ar = :banner_title_ar, banner_title_en = :banner_title_en,
                    banner_tagline_ar = :banner_tagline_ar, banner_tagline_en = :banner_tagline_en,
                    image_url = :image_url, ingredients_ar = :ingredients_ar, ingredients_en = :ingredients_en, 
                    storage_ar = :storage_ar, storage_en = :storage_en,
                    weight_info_en = :weight_info_en, weight_info_ar = :weight_info_ar,
                    nutritional_facts = :nutritional_facts
                  WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':category_id', $data['category_id']);
        $stmt->bindParam(':name_ar', $data['name_ar']);
        $stmt->bindParam(':name_en', $data['name_en']);
        $stmt->bindParam(':description_ar', $data['description_ar']);
        $stmt->bindParam(':description_en', $data['description_en']);
        $stmt->bindParam(':banner_title_ar', $data['banner_title_ar']);
        $stmt->bindParam(':banner_title_en', $data['banner_title_en']);
        $stmt->bindParam(':banner_tagline_ar', $data['banner_tagline_ar']);
        $stmt->bindParam(':banner_tagline_en', $data['banner_tagline_en']);
        $stmt->bindParam(':image_url', $data['image_url']);
        $stmt->bindParam(':ingredients_ar', $data['ingredients_ar']);
        $stmt->bindParam(':ingredients_en', $data['ingredients_en']);
        $stmt->bindParam(':storage_ar', $data['storage_ar']);
        $stmt->bindParam(':storage_en', $data['storage_en']);
        $stmt->bindParam(':weight_info_en', $data['weight_info_en']);
        $stmt->bindParam(':weight_info_ar', $data['weight_info_ar']);
        $stmt->bindParam(':nutritional_facts', $nutritional_facts_json);

        return $stmt->execute();
    }
    
    public function deleteProduct($id) {
        $query = 'DELETE FROM products WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // --- Public Site Functions ---
    public function getProductsGroupedByCategory($lang = 'ar', $searchTerm = null) {
        $sql = "SELECT p.id, p.name_{$lang} as name, p.image_url, c.name_{$lang} as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id";
        $params = [];
        if ($searchTerm) {
            $sql .= " WHERE p.name_ar LIKE :searchTerm OR p.name_en LIKE :searchTerm";
            $params[':searchTerm'] = '%' . $searchTerm . '%';
        }
        $sql .= " ORDER BY c.id, p.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $grouped = [];
        foreach ($products as $product) {
            $categoryName = $product['category_name'] ?? 'Uncategorized';
            $grouped[$categoryName][] = $product;
        }
        return $grouped;
    }
    
    public function getPublicProductDetails($id, $lang = 'ar') {
         $sql = "SELECT 
                    p.*,
                    p.name_{$lang} as name, 
                    p.banner_title_{$lang} as banner_title,
                    p.banner_tagline_{$lang} as banner_tagline,
                    p.description_{$lang} as description, 
                    p.ingredients_{$lang} as ingredients,
                    p.storage_{$lang} as storage,
                    p.weight_info_{$lang} as weight_info,
                    c.name_{$lang} as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // فك ترميز حقل الحقائق الغذائية
        if ($product && !empty($product['nutritional_facts'])) {
            $product['nutritional_facts_decoded'] = json_decode($product['nutritional_facts'], true);
        } else {
            $product['nutritional_facts_decoded'] = [];
        }

        return $product;
    }
}
?>