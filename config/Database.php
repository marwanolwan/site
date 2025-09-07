<?php// filepath: C:/wamp64/www/site/config/Database.php?>
<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'site'; // تأكد من أن هذا هو اسم قاعدة بياناتك
    private $username = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8', $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            // من الأفضل إظهار رسالة عامة للمستخدم وتسجيل الخطأ التفصيلي
            error_log('Connection Error: ' . $e->getMessage());
            die('Database connection failed.');
        }
        return $this->conn;
    }
}