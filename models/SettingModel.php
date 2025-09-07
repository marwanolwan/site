<?php// filepath: C:/wamp64/www/site/models/SettingModel.php?>
<?php
class SettingModel {
    private $conn;
    private $db;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    // ======================================================
    // == الدوال التي يحتاجها الموقع الأمامي (Public Site) ==
    // ======================================================

    /**
     * (مهم) دالة جلب كل الإعدادات (مثل الألوان) للموقع الأمامي
     */
    public function getSettings() {
        $query = 'SELECT * FROM settings';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $settings_array = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings_array[$row['setting_key']] = $row['setting_value'];
        }
        return $settings_array;
    }

    /**
     * (مهم) دالة جلب كل الترجمات للغة معينة للموقع الأمامي
     */
    public function getTranslations($lang = 'ar') {
        $text_column = 'text_' . $lang; // text_ar or text_en
        $query = "SELECT text_key, {$text_column} as text FROM translations";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $translations_array = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $translations_array[$row['text_key']] = $row['text'];
        }
        return $translations_array;
    }


    // =================================================
    // == الدوال التي تحتاجها لوحة التحكم (Admin Panel) ==
    // =================================================

    /**
     * دالة لجلب كل بيانات الترجمة (عربي وإنجليزي) للوحة التحكم
     */
    public function getAllTranslations() {
        $query = 'SELECT id, text_key, text_ar, text_en FROM translations ORDER BY id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * دالة لتحديث كل الترجمات دفعة واحدة
     */
    public function updateTranslations($translations) {
        try {
            $this->conn->beginTransaction();

            $query = 'UPDATE translations SET text_ar = :text_ar, text_en = :text_en WHERE text_key = :text_key';
            $stmt = $this->conn->prepare($query);

            foreach ($translations as $key => $values) {
                $stmt->bindParam(':text_ar', $values['ar']);
                $stmt->bindParam(':text_en', $values['en']);
                $stmt->bindParam(':text_key', $key);
                $stmt->execute();
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    /**
     * دالة لتحديث كل الإعدادات (الألوان) دفعة واحدة (سنحتاجها في الخطوة التالية)
     */
    public function updateSettings($settings) {
         try {
            $this->conn->beginTransaction();
            
            $query = 'UPDATE settings SET setting_value = :setting_value WHERE setting_key = :setting_key';
            $stmt = $this->conn->prepare($query);

            foreach ($settings as $key => $value) {
                $stmt->bindParam(':setting_value', $value);
                $stmt->bindParam(':setting_key', $key);
                $stmt->execute();
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}