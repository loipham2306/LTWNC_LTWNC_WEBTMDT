<?php
require_once '../model/Danhmuc.php';
class DanhMucController {
    private $model;

    public function __construct($db) {
        $this->model = new DanhMuc($db);
    }

    public function handle($act) {
        switch ($act) {
            case 'QuanLyDanhMuc': $this->danhSach(); break;
            case 'themDM':  $this->them(); break;
            case 'suaDM':   $this->sua(); break;
            case 'xoaDM':   $this->xoa(); break;
        }
    }

    private function danhSach() {
        $stmt = $this->model->getDanhMuc();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; // Gán mảng rỗng nếu không có dữ liệu

        // Tính toán thống kê an toàn
        $total = count($categories);
        $active = 0;
        $hidden = 0;

        foreach($categories as $c) {
            if (isset($c['trang_thai'])) {
                $c['trang_thai'] == 1 ? $active++ : $hidden++;
            }
        }

        // Giờ thì include file View, các biến này sẽ tồn tại sẵn
        include '../views/pages/admin/QuanLyDanhMuc.php';
    }

    private function them() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model->setData($_POST['ten_danh_muc'], $_POST['mo_ta'], $_POST['trang_thai']);
            if ($this->model->themDanhMuc()) {
                $_SESSION['success'] = "Thêm danh mục thành công!";
            }
            header("Location: index.php?act=QuanLyDanhMuc");
        }
    }

   private function xoa() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_danh_muc'])) {
        try {
            $id = $_POST['id_danh_muc'];
            
            // Thiết lập ID vào model
            $this->model->setIdDanhMuc($id);
            
            // Thực hiện xóa
            if ($this->model->deleteDanhMuc()) {
                $_SESSION['success'] = "Xóa danh mục thành công!";
            } else {
                // Nếu hàm trả về false mà không có exception, ta tự throw lỗi
                throw new Exception("Không thể xóa danh mục này.");
            }
        } catch (PDOException $e) {
            // Bắt lỗi cụ thể từ Database (ví dụ: lỗi khóa ngoại 1451)
            if ($e->getCode() == '23000') {
                $_SESSION['error'] = "Không thể xóa: Danh mục này đang chứa sản phẩm!";
            } else {
                $_SESSION['error'] = "Lỗi Database: " . $e->getMessage();
            }
        } catch (Exception $e) {
            // Bắt các lỗi logic thông thường
            $_SESSION['error'] = $e->getMessage();
        }
    }
    header("Location: index.php?act=QuanLyDanhMuc");
    exit();
}
    private function sua() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id_danh_muc'] ?? null;
        if (!$id) {
            $_SESSION['error'] = "Lỗi: Không tìm thấy ID danh mục!";
            header("Location: index.php?act=QuanLyDanhMuc");
            exit();
        }
        $ten = $_POST['ten_danh_muc'];
        $mo_ta = $_POST['mo_ta'];
        $trang_thai = $_POST['trang_thai'];

        // Cập nhật dữ liệu vào model
        $this->model->setData($ten, $mo_ta, $trang_thai, $id);
        
        if ($this->model->updateDanhMuc()) {
            $_SESSION['success'] = "Cập nhật danh mục thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật.";
        }
        
        // Quay về trang danh sách
        header("Location: index.php?act=QuanLyDanhMuc");
        exit();
    }
}
}
?>  