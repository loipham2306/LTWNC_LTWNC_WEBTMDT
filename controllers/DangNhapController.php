<?php
require_once '../model/taikhoan.php'; 
require_once '../model/khachhang.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class DangNhapController {
    private $db;
    private $tk_model;
    private $kh_model;

    public function __construct($db) {
        $this->db = $db;
        $this->tk_model = new taikhoan($db);
        $this->kh_model = new khachhang($db);
    }


    public function xuLyDangNhap() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../views/pages/login.php");
            exit;
        }

        $ten_dang_nhap = trim($_POST['username'] ?? '');
        $mat_khau_nhap = $_POST['password'] ?? '';

        if (empty($ten_dang_nhap) || empty($mat_khau_nhap)) {
            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
            header("Location: ../views/pages/login.php");
            exit;
        }

        $userRow = $this->tk_model->TimKiemTaiKhoan($ten_dang_nhap);

        if ($userRow) {
            if ($userRow['trang_thai'] == 0) {
                $_SESSION['error'] = "Tài khoản của bạn đã bị khóa!";
            } elseif (password_verify($mat_khau_nhap, $userRow['mat_khau'])) {
                
                $this->taoSessionUser($userRow);
                require_once '../controllers/GioHangController.php'; // Đảm bảo đã include controller
                $ghController = new GioHangController($this->db);
                $ghController->syncCartFromDB();
                $this->dieuHuong($userRow['vai_tro']);
                exit;
            } else {
                $_SESSION['error'] = "Mật khẩu không chính xác!";
            }
        } else {
            $_SESSION['error'] = "Tài khoản không tồn tại!";
        }

        header("Location: ../views/pages/login.php");
        exit;
    }

    private function taoSessionUser($userRow) {
        // 1. Luôn cố gắng lấy thông tin khách hàng từ bảng khach_hang
        $thongTinKhach = $this->kh_model->getThongTinByTaiKhoanId($userRow['id_tai_khoan']);
        
        // 2. Khởi tạo mảng dữ liệu với các giá trị mặc định
        $userData = [
            "id_tai_khoan"    => $userRow['id_tai_khoan'],
            "ten_dang_nhap"   => $userRow['ten_dang_nhap'],
            "vai_tro"         => $userRow['vai_tro'],
            // Lấy id_khach_hang nếu có (quan trọng nhất cho giỏ hàng)
            "id_khach_hang"   => $thongTinKhach['id_khach_hang'] ?? null, 
            "ten"             => $thongTinKhach['ten'] ?? 'Khách Hàng',
            "ho_ten_dem"      => $thongTinKhach['ho_ten_dem'] ?? '',
            "so_dien_thoai"   => $thongTinKhach['so_dien_thoai'] ?? '',
            "dia_chi"         => $thongTinKhach['dia_chi'] ?? '',
            "hang_thanh_vien" => $thongTinKhach['hang_thanh_vien'] ?? 'silver',
            "email"           => $thongTinKhach['email'] ?? ''
        ];
        
        $_SESSION['user'] = $userData;
    }    
    private function dieuHuong($vai_tro) {
        // Ép kiểu về lowercase và bỏ khoảng trắng để so sánh chính xác
        $v = trim(strtolower($vai_tro));
        
        if ($v === 'admin') {
           header("Location: /LTWNC_LTWNC_WEBTMDT/controllers/index.php?act=admin_dashboard");
            exit;
        } elseif ($v === 'nhanvien') {
            header("Location: ../views/pages/employee/orders.php");
        }elseif ($v === 'khach hang') {
            header("Location: /LTWNC_LTWNC_WEBTMDT/controllers/index.php");
        } 
        else {
            header("Location: /LTWNC_LTWNC_WEBTMDT/controllers/index.php");
        }
        exit;
    }
}