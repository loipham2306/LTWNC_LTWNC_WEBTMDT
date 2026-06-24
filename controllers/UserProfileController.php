<?php
require_once '../model/khachhang.php';
require_once '../model/Vouchers.php';
require_once '../model/TaiKhoan.php';
require_once '../model/DonHangModel.php';
require_once '../model/BinhLuanVaDanhGia.php';
class UserProfileController {
    private $db;
    private $binhLuanModel;
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
        $this->binhLuanModel = new BinhLuanVaDanhGia($db);
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
            case 'guiDanhGia':
                $this->xuLyGuiDanhGia();
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
        foreach ($danhSachDonHang as &$dh) {
            $dh['items'] = $this->donhang_model->getChiTietDonHang($dh['id_don_hang']);
        }
        $_SESSION['user']['vouchers'] = $danhSachVoucherCuaToi;
        
        include '../views/pages/UserProfile.php';
    }

    // Xử lý cập nhật thông tin cá nhân
    private function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?act=TaiKhoan");
            exit;
        }

        $id_tk = $_SESSION['user']['id_tai_khoan'];

        // 1. Gọi model gán dữ liệu
        $this->kh_model->setThongTin(
            $_POST['ho_ten_dem'], 
            $_POST['ten'], 
            $_POST['so_dien_thoai'], 
            $_POST['dia_chi'], 
            $_SESSION['user']['hang_thanh_vien'],
            $id_tk
        );
        
        // 2. Thực hiện update
        if ($this->kh_model->CapNhatThongTinKhachHang()) {
            // Cập nhật lại toàn bộ thông tin trong session để đồng bộ UI
            $_SESSION['user']['ho_ten_dem'] = $_POST['ho_ten_dem'];
            $_SESSION['user']['ten'] = $_POST['ten'];
            $_SESSION['user']['so_dien_thoai'] = $_POST['so_dien_thoai'];
            $_SESSION['user']['dia_chi'] = $_POST['dia_chi'];
            
            // Chuyển hướng sạch sẽ bằng PHP (không echo script)
            header("Location: index.php?act=UserProfile&status=success");
            exit;
        } else {
            // Thông báo lỗi nếu update thất bại (trường hợp DB lỗi)
            header("Location: index.php?act=UserProfile&status=error");
            exit;
        }
    }

    private function updatePassword() {
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?act=UserProfile");
            exit;
        }

        // Lấy ID từ session
        $id = $_SESSION['user']['id_tai_khoan'] ?? null;
        
        // Lấy dữ liệu từ form
        $oldPass = $_POST['old_password'] ?? '';
        $newPass = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        // 1. Kiểm tra xác nhận mật khẩu
        if ($newPass !== $confirm) {
            $_SESSION['error'] = "❌ Xác nhận mật khẩu mới không khớp!";
            header("Location: index.php?act=TaiKhoan");
            exit;
        }

        // 2. Lấy thông tin user từ Model
        $user = $this->tk_model->getTaiKhoanById($id); 

        // 3. Kiểm tra mật khẩu cũ
        if ($user && password_verify($oldPass, $user['mat_khau'])) {
            // 4. Mã hóa và cập nhật
            $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);
            
            if ($this->tk_model->updatePassword($id, $hashedPass)) {
                $_SESSION['success'] = "🎉 Đổi mật khẩu thành công!";
            } else {
                $_SESSION['error'] = "❌ Lỗi hệ thống, vui lòng thử lại sau!";
            }
        } else {
            $_SESSION['error'] = "❌ Mật khẩu hiện tại không đúng!";
        }

        header("Location: index.php?act=UserProfile");
        exit;
    }
    // Bình luận và đánh giá sản phẩm
    private function xuLyGuiDanhGia() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_kh = $_SESSION['user']['id_khach_hang']; // Đảm bảo trong session có thông tin này
            $id_sp = $_POST['id_san_pham'];
            $so_sao = $_POST['so_sao'];
            $noi_dung = $_POST['noi_dung'];

            if ($this->binhLuanModel->themBinhLuan($id_kh, $id_sp, $so_sao, $noi_dung)) {
                $_SESSION['success'] = "Cảm ơn bạn đã đánh giá sản phẩm!";
            } else {
                $_SESSION['error'] = "Đã có lỗi xảy ra khi gửi đánh giá.";
            }
            header("Location: index.php?act=UserProfile");
            exit;
        }
    }
}