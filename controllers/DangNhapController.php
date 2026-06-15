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
                // Chuyển hướng theo vai trò
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
        // 1. Xử lý tên: Ưu tiên lấy Tên thật trong DB (bảng tai_khoan). 
        // Nếu trống thì mới lấy chữ "Khách Hàng" để tránh bị dính chữ.
        $vai_tro_chu_thuong = trim(strtolower($userRow['vai_tro']));
        $ten_mac_dinh = ($vai_tro_chu_thuong === 'admin') ? 'Admin' : 'Khách Hàng';
        $ten_hien_tai = !empty($userRow['ten']) ? trim($userRow['ten']) : $ten_mac_dinh;

        // 2. Lấy TRỰC TIẾP dữ liệu từ bảng tai_khoan mà ta vừa thêm cột lúc nãy
        $userData = [
            "id_tai_khoan"   => $userRow['id_tai_khoan'],
            "ten_dang_nhap"  => $userRow['ten_dang_nhap'],
            "vai_tro"        => $userRow['vai_tro'],
            "ho_ten_dem"     => $userRow['ho_ten_dem'] ?? "",
            "ten"            => $ten_hien_tai,
            "so_dien_thoai"  => $userRow['so_dien_thoai'] ?? "",
            "dia_chi"        => $userRow['dia_chi'] ?? "",
            "hang_thanh_vien"=> "Thành Viên Mới" 
        ];

        // 3. Nếu là khách hàng, chỉ lấy thêm "Hạng Thành Viên" từ bảng khachhang (nếu có dùng)
        if ($vai_tro_chu_thuong === 'khach hang' || $vai_tro_chu_thuong === 'khach_hang') {
            $thongTinKhach = $this->kh_model->getThongTinByTaiKhoanId($userRow['id_tai_khoan']);
            if ($thongTinKhach) {
                // Lấy hạng
                $userData['hang_thanh_vien'] = $thongTinKhach['hang_thanh_vien'] ?? 'Thành Viên Mới';
                
                // Backup: Nếu bảng tai_khoan đang trống mà bảng khachhang lại có sdt/địa chỉ thì lấy bù qua
                if (empty($userData['so_dien_thoai'])) {
                    $userData['so_dien_thoai'] = $thongTinKhach['sdt'] ?? '';
                }
                if (empty($userData['dia_chi'])) {
                    $userData['dia_chi'] = $thongTinKhach['diachi'] ?? '';
                }
            }
        }
        
        // 4. Lưu vào Session
        $_SESSION['user'] = $userData;
    }

    private function dieuHuong($vai_tro) {
        $v = trim(strtolower($vai_tro));
        
        if ($v === 'admin') {
           header("Location: /LTWNC_LTWNC_WEBTMDT/controllers/index.php?act=admin_dashboard");
        } elseif ($v === 'nhanvien') {
            header("Location: ../views/pages/employee/orders.php");
        } elseif ($v === 'khach hang' || $v === 'khach_hang') {
            header("Location: /LTWNC_LTWNC_WEBTMDT/controllers/index.php");
        } else {
            header("Location: /LTWNC_LTWNC_WEBTMDT/controllers/index.php");
        }
        exit;
    }
}
?>