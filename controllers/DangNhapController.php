<?php
require_once '../model/taikhoan.php'; 
require_once '../model/khachhang.php';

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
        $userData = [
            "id_tai_khoan"   => $userRow['id_tai_khoan'],
            "ten_dang_nhap"  => $userRow['ten_dang_nhap'],
            "vai_tro"        => $userRow['vai_tro'],
            "ten"            => ($userRow['vai_tro'] === 'admin') ? 'Admin' : 'Khách Hàng',
            "ho_ten_dem"     => "", // RESET VỀ RỖNG
            "so_dien_thoai"  => "", // RESET VỀ RỖNG
            "dia_chi"        => "", // RESET VỀ RỖNG
            "hang_thanh_vien"=> ""  // RESET VỀ RỖNG
        ];

        if ($userRow['vai_tro'] === 'khach hang') {
            $thongTinKhach = $this->kh_model->getThongTinByTaiKhoanId($userRow['id_tai_khoan']);
           if ($thongTinKhach) {
                // 1. Lấy thông tin cơ bản
                $userData['so_dien_thoai']   = $thongTinKhach['sdt'] ?? '';
                $userData['dia_chi']         = $thongTinKhach['diachi'] ?? '';
                $userData['hang_thanh_vien'] = $thongTinKhach['hang_thanh_vien'] ?? 'silver';

                // 2. Tách tên theo logic cũ của bạn
                $ten_day_du = trim($thongTinKhach['ten_day_du'] ?? '');
                if (!empty($ten_day_du)) {
                    $parts = explode(' ', $ten_day_du);
                    
                    // Lấy từ cuối cùng làm "Tên"
                    $userData['ten'] = array_pop($parts); 
                    
                    // Lấy các từ còn lại làm "Họ và tên đệm"
                    $userData['ho_ten_dem'] = implode(' ', $parts); 
                } else {
                    $userData['ten'] = 'Khách Hàng';
                    $userData['ho_ten_dem'] = '';
                }
            }
        }
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