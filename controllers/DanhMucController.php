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
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Khởi tạo các biến
        $danh_muc_goc = [];
        $danh_muc_con = [];
        $active = 0;
        $hidden = 0;

        // Chỉ duyệt 1 vòng lặp duy nhất cho tất cả
        foreach ($categories as $item) {
            // 1. Phân loại cha/con
            if (is_null($item['id_danh_muc_cha']) || $item['id_danh_muc_cha'] == 0) {
                $danh_muc_goc[] = $item;
            } else {
                $danh_muc_con[] = $item;
            }

            // 2. Đếm trạng thái
            if (isset($item['trang_thai'])) {
                $item['trang_thai'] == 1 ? $active++ : $hidden++;
            }
        }

        $total = count($categories);
        $total_parent = count($danh_muc_goc);

        // Bây giờ tất cả các biến này đều có sẵn trong file include
        include '../views/pages/admin/QuanLyDanhMuc.php';
    }
    private function them() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_cha = !empty($_POST['id_danh_muc_cha']) ? $_POST['id_danh_muc_cha'] : null;
        
            // Cập nhật setData để nhận thêm tham số thứ 5
            $this->model->setData(
                $_POST['ten_danh_muc'], 
                $_POST['mo_ta'], 
                $_POST['trang_thai'],
                null, // id = null vì là thêm mới
                $id_cha
            );
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
            $id_cha = !empty($_POST['id_danh_muc_cha']) ? $_POST['id_danh_muc_cha'] : null;
            $this->model->setData(
                $ten, 
                $mo_ta, 
                $trang_thai, 
                $id,    // id truyền vào đây
                $id_cha // id_cha truyền vào đây
            );
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