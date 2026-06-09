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
            default:
                $this->danhSach();
                break;
        }
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

    private function layChiTiet() {
        $id = $_GET['id_tai_khoan'] ?? 0;
        $data = $this->kh_model->getThongTinByTaiKhoanId($id);
        
        // Trả về JSON để xử lý ở phía Client (Modal)
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    private function xuLyKhoaMo() {
        $id = $_POST['id_tai_khoan'] ?? 0;
        $status = $_POST['status'] ?? 0; 

        if ($id > 0) {
            // Cập nhật trạng thái
            $this->tk_model->updateTrangThaiTaiKhoan($id, $status);
        }
        
        // Chuyển hướng về trang quản lý khách hàng thay vì trả về JSON
        header("Location: index.php?act=QuanLyKhachHang");
        exit(); 
    }
}