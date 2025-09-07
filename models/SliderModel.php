<?php// filepath: C:/wamp64/www/site/models/SliderModel.php?>
<?php
class SliderModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getSlides($only_active = false) {
        $sql = "SELECT * FROM hero_slides";
        if ($only_active) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY slide_order ASC, id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSlideById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM hero_slides WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function addSlide($data) {
        $stmt = $this->conn->prepare("INSERT INTO hero_slides (image_url, title_ar, title_en, subtitle_ar, subtitle_en, slide_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['image_url'],
            $data['title_ar'],
            $data['title_en'],
            $data['subtitle_ar'],
            $data['subtitle_en'],
            $data['slide_order'],
            $data['is_active']
        ]);
    }

    public function deleteSlide($id) {
        $stmt = $this->conn->prepare("DELETE FROM hero_slides WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>