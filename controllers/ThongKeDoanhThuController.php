<?php
// Giả sử các file này đã được require ở file index.php cha
require_once '../model/ThongKeModel.php';
class ThongKeDoanhThuController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ThongKeModel($pdo);
    }

    public function index() {
        // 1. Kiểm tra quyền Admin (đã thực hiện ở index.php nhưng thêm ở đây để an toàn)
        if (!isset($_SESSION['user']) || $_SESSION['user']['vai_tro'] !== 'admin') {
            header("Location: index.php");
            exit();
        }

        // 2. Lấy dữ liệu từ Model
        $dataTuan = $this->model->getDoanhThuTheoTuan();
        $dataSanPham = $this->model->getTopSanPhamBanChay();

        // 3. Chuẩn bị dữ liệu cho biểu đồ (Format lại để Chart.js dễ hiểu)
        // Đảo ngược mảng để hiển thị từ tuần cũ đến tuần mới (trái sang phải)
        $dataTuan = array_reverse($dataTuan);
        
        $labelsTuan = [];
        $valuesTuan = [];
        foreach ($dataTuan as $item) {
            $labelsTuan[] = 'T' . $item['tuan'] . '/' . $item['nam'];
            $valuesTuan[] = (float)$item['doanh_thu'];
        }

        // 4. Render nội dung
        // Sử dụng bộ đệm để tách view khỏi controller
        ob_start();
        include '../views/pages/admin/ThongKeDoanhThu.php';
        $PAGE_CONTENT = ob_get_clean();

        // 5. Nạp layout chính
        include '../views/pages/admin/AdminLayout.php';
    }
}