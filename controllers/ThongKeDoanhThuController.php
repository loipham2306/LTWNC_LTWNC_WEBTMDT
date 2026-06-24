<?php
// Giả sử các file này đã được require ở file index.php cha
require_once '../model/ThongKeModel.php';
require_once '../model/DonHangModel.php';
class ThongKeDoanhThuController {
    private $model;
    private $DHmodel;
    public function __construct($pdo) {
        $this->model = new ThongKeModel($pdo);
        $this->DHmodel= new DonHangModel($pdo);
    }

    public function index() {
        // 1. Kiểm tra quyền Admin (đã thực hiện ở index.php nhưng thêm ở đây để an toàn)
        if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'admin') {
            header("Location: index.php");
            exit();
        }

        $dataTuan = array_reverse($this->model->getDoanhThuTheoTuan());
        $dataThang = array_reverse($this->model->getDoanhThuTheoThang());
        $dataSanPham = $this->model->getTopSanPhamBanChay();
        $dataKhachHang = $this->model->getTopKhachHang();
        $tongDoanhThu = $this->DHmodel->getTongDoanhThu();
        $tongDonHang = $this->DHmodel->countDonHang();

        // Chuẩn bị mảng để truyền sang JS (Tránh lỗi định dạng)
        $chartData = [
            'tuan' => [
                'labels' => array_map(fn($i) => 'T' . $i['tuan'] . '/' . $i['nam'], $dataTuan),
                'values' => array_map(fn($i) => (float)$i['doanh_thu'], $dataTuan)
            ],
            'thang' => [
                'labels' => array_map(fn($i) => 'Tháng ' . $i['thang'] . '/' . $i['nam'], $dataThang),
                'values' => array_map(fn($i) => (float)$i['doanh_thu'], $dataThang)
            ]
        ];

        ob_start();
        include '../views/pages/admin/ThongKeDoanhThu.php';
        $PAGE_CONTENT = ob_get_clean();
        include '../views/pages/admin/AdminLayout.php';
    }
};