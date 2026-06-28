<?php
class QuenMatKhauController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function handle($act) {
        switch ($act) {
            case 'QuenMatKhau': // 1. Hiện form nhập email
                include '../views/pages/ForgotPassword.php';
                break;
            case 'XuLyQuenMatKhau': // 2. Tạo OTP, gửi mail và hiện form OTP
                $this->xuLyGuiOTP();
                break;
            case 'XacNhanOTPQuenMat': // 3. Kiểm tra mã OTP
                $this->xuLyXacNhanOTP();
                break;
            case 'FormDatLaiMatKhau': // 4. Hiện form nhập mật khẩu mới
                include '../views/pages/ResetPassword.php';
                break;
            case 'XuLyDatLaiMatKhau': // 5. Lưu mật khẩu mới vào DB
                $this->xuLyLuuMatKhauMoi();
                break;
        }
    }

    private function xuLyGuiOTP() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            
            // 1. Kiểm tra email có trong DB không
            $stmt = $this->db->prepare("SELECT id_tai_khoan FROM tai_khoan WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            
            if ($stmt->fetch()) {
                // 2. Tạo OTP và lưu vào Session
                $otp = rand(100000, 999999);
                $_SESSION['reset_email'] = $email; 
                $_SESSION['reset_otp'] = $otp;
                $_SESSION['reset_otp_expiry'] = time() + 300; // Hết hạn sau 5 phút

                // 3. Gọi hàm gửi OTP có sẵn của bạn Lợi
                require_once 'MailController.php';
                $isSent = MailController::sendOTP($email, $otp);

                if ($isSent) {
                    // Chuyển thẳng sang giao diện nhập OTP
                    include '../views/pages/ForgotOTP.php';
                    exit();
                } else {
                    $_SESSION['error'] = "Không thể gửi OTP. Vui lòng kiểm tra lại cấu hình mail!";
                }
            } else {
                $_SESSION['error'] = "Email này chưa được đăng ký trong hệ thống!";
            }
            header("Location: index.php?act=QuenMatKhau");
            exit();
        }
    }

    private function xuLyXacNhanOTP() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $user_otp = trim($_POST['otp_input'] ?? '');
            
            if (isset($_SESSION['reset_otp']) && $user_otp == $_SESSION['reset_otp']) {
                if (time() < $_SESSION['reset_otp_expiry']) {
                    // Mã đúng và còn hạn -> Chuyển sang form đặt mật khẩu mới
                    header("Location: index.php?act=FormDatLaiMatKhau");
                    exit();
                } else {
                    $_SESSION['error'] = "Mã OTP đã hết hạn!";
                }
            } else {
                $_SESSION['error'] = "Mã OTP không chính xác!";
            }
            // Sai thì quay lại trang nhập OTP
            include '../views/pages/ForgotOTP.php';
            exit();
        }
    }

    private function xuLyLuuMatKhauMoi() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $matKhauMoi = trim($_POST['mat_khau_moi']);
            $email = $_SESSION['reset_email'] ?? '';

            if (empty($email)) {
                $_SESSION['error'] = "Phiên làm việc đã hết hạn, vui lòng làm lại từ đầu!";
                header("Location: index.php?act=QuenMatKhau");
                exit();
            }

            // Mã hóa mật khẩu
            $hashedPassword = password_hash($matKhauMoi, PASSWORD_DEFAULT);

            // Cập nhật vào DB
            $stmt = $this->db->prepare("UPDATE tai_khoan SET mat_khau = :pass WHERE email = :email");
            if ($stmt->execute(['pass' => $hashedPassword, 'email' => $email])) {
                // Xóa session dọn dẹp
                unset($_SESSION['reset_email'], $_SESSION['reset_otp'], $_SESSION['reset_otp_expiry']);
                
                // 🔥 BẬT CỜ BÁO THÀNH CÔNG VÀ NẰM LẠI TRANG ĐỂ HIỆN POPUP
                $_SESSION['reset_success'] = true;
                header("Location: index.php?act=FormDatLaiMatKhau");
            } else {
                $_SESSION['error'] = "Lỗi khi lưu mật khẩu mới!";
                header("Location: index.php?act=FormDatLaiMatKhau");
            }
            exit();
        }
    }
}
?>