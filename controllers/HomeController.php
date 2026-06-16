<?php
require_once '../model/DanhMuc.php'; 
require_once '../model/SanPham.php';
require_once '../model/ThuongHieu.php';
require_once '../model/Vouchers.php';
class HomeController {

    private $spModel;
    private $thModel;
    private $voucherModel;
    public function __construct($db) {
        $this->spModel = new SanPham($db);
        $this->thModel = new ThuongHieu($db);
        $this->voucherModel = new Vouchers($db);
    }

    public function index() {
        $homeFeaturedProducts = $this->spModel->getSanPhamHome(4);
        $brands = $this->thModel->getTatCaThuongHieu();
        $currentUserId = $_SESSION['user']['id_tai_khoan'] ?? 0;
        // HomeController.php
        $currentUserId = $_SESSION['user']['id_tai_khoan'] ?? 0;
        $danhSachVoucher = $this->voucherModel->layTatCaVoucher();

        // Sử dụng mảng tạm để không làm thay đổi mảng gốc ngay lập tức
        $danhSachDaXuLy = [];
        if (!empty($danhSachVoucher)) {
            foreach ($danhSachVoucher as $vc) { // BỎ DẤU & Ở ĐÂY
                // Kiểm tra cho từng voucher cụ thể
                $vc['da_luu'] = $this->voucherModel->kiemTraDaLuuVoucher($currentUserId, $vc['id_voucher']);
                $danhSachDaXuLy[] = $vc;
            }
        }
        $danhSachVoucher = $danhSachDaXuLy; // Gán lại mảng đã có trạng thái
        include '../index.php';
    }
}
?>