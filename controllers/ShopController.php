<?php
require_once __DIR__ . '/../model/SanPham.php';
require_once __DIR__ . '/../model/Danhmuc.php';

class ShopController {
    private $productModel;
    private $danhMucModel;
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
    }

    public function index() {
        // 1. Lấy tất cả sản phẩm
        $Products = $this->productModel->getAllProductsForShop();
        
        // 2. Lấy danh mục để hiển thị sidebar
        $stmt = $this->danhMucModel->getDanhMucShop();
        $danhMucList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // 3. Gọi View và truyền dữ liệu sang
        include __DIR__ . '/../views/pages/Shop.php';
    }
}