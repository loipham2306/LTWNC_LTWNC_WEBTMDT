<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// 1. Nhúng cấu hình và khởi tạo kết nối DB duy nhất
require_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();
require_once '../controllers/DanhMucController.php';
require_once '../controllers/SanPhamController.php';
require_once '../controllers/KhachHangController.php';
require_once '../controllers/ThuongHieuController.php';

// 2. Lấy action (act) từ URL
$act = $_REQUEST['act'] ?? 'trangchu';

// 3. Điều hướng tập trung
switch ($act) {
    case 'trangchu':
        require_once 'HomeController.php';
        $home = new HomeController($db);
        $home->index();

        break;
    // --- NHÓM ĐĂNG NHẬP / TÀI KHOẢN ---
    case 'login':
        // Chỉ hiển thị giao diện login, không gọi controller xử lý ở đây
        include '../views/pages/login.php';
        break;

    case 'xuly_dangnhap':
        // Chỉ dành cho việc xử lý logic POST
        include_once 'DangNhapController.php';
        $dnController = new DangNhapController($db);
        $dnController->xuLyDangNhap(); // Gọi trực tiếp hàm xử lý
        break;
    case 'admin_dashboard':
        // Kiểm tra quyền admin trước khi cho vào
        if (isset($_SESSION['user']) && $_SESSION['user']['vai_tro'] === 'admin') {
            include '../views/pages/admin/Dashboard.php';
            exit();
        } else {
            // Nếu không phải admin thì đá về trang chủ
            header("Location: index.php");
        }
        break;
    
    case 'Product_Management':
        if (isset($_SESSION['user']) && $_SESSION['user']['vai_tro'] == 'admin') {
            include '../views/pages/admin/ProductAdmin.php'; 
        } else {
            header("Location: index.php?act=login");
            exit();
        }
        break;
    case 'Order_Management':
        if (isset($_SESSION['user']) && $_SESSION['user']['vai_tro'] == 'admin') {
            include '../views/pages/admin/OrderAdmin.php'; 
        } else {
            header("Location: index.php?act=login");
            exit();
        }
        break;
    case 'Voucher_Management':
        if (isset($_SESSION['user']) && $_SESSION['user']['vai_tro'] == 'admin') {
            include '../views/pages/admin/VoucherAdmin.php'; 
        } else {
            header("Location: index.php?act=login");
            exit();
        }
        break;
    case 'register':
        include_once 'DangKyController.php'; // Nên chuyển sang Controller Class
        //$dkController = new DangKyController($db);
        //$dkController->handle($act);
        break;

    case 'logout':
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Xóa sạch dữ liệu trong biến $_SESSION ngay lập tức
        $_SESSION = array(); 
        
        session_unset();
        session_destroy();
        // 3. Chuyển hướng về trang đăng nhập
        // Đảm bảo đường dẫn này đúng với cấu trúc hiện tại của bạn
        header("Location: /LTWNC_LTWNC_WEBTMDT/controllers/index.php");
        exit();
    // Danh Mục
    case 'QuanLyDanhMuc':
    case 'themDM':  
    case 'suaDM':   
    case 'xoaDM':
        include_once 'DanhMucController.php'; // Bạn cần tạo file này
        $dmController = new DanhMucController($db);
        $dmController->handle($act);
        break;
    // --- NHÓM THƯƠNG HIỆU ---
    case 'QuanLyThuongHieu':
    case 'themTH':
    case 'suaTH':
    case 'xoaTH':
        include_once 'ThuongHieuController.php';
        $thController = new ThuongHieuController($db);
        $thController->handle($act);
        break;
    // --- NHÓM SAN PHAM ---
    case 'QuanLySanPham':
    case 'themSP':
    case 'suaSP':
    case 'xoaSP':
    case 'layBienThe': 
        include_once 'SanPhamController.php';
        $spController = new SanPhamController($db);
        $spController->handle($act);
        break;
    // --- NHÓM KHÁCH HÀNG ---
    case 'QuanLyKhachHang':
    case 'updateKH':
    case 'detailKH':
    case 'toggleStatus': // Hành động khóa/mở tài khoản bằng AJAX
        include_once 'KhachHangController.php';
        $khController = new KhachHangController($db);
        $khController->handle($act);
        break;
    // --- MẶC ĐỊNH ---
    default:
        include '../index.php';
        exit();
}
?>