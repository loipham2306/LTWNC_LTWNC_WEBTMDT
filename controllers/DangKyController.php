<?php
include_once '../model/khachhang.php';
include_once '../model/TaiKhoan.php';
include_once '../controllers/MailController.php';
class DangKyController {
private $modelTaiKhoan;
    private $modelKhachHang;
    private $db;
    public function handle($act) {
        switch ($act) {
            case 'Register':
                include '../views/pages/Register.php'; 
                break;
            case 'xulydangky':
                $this->xuLyDangKyStep1();
                break;
            case 'form_otp':
                include '../views/components/form_otp.php'; 
                break;
            case 'xacnhanotp':
                $this->xuLyOTP();
                break;
            case 'form_matkhau':
                include '../views/components/form_matkhau.php'; 
                break;
            case 'hoantatdangky':
                $this->xuLyHoanTat();
                break;
                
            default:
                // Xử lý mặc định nếu không khớp act nào
                echo "Action không tồn tại!";
                break;
        }
    }
    public function __construct($db) {
        $this->db = $db;
        $this->modelTaiKhoan = new TaiKhoan($db);
        $this->modelKhachHang = new KhachHang($db);
    }

    public function xuLyDangKyStep1() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lưu thông tin từ form vào session
            $email = $_POST['email'];
            $sdt = $_POST['so_dien_thoai'];

            // 1. Kiểm tra tồn tại trong DB (sử dụng Model bạn đã có)
            // Lưu ý: Giả sử modelTaiKhoan có hàm checkUserExists trả về true nếu trùng
            if ($this->modelTaiKhoan->checkUserExists($email, $sdt)) {
                echo "<div class='alert alert-danger text-center'>
                        Email hoặc SĐT đã tồn tại. 
                        <a href='index.php?act=forgot_password' class='text-white fw-bold'>Quên mật khẩu?</a>
                    </div>
                    <button onclick='window.location.reload()' class='btn btn-secondary w-100 mt-2'>Quay lại</button>";
                exit();
            }
            // Tạo mã OTP
            $_SESSION['temp_data'] = $_POST; 
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_expiry'] = time() + 300;

            // Gọi hàm gửi mail từ MailController
            $isSent = MailController::sendOTP($_POST['email'], $otp);
            
            if ($isSent) {
                include '../views/components/form_otp.php';
                exit();
            } else {
                $_SESSION['error'] = "Không thể gửi email xác thực!";
                header("Location: index.php?act=Register");
                exit();
            }
        }
    }

    // Bước 2: Kiểm tra OTP
    public function xuLyOTP() {
        if (!isset($_SESSION['temp_data'])) {
            include '../views/pages/register.php';
            exit();
        }
        $user_otp = $_POST['otp_input'];
        if ($user_otp == $_SESSION['otp'] && time() < $_SESSION['otp_expiry']) {
            include '../views/components/form_matkhau.php';
        } else {
            $_SESSION['error'] = "Mã OTP sai hoặc đã hết hạn!";
           include '../views/components/form_otp.php';
        }
    }

    // Bước 3: Hoàn tất đăng ký (Lưu vào 2 bảng)
    public function xuLyHoanTat() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kiểm tra session
            if (!isset($_SESSION['temp_data'])) {
                echo "<div class='alert alert-danger'>Phiên làm việc đã hết hạn. Vui lòng đăng ký lại!</div>";
                exit();
            }

            $data = $_SESSION['temp_data'];
            
            // Kiểm tra mật khẩu
            if ($_POST['password'] !== $_POST['confirm_password']) {
                echo "<div class='alert alert-danger'>Mật khẩu xác nhận không khớp!</div>";
                exit();
            }

            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            try {
                $this->db->beginTransaction();

                $username = $_POST['username'];
                $id_tk = $this->modelTaiKhoan->themTaiKhoan($username, $data['email'], $password);
                
                if (!$id_tk) {
                    throw new Exception("Lỗi tạo tài khoản!");
                }

                // 2. Lưu khách hàng
                $this->modelKhachHang->themKhachHang(
                    $id_tk, 
                    $data['ho_ten_dem'], 
                    $data['ten'], 
                    $data['so_dien_thoai'], 
                    $data['dia_chi']
                );

                $this->db->commit();
                $_SESSION['user_id'] = $id_tk; // Lưu ID tài khoản vào session
                // Có thể lưu thêm các thông tin khác nếu cần
                $_SESSION['username'] = $username; 
                $_SESSION['role'] = 'khach hang';
                // Xóa session
                unset($_SESSION['temp_data'], $_SESSION['otp'], $_SESSION['otp_expiry']);
                
                // Trả về thông báo thành công
                echo "<div class='text-center text-white'>
                        <i class='fas fa-check-circle text-success' style='font-size: 50px;'></i>
                        <h3 class='mt-3'>Đăng ký thành công!</h3>
                        <a href='index.php?act=login' class='btn btn-success mt-3'>Đăng nhập ngay</a>
                    </div>";
                    
            } catch (Exception $e) {
                $this->db->rollBack();
                echo "<div class='alert alert-danger'>Có lỗi xảy ra: " . $e->getMessage() . "</div>";
            }
        }
    }
}
?>