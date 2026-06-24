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
        $today = date('Y-m-d');

        $homeFeaturedProducts = $this->spModel->getSanPhamHome(8);

        // GẮN KHUYẾN MÃI CHO SẢN PHẨM
        $this->spModel->applyPromotion($homeFeaturedProducts);

        $brands = $this->thModel->getTatCaThuongHieu();

        $currentUserId = $_SESSION['user']['id_tai_khoan'] ?? null;

        $danhSachVoucher = $this->voucherModel->layTatCaVoucher();

        foreach ($danhSachVoucher as &$vc) {
            $vc['da_luu'] = $currentUserId
                ? $this->voucherModel->kiemTraDaLuuVoucher(
                    $currentUserId,
                    $vc['id_voucher']
                )
                : false;

            $vc['is_expired'] = ($vc['ngay_het_han'] < $today);
        }

        include '../index.php';
    }
}
?>