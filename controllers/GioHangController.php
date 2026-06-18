<?php
require_once '../model/GioHang.php';
include_once __DIR__ . '/../app/utils/color_helper.php';
class GioHangController {
    private $ghModel;
    private $db;
    public function __construct($db) {
        $this->ghModel = new GioHang($db);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
   public function handle($act)
    {
        if (!isset($_SESSION['user']['id_tai_khoan']) || $_SESSION['user']['id_tai_khoan'] <= 0) {
            header("Location: index.php?act=Login");
            exit();
        }

        switch ($act) {
            case 'GioHang':
                $this->index();
                break;

            case 'ThemGioHang':
                $this->addToCart();
                break;

            case 'XoaGioHang':
                $this->xoaGioHang();
                break;

            case 'CapNhatSoLuong':
                $this->capNhatSoLuong();
                break;

            default:
                $this->index();
                break;
        }
    }

    // Hiển thị danh sách giỏ hàng
    private function index() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $this->syncCartFromDB();

        $listSanPham = $_SESSION['cart'];

        foreach ($listSanPham as $key => &$item) {
            $item['ten_mau'] = $item['ten_mau'] ?? getColorName($item['mau']);
        }

        include '../views/pages/Cart.php';
}

    private function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Request không hợp lệ']);
            exit;
        }

        $id_khach_hang = $_SESSION['user']['id_khach_hang'] ?? 0;
        $id_bien_the = isset($_POST['id_bien_the']) ? (int)$_POST['id_bien_the'] : 0;
        $so_luong = isset($_POST['so_luong']) ? (int)$_POST['so_luong'] : 1;

        if ($id_khach_hang <= 0 || $id_bien_the <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        try {
            // Lấy ID giỏ hàng
            $id_gio_hang = $this->ghModel->getGioHangId($id_khach_hang);

            // Nếu chưa có giỏ hàng, tạo mới
            if (!$id_gio_hang) {
                $id_gio_hang = $this->ghModel->createGioHang($id_khach_hang);
            }

            // Kiểm tra xem ID giỏ hàng có hợp lệ không trước khi thêm
            if ($id_gio_hang > 0) {
                $result = $this->ghModel->addToGioHang($id_gio_hang, $id_bien_the, $so_luong);
                
                if ($result) {
                    $this->syncCartFromDB();
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Lỗi khi thêm vào DB']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không thể tạo giỏ hàng']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
     // Ví dụ xử lý trong GioHangController
   public function capNhatSoLuong() {

        header('Content-Type: application/json');

        $id = $_GET['id']; // id_bien_the
        $type = $_GET['type'];

        $id_khach_hang = $_SESSION['user']['id_khach_hang'];
        $id_gio_hang = $this->ghModel->getGioHangId($id_khach_hang);

        // 🔥 LẤY STOCK TỪ DB
        $stock = $this->ghModel->getStockByVariant($id);

        $current = $_SESSION['cart'][$id]['so_luong'] ?? 0;
        $stock = $_SESSION['cart'][$id]['so_luong_ton'] ?? 0;
        if ($type === 'plus') {

            // ❌ CHẶN VƯỢT KHO
            if ($current >= $stock) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Chỉ còn $stock sản phẩm trong kho"
                ]);
                exit;
            }

            $this->ghModel->updateQty($id_gio_hang, $id, 1);

        } else {

            if ($current <= 1) {
                $this->ghModel->removeFromGioHang($id_gio_hang, $id);
            } else {
                $this->ghModel->updateQty($id_gio_hang, $id, -1);
            }
        }

        // sync lại cart
        $this->syncCartFromDB();

        $newQty = $_SESSION['cart'][$id]['so_luong'] ?? 0;

        echo json_encode([
            "status" => "success",
            "newQty" => $newQty,
            "stock" => $stock
        ]);
        exit;
    }
    public function xoaGioHang() {
        $id_bien_the = $_GET['id'];
        // 1. Xóa trong Session
        unset($_SESSION['cart'][$id_bien_the]);
        // 2. Xóa trong Database (Cần thêm hàm này vào Model)
        $id_user = $_SESSION['user']['id_khach_hang'];
        $id_gio_hang = $this->ghModel->getGioHangId($id_user);
        $this->ghModel->removeFromGioHang($id_gio_hang, $id_bien_the); 
        header("Location: index.php?act=GioHang");
        exit();
    }         
    // Thêm hàm này vào Controller
    public function syncCartFromDB() {

        $id_khach_hang = $_SESSION['user']['id_khach_hang'] ?? null;

        if (!$id_khach_hang) {
            $_SESSION['cart'] = [];
            return;
        }

        $id_gio_hang = $this->ghModel->getGioHangId($id_khach_hang);

        if (!$id_gio_hang) {
            $_SESSION['cart'] = [];
            return;
        }

        $items = $this->ghModel->getChiTietGioHang($id_gio_hang);

        $_SESSION['cart'] = [];

        foreach ($items as $item) {

            $_SESSION['cart'][$item['id_bien_the']] = [
                'id_bien_the'  => $item['id_bien_the'],
                'ten_san_pham' => $item['ten_san_pham'],
                'gia'          => (float)$item['gia_ban'],
                'size'         => $item['kich_co'],
                'mau'          => $item['mau_sac'],
                'hinh_anh'     => $item['hinh_anh_bien_the'],
                'so_luong'     => (int)$item['so_luong'],
                'so_luong_ton' => (int)$item['so_luong_ton']
            ];
        }
    }
}