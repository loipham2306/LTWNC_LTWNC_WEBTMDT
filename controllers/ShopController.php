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

        // 1. Khởi tạo phân trang
        $limit = 9;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        // 2. Bắt biến từ URL
        $mode = $_GET['mode'] ?? 'all';
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

        // 3. XỬ LÝ LOGIC LẤY SẢN PHẨM (Ưu tiên Tìm kiếm -> Khuyến mãi -> Tất cả)
        if ($keyword !== '') {
            // TRƯỜNG HỢP 1: CÓ TÌM KIẾM
            $Products = $this->productModel->searchProductsForShop($keyword, $limit, $offset);
            
            // Gắn promotion cho kết quả tìm kiếm
            $this->productModel->applyPromotion($Products);
            
            $totalProducts = $this->productModel->countSearchProductsForShop($keyword);

        } elseif ($mode === 'khuyenmai') {
            // TRƯỜNG HỢP 2: MODE KHUYẾN MÃI
            $allProducts = $this->qlKhuyenMai->getSanPhamKhuyenMai();

            // Gắn promotion
            $this->productModel->applyPromotion($allProducts);

            $totalProducts = count($allProducts);
            $Products = array_slice($allProducts, $offset, $limit);

        } else {
            // TRƯỜNG HỢP 3: MODE ALL (MẶC ĐỊNH)
            $Products = $this->productModel->getAllProductsForShop($limit, $offset);

            // Gắn promotion (Đã fix lỗi truyền sai biến $allProducts thành $Products)
            $this->productModel->applyPromotion($Products);

            $totalProducts = $this->productModel->countAllProductsForShop();
        }

        // 4. Tính toán trang và lấy danh mục hiển thị
        $totalPages = ceil($totalProducts / $limit);

        $stmt = $this->danhMucModel->getDanhMucShop();
        $danhMucList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 5. Trả ra View
        include __DIR__ . '/../views/pages/Shop.php';
    }
    
    
}