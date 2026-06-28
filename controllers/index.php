<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// index.php
require_once __DIR__ . '/../vendor/autoload.php';
// 1. Nhúng cấu hình và khởi tạo kết nối DB duy nhất
require_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();
require_once '../controllers/DanhMucController.php';
require_once '../controllers/SanPhamController.php';
require_once '../controllers/KhachHangController.php';
require_once '../controllers/ThuongHieuController.php';
require_once '../controllers/DangKyController.php';
require_once '../controllers/VoucherController.php';
require_once '../controllers/QuanLyKhuyenMaiController.php';
include_once '../controllers/ShopController.php';
include_once '../controllers/GioHangController.php';
require_once '../controllers/ThanhToanController.php';
require_once '../controllers/UserProfileController.php';
require_once '../controllers/DonHangController.php';
require_once '../controllers/BinhLuanVaDanhGiaController.php';
require_once '../controllers/ThongKeDoanhThuController.php';

require_once '../model/QuanLyKhuyenMai.php';

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
    case 'Login':
        include '../views/pages/Login.php';
        break;
    case 'LienHe':
        include '../views/pages/Contact.php';
        break;
        
    case 'layChiTiet':
        require_once 'KhachHangController.php'; // Đảm bảo gọi đúng file Controller
        $khController = new KhachHangController($db);
        $khController->layChiTiet(); 
        // KHÔNG ĐƯỢC CÓ BẤT KỲ LỆNH ECHO HOẶC INCLUDE HTML NÀO Ở ĐÂY
        break;
    // --- NHÓM ĐĂNG NHẬP / TÀI KHOẢN ---
    case 'Register':
    case 'xulydangky': 
    case 'form_otp':
    case 'xacnhanotp':
    case 'form_matkhau':
    case 'hoantatdangky':
        require_once 'DangKyController.php';
        $dangKyController = new DangKyController($db);
        $dangKyController->handle($act);
        break;
    // --- NHÓM QUÊN MẬT KHẨU ---
    case 'QuenMatKhau':
    case 'XuLyQuenMatKhau':
    case 'XacNhanOTPQuenMat':
    case 'FormDatLaiMatKhau':
    case 'XuLyDatLaiMatKhau':
        require_once 'QuenMatKhauController.php';
        $qmkController = new QuenMatKhauController($db);
        $qmkController->handle($act);
        break;
    // --- profile ---
    case 'UserProfile':
    case 'updateProfile':
    case 'changePassword':
    case 'guiDanhGia':
    // 1. Khởi tạo Controller
        include_once 'UserProfileController.php';
        $profileController = new UserProfileController($db);
        // 2. Gọi hàm xử lý (hàm này sẽ tự load dữ liệu và include View)
        $profileController->handle($act);
        break;
    case 'xuly_dangnhap':
        // Chỉ dành cho việc xử lý logic POST
        include_once 'DangNhapController.php';
        $dnController = new DangNhapController($db);
        $dnController->xuLyDangNhap(); // Gọi trực tiếp hàm xử lý
        break;
    case 'admin_dashboard':
        if (isset($_SESSION['user']) && $_SESSION['user']['vai_tro'] === 'admin') {

            require_once '../controllers/DashboardController.php';

            $controller = new DashboardController($db);
            $controller->index(); // <-- QUAN TRỌNG

            exit();
        } else {
            header("Location: index.php");
            exit();
        }
        break;
    //render cửa hàng
    case 'Shop':
        include_once 'ShopController.php'; // Đảm bảo đường dẫn đúng
        $ShopController = new ShopController($db); // Bỏ dấu $ ở tên class
        $ShopController->handle($act);
        break;
    case 'Order_Management':
        if (isset($_SESSION['user']) && $_SESSION['user']['vai_tro'] == 'admin') {
            include '../views/pages/admin/OrderAdmin.php'; 
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
        
        // 1. Xóa sạch dữ liệu
        $_SESSION = array(); 
        
        // 2. Xóa Cookie của Session (Quan trọng để xóa hoàn toàn phiên cũ trên trình duyệt)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // 3. Hủy Session
        session_unset();
        session_destroy();
        
        // 4. Chuyển hướng
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
    // chi tiết sản phẩm
    case 'ProductDetail':
        if (isset($_GET['id'])) {

            $id = $_GET['id'];

            $spModel = new SanPham($db);
            $blModel = new BinhLuanVaDanhGia($db);

            $product = $spModel->getChiTietSanPham($id);

            if (!$product) {
                include '../views/pages/ProductDetail.php';
                break;
            }

            // 🔥 đồng bộ promotion
            $spModel->applyPromotionSingle($product);

            $DanhSachBinhLuan = $blModel->layBinhLuanTheoSanPham($id);

            // 👉 dùng giá đã tính
            $firstVariant = $product['bien_the'][0] ?? null;

            $gia_hien_tai = $firstVariant['gia_sau_giam']
                ?? $firstVariant['gia_ban']
                ?? $product['gia_co_ban'];
            $imgPath = '/LTWNC_LTWNC_WEBTMDT/assets/images/products/' . $product['hinh_anh'];

            include '../views/pages/ProductDetail.php';
        }
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
    // --- Voucher ---
    case 'QuanLyVoucher':
    case 'ThemVoucher':
    case 'CapNhatVoucher':
    case 'XoaVoucher':
    case 'LuuVoucher': 
    case 'ViVoucher':
        include_once 'VoucherController.php';
        $voucherController = new VoucherController($db);
        $voucherController->handle($act); 
        break;
   // --- Nhóm quản lý khuyến mãi ---
    case 'QuanLyKhuyenMai':
    case 'UpdateKM':
    case 'detailKM':
    case 'toggleStatusKM':
    case 'XoakhuyenMai':
    case 'TaoKhuyenMai': // Action này nhận dữ liệu từ form thêm mới
        include_once 'QuanLyKhuyenMaiController.php';
        $kmController= new QuanLyKhuyenMaiController($db);
        $kmController->handle($act);
        break;
    // --- Nhóm Giỏ Hàng ---
    case 'GioHang':
    case 'ThemGioHang':
    case 'XoaGioHang':
    case 'CapNhatSoLuong':
        include_once 'GioHangController.php';
        $GHController = new GioHangController($db);
        $GHController->handle($act);
        break;
    case 'LuuSanPhamThanhToan':
    case 'ThanhToan':
    case 'XuLyThanhToan':
        require_once '../controllers/ThanhToanController.php';
        $controller = new ThanhToanController($db);
        if ($act == 'LuuSanPhamThanhToan') {
            $controller->luuSanPhamThanhToan();
        } elseif ($act == 'XuLyThanhToan') {
            $controller->xuLyThanhToan();
        } else {
            $controller->showCheckout();
        }
        break;
    case 'ThanhToanThanhCong':
        require_once '../controllers/ThanhToanController.php';
        $controller = new ThanhToanController($db);
        // Lấy ID đơn hàng từ URL để hiển thị thông tin đúng
        $id = $_GET['id'] ?? 0;
        $controller->showThanhCong($_GET['id'] ?? null);
    break;
        break;
    // --- NHÓM ĐƠN HÀNG ---
    case 'QuanLyDonHang':
    case 'ChiTietDonHang':
    case 'CapNhatTrangThaiDonHang':
    case 'HuyDonHang':
    case 'XoaDonHang':

        include_once 'DonHangController.php';

        $dhController = new DonHangController($db);
        $dhController->handle($act);

        break;
    // Nhóm quản lý bình luận
    case 'QuanLyBinhLuan':
    case 'CapNhatTrangThaiBinhLuan':
    case 'XoaBinhLuan':
        include_once 'BinhLuanVaDanhGiaController.php';
        $blController = new BinhLuanVaDanhGiaController($db);
        $blController->handle($act);
        break;
    // ThongKeDoanhThu
    // index.php
    case 'ThongKeDoanhThu':
        require_once 'ThongKeDoanhThuController.php';
        $controller = new ThongKeDoanhThuController($db); // $pdo là kết nối database của bạn
        $controller->index();
        break;
    // --- MẶC ĐỊNH ---
    default:
        include '../index.php';
        exit();
}
?>