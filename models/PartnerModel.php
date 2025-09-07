<?php// filepath: C:/wamp64/www/site/models/PartnerModel.php?>
<?php
class PartnerModel {
    private $conn;
    private $db;
    private $upload_dir = ROOT_PATH . '/assets/images/partners/';

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
        if (!is_dir($this->upload_dir)) {
            mkdir($this->upload_dir, 0777, true);
        }
    }

    /**
     * Get all partners from the database
     */
    public function getPartners() {
        $query = 'SELECT * FROM partners ORDER BY id DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Add a new partner to the database
     */
    public function addPartner($data, $file) {
        if ($file['error'] !== 0) {
            return false; // No file uploaded or error
        }

        $logo_name = time() . '_' . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $this->upload_dir . $logo_name)) {
            $query = 'INSERT INTO partners (name, website_url, logo_url) VALUES (:name, :website_url, :logo_url)';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':website_url', $data['website_url']);
            $stmt->bindParam(':logo_url', $logo_name);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Delete a partner from the database and remove their logo file
     */
    public function deletePartner($id) {
        // First, get the logo filename to delete it
        $query = 'SELECT logo_url FROM partners WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $partner = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($partner) {
            $logo_path = $this->upload_dir . $partner['logo_url'];
            if (file_exists($logo_path)) {
                unlink($logo_path); // Delete the logo file
            }
        }

        // Now delete the record from the database
        $query = 'DELETE FROM partners WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}