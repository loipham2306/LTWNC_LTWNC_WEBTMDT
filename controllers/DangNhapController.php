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
        $this->tk_model = new TaiKhoan($db);
        $this->kh_model = new khachhang($db);
    }

    public function xuLyDangNhap() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /LTWNC_LTWNC_WEBTMDT/controllers/index.php?act=Login");
            exit;
        }

        $ten_dang_nhap = trim($_POST['username'] ?? '');
        $mat_khau_nhap = $_POST['password'] ?? '';

        if (empty($ten_dang_nhap) || empty($mat_khau_nhap)) {
            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
            header("Location: /LTWNC_LTWNC_WEBTMDT/controllers/index.php?act=Login");
            exit;
        }

        $userRow = $this->tk_model->TimKiemTaiKhoan($ten_dang_nhap);

        if ($userRow) {
            if ($userRow['trang_thai'] == 0) {
                $_SESSION['error'] = "Tài khoản của bạn đã bị khóa!";
            } elseif (password_verify($mat_khau_nhap, $userRow['mat_khau'])) {
                
                // 1. Lưu Session
                $this->taoSessionUser($userRow);
                
                // 2. Đồng bộ giỏ hàng
                require_once '../controllers/GioHangController.php'; 
                $ghController = new GioHangController($this->db);
                $ghController->syncCartFromDB();
                
                // 3. Chuyển hướng
                $this->dieuHuong($userRow['vai_tro']);
                exit;
            } else {
                $_SESSION['error'] = "Mật khẩu không chính xác!";
            }
        } else {
            $_SESSION['error'] = "Tài khoản không tồn tại!";
        }

        header("Location: /LTWNC_LTWNC_WEBTMDT/controllers/index.php?act=Login");
        exit;
    }

    private function taoSessionUser($userRow) {
        $vai_tro_chu_thuong = trim(strtolower($userRow['vai_tro']));
        $ten_mac_dinh = ($vai_tro_chu_thuong === 'admin') ? 'Admin' : 'Khách Hàng';
        $ten_hien_tai = !empty($userRow['ten']) ? trim($userRow['ten']) : $ten_mac_dinh;

        // GOM CHUNG VÀO 1 MẢNG DUY NHẤT ĐỂ KHÔNG BỊ GHI ĐÈ
        $userData = [
            "id_tai_khoan"   => $userRow['id_tai_khoan'],
            "ten_dang_nhap"  => $userRow['ten_dang_nhap'],
            "vai_tro"        => $userRow['vai_tro'],
            "ho_ten_dem"     => $userRow['ho_ten_dem'] ?? "",
            "ten"            => $ten_hien_tai,
            "so_dien_thoai"  => $userRow['so_dien_thoai'] ?? "",
            "dia_chi"        => $userRow['dia_chi'] ?? "",
            "email"          => $userRow['email'] ?? "",
            "hang_thanh_vien"=> "Thành Viên Mới",
            "id_khach_hang"  => null // Sẽ cập nhật ở dưới nếu là user
        ];

        // Nếu là khách hàng, lấy thêm dữ liệu phụ từ bảng khach_hang
        if ($vai_tro_chu_thuong === 'khach hang' || $vai_tro_chu_thuong === 'khach_hang') {
            $thongTinKhach = $this->kh_model->getThongTinByTaiKhoanId($userRow['id_tai_khoan']);
            if ($thongTinKhach) {
                // Lấy ID khách hàng (rất quan trọng cho đồng bộ giỏ hàng)
                $userData['id_khach_hang'] = $thongTinKhach['id_khach_hang'] ?? null;
                $userData['hang_thanh_vien'] = $thongTinKhach['hang_thanh_vien'] ?? 'Thành Viên Mới';
                
                // Lấy bù qua nếu bên bảng tai_khoan đang trống SĐT hoặc Địa chỉ
                if (empty($userData['so_dien_thoai'])) {
                    $userData['so_dien_thoai'] = $thongTinKhach['so_dien_thoai'] ?? $thongTinKhach['sdt'] ?? '';
                }
                if (empty($userData['dia_chi'])) {
                    $userData['dia_chi'] = $thongTinKhach['dia_chi'] ?? $thongTinKhach['diachi'] ?? '';
                }
            }
        }
        
        $_SESSION['user'] = $userData;
    }    

    private function dieuHuong($vai_tro) {
        $v = trim(strtolower($vai_tro));
        
        if ($v === 'admin') {
           header("Location: /LTWNC_LTWNC_WEBTMDT/controllers/index.php?act=admin_dashboard");
        } elseif ($v === 'nhanvien') {
            header("Location: ../views/pages/employee/orders.php");
        } else {
            header("Location: /LTWNC_LTWNC_WEBTMDT/controllers/index.php");
        }
        exit;
    }
}
?>