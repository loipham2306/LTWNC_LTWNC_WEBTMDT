<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController {
    public static function sendOTP($toEmail, $otp) {
        $mail = new PHPMailer(true);
    
        try {
            // Cấu hình máy chủ SMTP
            $mail->isSMTP();
            
            $mail->Host       = 'smtp.gmail.com'; // SMTP của Gmail
            $mail->SMTPAuth   = true;
            $mail->Username   = 'luan14102005in@gmail.com'; // Email của bạn
            $mail->Password   = 'mgdr dyon rgnz yxay';    // Mật khẩu ứng dụng (App Password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->setFrom('luan14102005in@gmail.com', 'TramHieuShop');
            error_log("Đang gửi mail tới địa chỉ: " . $toEmail);
            $mail->addAddress($toEmail);
            // Nội dung email
            $mail->isHTML(true);
            $mail->Subject = 'Ma xac thuc OTP cua ban';
            $mail->Body    = "Xin chào, mã xác thực OTP của bạn là: <b>$otp</b>. Mã có hiệu lực trong 5 phút.";
            $mail->send();
            return true;
        } catch (Exception $e) {
            // Sửa thành:
            $errorMsg = "Lỗi hệ thống: " . $e->getMessage() . " - Chi tiết: " . $mail->ErrorInfo;
            error_log($errorMsg);
            echo "<div class='alert alert-danger'>$errorMsg</div>"; 
            exit();
        }
    }
}
?>