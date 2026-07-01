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
                case 'toggleStatusKM':
                    $this->toggleStatus();
                    break;
                case 'XoakhuyenMai':
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
       $listSanPham = $this->kmModel->getDanhSachBienThe();
       
        include '../views/pages/admin/QuanLyKhuyenMai.php';
    }
   
    private function TaoKhuyenMai() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten = $_POST['ten_km'];
            $phan_tram = (int)$_POST['phan_tram_giam'];
            $start = $_POST['ngay_bat_dau'];
            $end = $_POST['ngay_ket_thuc'];
            $trang_thai = (int)$_POST['trang_thai'];
            $selection = $_POST['selection']; // Ví dụ: "sp_1" hoặc "bt_5"
            $parts = explode('_', $selection);
            $type = $parts[0]; // 'sp' hoặc 'bt'
            $id = (int)$parts[1];
            // Xử lý upload ảnh Banner
            $bannerName = null;
            if (!empty($_FILES['hinh_anh_banner']['name'])) {
                $target_dir = __DIR__ . '/../assets/images/banners/';
                $bannerName = time() . '_' . basename($_FILES['hinh_anh_banner']['name']);
                move_uploaded_file($_FILES['hinh_anh_banner']['tmp_name'], $target_dir . $bannerName);
            }

            $id_km = $this->kmModel->createKhuyenMai($ten, $phan_tram, $start, $end, $trang_thai, $bannerName);
        
            if ($id_km) {
                // Gán vào sản phẩm hoặc biến thể tương ứng
                if ($type === 'sp') {
                    $this->kmModel->addProductToKhuyenMai(
                        $id_km,
                        $id,
                        null
                    );
                } else {
                    $id_sp = $this->kmModel->getSanPhamByBienThe($id);
                    $this->kmModel->addProductToKhuyenMai(
                        $id_km,
                        $id_sp,
                        $id
                    );
                }
                $_SESSION['success'] = "Tạo khuyến mãi và gán thành công!";
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
            // Lấy thêm id_bien_the từ form, nếu không chọn thì để null
            $id_bt = !empty($_POST['id_bien_the']) ? (int)$_POST['id_bien_the'] : null;

            // Gọi model đã được nâng cấp (nhận 3 tham số)
            if ($this->kmModel->addProductToKhuyenMai($id_km, $id_sp, $id_bt)) {
                echo json_encode(['status' => 'success', 'message' => 'Đã thêm thành công!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi hoặc đã tồn tại!']);
            }
        }
    }

    // Bật/Tắt chương trình
    private function toggleStatus() {
        $id = (int)$_GET['id'];
        $status = (int)$_GET['status'];
        $this->kmModel->toggleStatus($id, $status);
        header("Location: index.php?act=QuanLyKhuyenMai");
    }
    // Thêm vào QuanLyKhuyenMaiController.php
    public function deleteKH() {
        $id = (int)$_GET['id'];
        $this->kmModel->removeProductFromKhuyenMai($id); 
        header("Location: index.php?act=QuanLyKhuyenMai");
    }
}
