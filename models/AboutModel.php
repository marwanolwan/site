<?php// filepath: C:/wamp64/www/site/models/AboutModel.php?>
<?php
class AboutModel {
    private $conn;
    private $db;
    private $upload_dir = ROOT_PATH . '/assets/images/about/';

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
        if (!is_dir($this->upload_dir)) {
            mkdir($this->upload_dir, 0777, true);
        }
    }

    // For public page
    public function getActiveTabs($lang = 'ar') {
        $sql = "SELECT id, title_{$lang} as title, content_{$lang} as content, image_url 
                FROM about_tabs 
                WHERE is_active = 1 
                ORDER BY sort_order ASC, id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // For Admin Panel
    public function getAllTabs() {
        $stmt = $this->conn->prepare("SELECT * FROM about_tabs ORDER BY sort_order ASC, id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTabById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM about_tabs WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addTab($data, $file) {
        $image_name = null;
        if (isset($file) && $file['error'] == 0) {
            $image_name = time() . '_' . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $this->upload_dir . $image_name);
        }

        $sql = "INSERT INTO about_tabs (title_en, title_ar, content_en, content_ar, image_url, sort_order, is_active) 
                VALUES (:title_en, :title_ar, :content_en, :content_ar, :image_url, :sort_order, :is_active)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title_en' => $data['title_en'],
            ':title_ar' => $data['title_ar'],
            ':content_en' => $data['content_en'],
            ':content_ar' => $data['content_ar'],
            ':image_url' => $image_name,
            ':sort_order' => $data['sort_order'] ?? 0,
            ':is_active' => isset($data['is_active']) ? 1 : 0
        ]);
    }

    public function updateTab($id, $data, $file) {
        $image_name = $data['current_image'];
        if (isset($file) && $file['error'] == 0) {
            // Delete old image if it exists
            if (!empty($image_name) && file_exists($this->upload_dir . $image_name)) {
                unlink($this->upload_dir . $image_name);
            }
            $image_name = time() . '_' . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $this->upload_dir . $image_name);
        }

        $sql = "UPDATE about_tabs SET 
                    title_en = :title_en, title_ar = :title_ar, 
                    content_en = :content_en, content_ar = :content_ar, 
                    image_url = :image_url, sort_order = :sort_order, is_active = :is_active
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':title_en' => $data['title_en'],
            ':title_ar' => $data['title_ar'],
            ':content_en' => $data['content_en'],
            ':content_ar' => $data['content_ar'],
            ':image_url' => $image_name,
            ':sort_order' => $data['sort_order'] ?? 0,
            ':is_active' => isset($data['is_active']) ? 1 : 0
        ]);
    }

    public function deleteTab($id) {
        $tab = $this->getTabById($id);
        if ($tab && !empty($tab['image_url']) && file_exists($this->upload_dir . $tab['image_url'])) {
            unlink($this->upload_dir . $tab['image_url']);
        }
        $stmt = $this->conn->prepare("DELETE FROM about_tabs WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}