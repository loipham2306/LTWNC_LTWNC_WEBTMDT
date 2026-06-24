<?php
require_once __DIR__ . '/../model/SanPham.php';
require_once __DIR__ . '/../model/Danhmuc.php';
require_once __DIR__ . '/../model/QuanLyKhuyenMai.php';
class ShopController {
    private $productModel;
    private $danhMucModel;
    private $qlKhuyenMai;
    public function handle($act) {
        switch ($act) {
            case 'Shop':
                $this->index();
                break;
            default:
                $this->index();
                break;
        }
    } 
    public function __construct($db) {
        $this->productModel = new SanPham($db);
        $this->danhMucModel = new DanhMuc($db);
        $this->qlKhuyenMai= new QuanLyKhuyenMai($db);
    }

    public function index() {

        $limit = 9;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $offset = ($page - 1) * $limit;
        $mode = $_GET['mode'] ?? 'all';
        // MODE KHUYẾN MÃI
        if ($mode === 'khuyenmai') {

            $allProducts = $this->qlKhuyenMai->getSanPhamKhuyenMai();

            // gắn promotion
            $this->productModel->applyPromotion($allProducts);

            $totalProducts = count($allProducts);
            $Products = array_slice($allProducts, $offset, $limit);

        } 
        // MODE ALL
        else {

            $Products = $this->productModel->getAllProductsForShop($limit, $offset);

            // gắn promotion
            $this->productModel->applyPromotion($allProducts);

            $totalProducts = $this->productModel->countAllProductsForShop();
        }

        $totalPages = ceil($totalProducts / $limit);

        $stmt = $this->danhMucModel->getDanhMucShop();
        $danhMucList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../views/pages/Shop.php';
    }
    
}