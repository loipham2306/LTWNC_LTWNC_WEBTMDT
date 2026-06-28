<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController {
    public static function sendOTP($toEmail, $otp) {
        $mail = new PHPMailer(true);
    
        try {
            $mail->isSMTP();
            
            // THÊM DÒNG NÀY ĐỂ FIX LỖI FONT TIẾNG VIỆT
            $mail->CharSet = 'UTF-8'; 
            
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'luan14102005in@gmail.com'; 
            $mail->Password   = 'mgdr dyon rgnz yxay';    
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->setFrom('luan14102005in@gmail.com', 'TramHieuShop');
            error_log("Đang gửi mail tới địa chỉ: " . $toEmail);
            $mail->addAddress($toEmail);
            
            $mail->isHTML(true);
            $mail->Subject = 'Mã xác thực OTP của bạn';
            $mail->Body    = "Xin chào, mã xác thực OTP của bạn là: <b style='color:#F28B00; font-size: 18px;'>$otp</b>. Mã có hiệu lực trong 5 phút.";
            $mail->send();
            return true;
        } catch (Exception $e) {
            $errorMsg = "Lỗi hệ thống: " . $e->getMessage() . " - Chi tiết: " . $mail->ErrorInfo;
            error_log($errorMsg);
            echo "<div class='alert alert-danger'>$errorMsg</div>"; 
            exit();
        }
    }
    // THÊM HÀM NÀY VÀO TRONG CLASS MailController
    public static function sendResetLink($emailNhan, $tenNguoiDung, $linkReset) {
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        try {
            // Lợi copy y chang 5 dòng cấu hình (Host, SMTPAuth, Username, Password, Port) 
            // từ hàm sendOTP hiện tại của Lợi thả vào đây nhé!
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = '...'; // Copy từ sendOTP xuống
            $mail->Password   = '...'; // Copy từ sendOTP xuống
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($mail->Username, 'Hệ Thống Trạm Hiệu'); // Tên người gửi
            $mail->addAddress($emailNhan, $tenNguoiDung);

            $mail->isHTML(true);
            $mail->Subject = 'Yêu cầu đặt lại mật khẩu - Trạm Hiệu';
            $mail->Body    = "Chào <b>{$tenNguoiDung}</b>,<br><br>
                              Bạn vừa yêu cầu đặt lại mật khẩu. Vui lòng click vào link dưới đây để thiết lập mật khẩu mới (Link có hiệu lực 15 phút):<br><br>
                              <a href='{$linkReset}' style='background: #F28B00; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>ĐẶT LẠI MẬT KHẨU</a><br><br>
                              Nếu bạn không yêu cầu, vui lòng bỏ qua email này.";

            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
    public static function sendOrderConfirmation($toEmail, $donHang, $chiTiet, $tong_tien, $giam)    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            
            // THÊM DÒNG NÀY ĐỂ FIX LỖI FONT TIẾNG VIỆT
            $mail->CharSet = 'UTF-8';
            
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
                <table border='1' cellpadding='8' cellspacing='0' width='100%' style='border-collapse: collapse;'>
                    <tr style='background-color: #f8f9fa;'>
                        <th>Hình</th>
                        <th>Tên</th>
                        <th>SL</th>
                        <th>Giá</th>
                    </tr>";

            $tong = 0;

            foreach ($chiTiet as $sp) {
                $tien = $sp['so_luong'] * $sp['gia_luc_mua'];
                $tong += $tien;
                
                // Thay bằng tên miền thật khi đưa web lên Hosting
                $baseUrl = "http://127.0.0.1/LTWNC_LTWNC_WEBTMDT/";
                
                // FIX LOGIC LẤY ẢNH: Phân biệt ảnh biến thể và ảnh gốc
                $img = '';
                if (!empty($sp['hinh_anh_bien_the'])) {
                    $img = $baseUrl . 'assets/images/products/Bien_The_Products/' . $sp['hinh_anh_bien_the'];
                } elseif (!empty($sp['hinh_anh'])) {
                    $img = $baseUrl . 'assets/images/products/' . $sp['hinh_anh'];
                }

                $html .= "
                    <tr style='text-align: center;'>
                        <td><img src='{$img}' width='60' alt='Hình SP'></td>
                        <td style='text-align: left;'>{$sp['ten_san_pham']}</td>
                        <td>{$sp['so_luong']}</td>
                        <td style='color: #F28B00; font-weight: bold;'>" . number_format($tien, 0, ',', '.') . " đ</td>
                    </tr>
                ";
            }

            $html .= "
                </table>
               <h3 style='margin-top:15px; text-align: right;'>
                    <span style='font-size: 14px; font-weight: normal; color: #333;'>Tổng gốc: " . number_format($tong, 0, ',', '.') . " đ </span><br>
                    <span style='font-size: 14px; font-weight: normal; color: #dc3545;'>Giảm giá: -" . number_format($giam, 0, ',', '.') . " đ </span><br>
                    <span style='color:#F28B00; font-size: 18px;'>Tổng thanh toán: " . number_format($tong_tien, 0, ',', '.') . " đ</span>
                </h3>

                <div style='background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 20px;'>
                    <h3 style='margin-top: 0;'>Thông tin giao hàng:</h3>
                    <p style='margin-bottom: 5px;'><b>Người nhận:</b> {$donHang['ten_nguoi_nhan']}</p>
                    <p style='margin-bottom: 5px;'><b>SĐT:</b> {$donHang['sdt_nguoi_nhan']}</p>
                    <p style='margin-bottom: 0;'><b>Địa chỉ:</b> {$donHang['dia_chi_giao_hang']}</p>
                </div>
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