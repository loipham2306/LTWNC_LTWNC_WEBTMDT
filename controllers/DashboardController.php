<?php

class DashboardController {
    private $sp_model;
    private $tai_khoan_model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        // Giả sử bạn khởi tạo model ở đây
        // Yêu cầu: Đảm bảo class SanPham được định nghĩa và nhận vào $db
        require_once "../model/SanPham.php";
        require_once "../model/TaiKhoan.php";
        $this->sp_model = new SanPham($db);
        $this->tai_khoan_model= new TaiKhoan($db);
    }

    /**
     * Hàm xử lý trang Dashboard
     */
    public function index() {
        try {
            // 1. Lấy dữ liệu từ Models
            // Gom dữ liệu vào một mảng duy nhất để truyền sang view gọn hơn
            $data = [
                'total_revenue'   => $this->sp_model->getTotalRevenue(),
                'new_orders'      => $this->sp_model->countNewOrders(),
                'total_products'  => $this->sp_model->countAllProducts(),
                'total_users'     => $this->tai_khoan_model->countTotalCustomers(),
                'brand_stats'     => $this->sp_model->getStatsByBrand()
            ];

            // 2. Kiểm tra file
            $viewPath = "../views/pages/admin/Dashboard.php";
            if (!file_exists($viewPath)) {
                throw new Exception("Không tìm thấy tệp giao diện: " . $viewPath);
            }

            // 3. Include view
            // Lúc này trong Dashboard.php, bạn chỉ cần dùng $data['total_revenue']...
            include $viewPath;
                
        } catch (Exception $e) {
            error_log("Dashboard Error: " . $e->getMessage());
            $_SESSION['error'] = "Có lỗi xảy ra khi tải bảng điều khiển.";
            header("Location: index.php?act=home");
            exit();
        }
    }
}