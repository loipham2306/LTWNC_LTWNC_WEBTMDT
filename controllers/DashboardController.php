<?php

class DashboardController {
    private $donHangModel;
    private $sp_model;
    private $tai_khoan_model;
    private $thuong_hieu_model;

    public function __construct($db) {
        require_once "../model/DonHangModel.php";
        require_once "../model/SanPham.php";
        require_once "../model/TaiKhoan.php";
        require_once "../model/ThuongHieu.php";

        $this->donHangModel      = new DonHangModel($db);
        $this->sp_model          = new SanPham($db);
        $this->tai_khoan_model   = new TaiKhoan($db);
        $this->thuong_hieu_model = new ThuongHieu($db);
    }

    public function index() {
        try {
            // ⚠️ tạm thời nếu chưa có hàm
            $newOrders      = 0;
            $shippingOrders = 0;
            $cancel_orders   = 0;
            // KPI ĐƠN HÀNG
            $totalRevenue = $this->donHangModel->getTongDoanhThu() ?? 0;
            $totalOrders  = $this->donHangModel->countDonHang() ?? 0;
            $cancel_orders = $this->donHangModel-> countCancelledOrders() ?? 0;
            $newOrders = $this->donHangModel->countNewOrders() ?? 0;
            $shippingOrders= $this->donHangModel->countShippingOrders()  ?? 0;
            // SẢN PHẨM - USER
            $totalProducts = $this->sp_model->countAllProducts() ?? 0;
            $totalUsers    = $this->tai_khoan_model->countTotalCustomers() ?? 0;
            // THƯƠNG HIỆU
            $brandStats = $this->thuong_hieu_model->getAllThuongHieuWithCount() ?? [];
            $totalBrand = $this->thuong_hieu_model->countThuongHieu();
            $topProducts =  $this->sp_model->getTopSellingProducts(10);
           
            // BIỂU ĐỒ (tạm thời)
            $revenueByMonth = $this->donHangModel->getRevenueByMonth();

            // ĐƠN GẦN ĐÂY (RAW → MAP LẠI CHO UI)
            $rawOrders = $this->donHangModel->getFilteredOrders('', '', 0, 5);

            $recentOrders = array_map(function ($o) {
                return [
                    'id'       => $o['id_don_hang'] ?? 0,
                    'customer' => $o['ten_nguoi_nhan'] ?? 'N/A',
                    'date'     => $o['ngay_dat'] ?? '',
                    'total'    => number_format((float)($o['tong_tien'] ?? 0)) . ' đ',
                    'status'   => $o['trang_thai_don_hang'] ?? 'Không rõ'
                ];
            }, $rawOrders);

            // DATA VIEW
            $data = [
                'total_revenue'    => $totalRevenue,
                'new_orders'       => $newOrders,
                'total_orders'     => $totalOrders,
                'shipping_orders'  => $shippingOrders,
                'cancel_orders'    => $cancel_orders,

                'total_products'   => $totalProducts,
                'total_users'      => $totalUsers,

                'brand_stats'      => $brandStats,
                'total_brand'      => $totalBrand,

                'revenue_data'     => $revenueByMonth,
                'recent_orders'    => $recentOrders
            ];

            $viewPath = "../views/pages/admin/Dashboard.php";

            if (!file_exists($viewPath)) {
                throw new Exception("Không tìm thấy view dashboard");
            }

            include $viewPath;

        } catch (Exception $e) {
            error_log("Dashboard Error: " . $e->getMessage());

            $_SESSION['error'] = "Không thể tải dashboard";
            header("Location: index.php?act=home");
            exit();
        }
    }
}