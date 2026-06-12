<?php
require_once __DIR__ . '/../config/database.php'; // Điều chỉnh đường dẫn đến file kết nối DB
require_once __DIR__ . '/../model/QuanLyKhuyenMai.php';

class QuanLyKhuyenMaiController {
    private $kmModel;

    public function __construct($db) {
        $this->kmModel = new QuanLyKhuyenMai($db);
    }
    public function handle($act) {
            switch ($act) {
                case 'TaoKhuyenMai':
                    $this->TaoKhuyenMai();
                    break;
                case 'KhuyenMaiTheoSanPham':
                    $this->KhuyenMaiTheoBienThe();
                    break;
                case 'toggle':
                    $this->toggleStatus();
                    break;
                default:
                    $this->index();
                    break;
            }
    }
    // Hiển thị danh sách
    private function index() {
        $listKM = $this->kmModel->getAllKhuyenMai();
        include '../views/pages/admin/QuanLyKhuyenMai.php';
    }

    private function TaoKhuyenMai() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten = $_POST['ten_km'];
            $phan_tram = (int)$_POST['phan_tram_giam'];
            $start = $_POST['ngay_bat_dau'];
            $end = $_POST['ngay_ket_thuc'];
            $trang_thai = (int)$_POST['trang_thai']; // Lấy từ <select> trong form
            
            // Xử lý upload ảnh Banner
            $bannerName = null;
            if (!empty($_FILES['hinh_anh_banner']['name'])) {
                $target_dir = __DIR__ . '/../assets/images/banners/';
                $bannerName = time() . '_' . basename($_FILES['hinh_anh_banner']['name']);
                move_uploaded_file($_FILES['hinh_anh_banner']['tmp_name'], $target_dir . $bannerName);
            }

            // Gọi model với ĐỦ 6 tham số
            if ($this->kmModel->createKhuyenMai($ten, $phan_tram, $start, $end, $trang_thai, $bannerName)) {
                $_SESSION['success'] = "Thêm chương trình thành công!";
                header("Location: index.php?act=QuanLyKhuyenMai");
                exit();
            }
        }
    }

    // Gán sản phẩm vào chương trình
    private function KhuyenMaiTheoBienThe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_km = (int)$_POST['id_km'];
            $id_sp = (int)$_POST['id_sp'];

            if ($this->kmModel->addProductToKhuyenMai($id_km, $id_sp)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Sản phẩm đã có trong chương trình này']);
            }
        }
    }

    // Bật/Tắt chương trình
    private function toggleStatus() {
        $id = (int)$_GET['id'];
        $status = (int)$_GET['status'];
        $this->kmModel->toggleStatus($id, $status);
        header("Location: index.php?controller=khuyen_mai");
    }
}
