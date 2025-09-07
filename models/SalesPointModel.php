<?php// filepath: C:/wamp64/www/site/models/SalesPointModel.php?>
<?php
class SalesPointModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAllPoints() {
        $stmt = $this->conn->prepare("SELECT * FROM sales_points");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addPoint($name, $phone, $address, $latitude, $longitude) {
        $sql = "INSERT INTO sales_points (name, phone, address, latitude, longitude) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $phone, $address, $latitude, $longitude]);
    }

    public function clearAllPoints() {
        $stmt = $this->conn->prepare("TRUNCATE TABLE sales_points");
        return $stmt->execute();
    }
}