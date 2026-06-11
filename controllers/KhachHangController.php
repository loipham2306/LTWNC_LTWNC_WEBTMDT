<?php
include_once '../model/khachhang.php';
include_once '../model/TaiKhoan.php';
class KhachHangController {
    private $db;
    private $kh_model;
    private $tk_model;
    public function __construct($db) {
        $this->db = $db;
        $this->kh_model = new khachhang($db);
        $this->tk_model= new TaiKhoan($db);
    }

    // Điểm tiếp nhận hành động
    public function handle($act) {
        switch ($act) {
            case 'QuanLyKhachHang':
                $this->danhSach();
                break;
            case 'updateKH':
                $this->xuLyCapNhat();
                break;
            case 'detailKH':
                $this->layChiTiet();
                break;
            case 'toggleStatus':
                $this->xuLyKhoaMo();
                break;
            case 'deleteKH':
                $this->xuLyXoa();
                break;
            default:
                $this->danhSach();
                break;
        }
    }
    private function xuLyXoa() {
        $id = $_GET['id_tai_khoan'] ?? 0;
        if ($id > 0) {
            // Gọi hàm xóa mềm thay cho xóa vật lý
            if ($this->tk_model->softDeleteTaiKhoan($id)) {
                $_SESSION['success'] = "Đã ẩn tài khoản thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi xóa!";
            }
        }
        header("Location: index.php?act=QuanLyKhachHang");
        exit();
    }
    private function danhSach() {
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Tính toán lại
        $totalCustomers = $this->kh_model->countAllKhachHang();
        $totalPages = ceil($totalCustomers / $limit);
        
        $offset = ($page - 1) * $limit; // Đây mới là giá trị đúng cho SQL OFFSET
        
        $startItem = $offset + 1;
        $endItem = min($page * $limit, $totalCustomers);

        // Truyền $offset vào thay vì $startItem
        $customers = $this->kh_model->getAllKhachHang($limit, $offset);
        
        include "../views/pages/admin/QuanLyKhachHang.php";
    }
    private function xuLyCapNhat() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->kh_model->setThongTin(
                $_POST['ho_ten_dem'],
                $_POST['ten'],
                $_POST['sdt'],
                $_POST['diachi'],
                $_POST['hang_thanh_vien'],
                $_POST['id_tai_khoan']
            );

            if ($this->kh_model->CapNhatThongTinKhachHang()) {
                $_SESSION['success'] = "Cập nhật thông tin thành công!";
            } else {
                $_SESSION['error'] = "Cập nhật thất bại!";
            }
            header('Location: index.php?act=Customer_ManagementPage');
            exit();
        }
    }

    public function layChiTiet() {
        // 1. Kiểm tra quyền truy cập (Quan trọng)
        if (!isset($_SESSION['user'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Chưa đăng nhập']);
            exit();
        }

        // 2. Lấy ID từ GET
        $id = $_GET['id_tai_khoan'] ?? 0;
        
        // 3. Gọi model
        $data = $this->kh_model->getThongTinByTaiKhoanId($id);
        
        // 4. Trả về JSON
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    private function xuLyKhoaMo() {
        $id = $_GET['id_tai_khoan'] ?? 0; // Chuyển sang dùng GET
        $status = $_GET['status'] ?? 0; 

        if ($id > 0) {
            $this->tk_model->updateTrangThaiTaiKhoan($id, $status);
            $_SESSION['success'] = "Cập nhật trạng thái thành công!";
        }
        header("Location: index.php?act=QuanLyKhachHang");
        exit();
    }
}