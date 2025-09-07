<?php// filepath: C:/wamp64/www/site/models/ContactModel.php?>
<?php
class ContactModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function saveMessage($name, $email, $subject, $message) {
        $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $email, $subject, $message]);
    }

    public function getMessages() {
        $stmt = $this->conn->prepare("SELECT * FROM contact_messages ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}