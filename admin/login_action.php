<?php// filepath: C:/wamp64/www/site/admin/login_action.php?>
<?php
session_start();
require_once '../config/config.php'; // للوصول لملفات خارج مجلد admin
require_once '../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->connect();

    $username = $_POST['username'];
    $password = $_POST['password'];

    // البحث عن المستخدم
    $query = 'SELECT * FROM users WHERE username = :username';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // التحقق من كلمة المرور المشفرة
        if (password_verify($password, $user['password'])) {
            // كلمة المرور صحيحة، ابدأ الجلسة (session)
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['full_name'];
            
            // توجيه إلى لوحة التحكم الرئيسية
            header('Location: index.php');
            exit();
        }
    }

    // إذا فشل الدخول
    header('Location: index.php?error=1');
    exit();
}
?>