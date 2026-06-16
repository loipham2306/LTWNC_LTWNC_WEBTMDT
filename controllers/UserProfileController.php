<?php
require_once '../model/khachhang.php';
require_once '../model/Vouchers.php';
require_once '../model/TaiKhoan.php';
require_once '../model/DonHangModel.php';
class UserProfileController {
    private $db;
    private $kh_model;
    private $voucher_model;
    private $tk_model;
    private $donhang_model;
    public function __construct($db) {
        $this->db = $db;
        $this->kh_model = new khachhang($db);
        $this->voucher_model = new Vouchers($db);
        $this->tk_model = new TaiKhoan($db);
        $this->donhang_model = new DonHangModel($db);
        // Kiểm tra đăng nhập cho tất cả các hành động trong controller này
        if (!isset($_SESSION['user'])) {
            header("Location: ../views/pages/login.php");
            exit;
        }
    }

    public function handle($act) {
        switch ($act) {
            case 'UserProfile':
                $this->showProfile();
                break;
            case 'updateProfile':
                $this->updateProfile();
                break;
            case 'changePassword':
                $this->updatePassword();
                break;
            default:
                $this->showProfile();
                break;
        }
    }

    // Hiển thị trang profile và load dữ liệu cần thiết
    private function showProfile() {
    $id_tai_khoan = $_SESSION['user']['id_tai_khoan'];

    // 1. Lấy thông tin khách hàng (Hàm này trả về mảng thông tin có chứa id_khach_hang)
    $thongTinKhach = $this->kh_model->getThongTinByTaiKhoanId($id_tai_khoan);
    
    // ĐẢM BẢO BẠN LẤY ĐÚNG ID KHÁCH HÀNG TẠI ĐÂY
    $id_khach_hang = $thongTinKhach['id_khach_hang']; 

    // 2. Lấy danh sách Voucher và Đơn hàng theo ID khách hàng chuẩn
    $danhSachVoucherCuaToi = $this->voucher_model->layVoucherCuaTaiKhoan($id_tai_khoan);
    
    // SỬA DÒNG NÀY: Truyền $id_khach_hang thay vì $id_tai_khoan
    $danhSachDonHang = $this->donhang_model->getDonHangByKhachHangId($id_khach_hang);
    
    $_SESSION['user']['vouchers'] = $danhSachVoucherCuaToi;
    
    include '../views/pages/UserProfile.php';
}

    // Xử lý cập nhật thông tin cá nhân
    private function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user']['id_tai_khoan'];
            
            // Cập nhật thông qua model
            $this->kh_model->setThongTin(
                $_POST['ho_ten_dem'],
                $_POST['ten'],
                $_POST['so_dien_thoai'],
                $_POST['dia_chi'],
                $_SESSION['user']['hang_thanh_vien'], // Giữ nguyên hạng
                $id
            );

            if ($this->kh_model->CapNhatThongTinKhachHang()) {
                $_SESSION['success'] = "Cập nhật thông tin thành công!";
            } else {
                $_SESSION['error'] = "Cập nhật không thành công!";
            }
        }
        header("Location: index.php?act=UserProfile");
        exit;
    }

    // Xử lý đổi mật khẩu
    private function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user']['id_tai_khoan'];
            $oldPass = $_POST['oldPassword'];
            $newPass = $_POST['newPassword'];

            // 1. Kiểm tra mật khẩu cũ
            $user = $this->tk_model->getTaiKhoanById($id);
            if (password_verify($oldPass, $user['mat_khau'])) {
                // 2. Cập nhật mật khẩu mới
                $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);
                $this->tk_model->updatePassword($id, $hashedPass);
                $_SESSION['success'] = "Đổi mật khẩu thành công!";
            } else {
                $_SESSION['error'] = "Mật khẩu hiện tại không đúng!";
            }
        }
        header("Location: index.php?act=UserProfile");
        exit;
    }
}