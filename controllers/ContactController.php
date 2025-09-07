<?php// filepath: C:/wamp64/www/site/controllers/ContactController.php?>
<?php
// تضمين مكتبة PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// تضمين ملفات Composer
require 'vendor/autoload.php';

require_once 'controllers/BaseController.php';
require_once 'models/ContactModel.php';

class ContactController extends BaseController {
    
    public function index() {
        $page_data = [
            'form_sent' => false,
            'form_error' => false,
            'error_message' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $subject = trim($_POST['subject']);
            $message = trim($_POST['message']);

            if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
                $contactModel = new ContactModel();
                $contactModel->saveMessage($name, $email, $subject, $message);

                // --- (الكود الجديد لإرسال البريد الإلكتروني باستخدام PHPMailer) ---
                $mail = new PHPMailer(true);

                try {
                    // إعدادات السيرفر
                    // $mail->SMTPDebug = 2; // لتفعيل وضع تصحيح الأخطاء المفصل
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'your-gmail-address@gmail.com'; // ضع بريدك الإلكتروني في Gmail هنا
                    $mail->Password   = 'your-gmail-app-password';      // ضع كلمة مرور التطبيقات المكونة من 16 حرفًا هنا
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;
                    $mail->CharSet    = 'UTF-8';

                    // المستلمون
                    $mail->setFrom($email, $name); // البريد الوارد من النموذج
                    $mail->addAddress($this->data['settings']['contact_form_email']); // البريد الذي سيستقبل الرسالة
                    $mail->addReplyTo($email, $name);

                    // المحتوى
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    
                    $email_body = "<html><body>";
                    $email_body .= "<h2>رسالة جديدة من موقع الصافي</h2>";
                    $email_body .= "<p><strong>الاسم:</strong> {$name}</p>";
                    $email_body .= "<p><strong>البريد الإلكتروني:</strong> {$email}</p>";
                    $email_body .= "<p><strong>الموضوع:</strong> {$subject}</p>";
                    $email_body .= "<hr><p><strong>الرسالة:</strong><br>" . nl2br($message) . "</p>";
                    $email_body .= "</body></html>";
                    $mail->Body    = $email_body;

                    $mail->send();
                    $page_data['form_sent'] = true;

                } catch (Exception $e) {
                    $page_data['form_error'] = true;
                    // لعرض الخطأ الفعلي أثناء التطوير فقط
                    $page_data['error_message'] = "فشل إرسال الرسالة. خطأ: {$mail->ErrorInfo}";
                }
                // --- (نهاية الكود الجديد) ---

            } else {
                $page_data['form_error'] = true;
                $page_data['error_message'] = "يرجى تعبئة جميع الحقول المطلوبة بشكل صحيح.";
            }
        }
        
        $this->render('contact', $page_data);
    }
}