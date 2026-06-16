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
    // 1. Cấu hình phân trang
    $limit = 9; // Số sản phẩm trên 1 trang
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $limit;

    // 2. Lấy danh sách sản phẩm phân trang
    $Products = $this->productModel->getAllProductsForShop($limit, $offset);
    
    // Cần hàm đếm tổng để tính số trang (bạn nên thêm hàm này vào Model)
    $totalProducts = $this->productModel->countAllProductsForShop(); 
    $totalPages = ceil($totalProducts / $limit);
    
    // 3. Lấy danh mục (Giữ nguyên)
    $stmt = $this->danhMucModel->getDanhMucShop();
    $danhMucList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 4. Truyền $totalPages sang View để hiển thị phân trang
    include __DIR__ . '/../views/pages/Shop.php';
}
}