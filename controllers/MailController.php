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
    public static function sendOrderConfirmation($toEmail, $donHang, $chiTiet, $tong_tien, $giam)    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'luan14102005in@gmail.com';
            $mail->Password   = 'mgdr dyon rgnz yxay';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('luan14102005in@gmail.com', 'TramHieuShop');
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->Subject = "Xác nhận đơn hàng #" . $donHang['id_don_hang'];

            // ===== HTML EMAIL =====
            $html = "
            <div style='font-family:Arial; padding:10px'>
                <h2 style='color:#F28B00'>Đặt hàng thành công</h2>
                <p>Mã đơn: <b>#{$donHang['id_don_hang']}</b></p>
                <hr>
                <h3>Chi tiết sản phẩm:</h3>
                <table border='1' cellpadding='8' cellspacing='0' width='100%'>
                    <tr>
                        <th>Hình</th>
                        <th>Tên</th>
                        <th>SL</th>
                        <th>Giá</th>
                    </tr>";

            $tong = 0;

            foreach ($chiTiet as $sp) {
                $tien = $sp['so_luong'] * $sp['gia_luc_mua'];
                $tong += $tien;
                $imgFile = $sp['hinh_anh_bien_the']
                    ?? $sp['hinh_anh']
                    ?? '';

                $img = $imgFile
                    ? '/LTWNC_LTWNC_WEBTMDT/assets/images/products/Bien_The_Products/' . $imgFile
                    : '';
                $html .= "
                    <tr>
                        <td><img src='{$img}' width='60'></td>
                        <td>{$sp['ten_san_pham']}</td>
                        <td>{$sp['so_luong']}</td>
                        <td>" . number_format($tien, 0, ',', '.') . " đ</td>
                    </tr>
                ";
            }

            $html .= "
                </table>
               <h3 style='margin-top:10px;color:#F28B00'>
                    Tổng gốc: " . number_format($tong, 0, ',', '.') . " đ <br>
                    Giảm giá: -" . number_format($giam, 0, ',', '.') . " đ <br>
                    Tổng thanh toán: " . number_format($tong_tien, 0, ',', '.') . " đ
                </h3>

                <h3>Thông tin giao hàng:</h3>
                <p>
                    {$donHang['ten_nguoi_nhan']}<br>
                    {$donHang['sdt_nguoi_nhan']}<br>
                    {$donHang['dia_chi_giao_hang']}
                </p>
            </div>";

            $mail->Body = $html;
            $mail->send();

            return true;

        } catch (Exception $e) {
            error_log("Mail order error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
?>