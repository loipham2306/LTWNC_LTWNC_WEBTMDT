<?php
require_once '../model/GioHang.php';
include_once __DIR__ . '/../app/utils/color_helper.php';
class GioHangController {
    private $ghModel;

    public function __construct($db) {
        $this->ghModel = new GiaHang($db);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

   public function handle($act)
    {
        if (!isset($_SESSION['user']['id_tai_khoan'])) {
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
        $listSanPham = $_SESSION['cart'] ?? [];
        
        // Đảm bảo dữ liệu đã được gán tên màu trước khi truyền sang View
        foreach ($listSanPham as $key => &$item) {
            if (!isset($item['ten_mau'])) {
                $item['ten_mau'] = getColorName($item['mau']);
            }
        }
        
        include '../views/pages/Cart.php';
    }

    private function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Request không hợp lệ");
        }
        $id_bien_the = isset($_POST['id_bien_the'])
            ? (int)$_POST['id_bien_the']
            : 0;
        $so_luong = isset($_POST['so_luong'])
            ? (int)$_POST['so_luong']
            : 1;
        if ($id_bien_the <= 0) {
            die("ID biến thể không hợp lệ");
        }
        $id_user = $_SESSION['user']['id_tai_khoan'] ?? 0;
        if ($id_user <= 0) {
            die("Bạn chưa đăng nhập");
        }
        try {
            // Lấy ID giỏ hàng
            $id_gio_hang = $this->ghModel->getGioHangId($id_user);
            // Thêm vào DB
            $result = $this->ghModel->addToGioHang(
                $id_gio_hang,
                $id_bien_the,
                $so_luong
            );
            if (!$result) {
                die("Không thể thêm vào giỏ hàng");
            }
            // Đồng bộ Session từ DB
            $this->syncCartFromDB();
            echo "success";
        } catch (Exception $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }
     // Ví dụ xử lý trong GioHangController
    public function capNhatSoLuong() {
        $id = $_GET['id'];
        $type = $_GET['type'];

        if ($type === 'plus') {
            $_SESSION['cart'][$id]['so_luong']++;
        } elseif ($type === 'minus' && $_SESSION['cart'][$id]['so_luong'] > 1) {
            $_SESSION['cart'][$id]['so_luong']--;
        }
        header("Location: index.php?act=GioHang"); // Tải lại trang sau khi cập nhật
        exit();
    }
    public function xoaGioHang() {
        $id_bien_the = $_GET['id'];
        // 1. Xóa trong Session
        unset($_SESSION['cart'][$id_bien_the]);
        // 2. Xóa trong Database (Cần thêm hàm này vào Model)
        $id_user = $_SESSION['user']['id_tai_khoan'];
        $id_gio_hang = $this->ghModel->getGioHangId($id_user);
        $this->ghModel->removeFromGioHang($id_gio_hang, $id_bien_the); 
        header("Location: index.php?act=GioHang");
        exit();
    }         
    // Thêm hàm này vào Controller
    public function syncCartFromDB() {
        $id_user = $_SESSION['user']['id_tai_khoan'];
        $id_gio_hang = $this->ghModel->getGioHangId($id_user);
        $items = $this->ghModel->getChiTietGioHang($id_gio_hang);

        $_SESSION['cart'] = []; // Xóa giỏ cũ để cập nhật mới
        foreach ($items as $item) {
            $_SESSION['cart'][$item['id_bien_the']] = [
                'id_bien_the'  => $item['id_bien_the'],
                'ten_san_pham' => $item['ten_san_pham'],
                'gia'          => $item['gia_ban'],
                'size'         => $item['kich_co'],
                'mau'          => $item['mau_sac'],
                'hinh_anh'     => $item['hinh_anh_bien_the'],
                'so_luong'     => $item['so_luong']
            ];
        }
    }
}