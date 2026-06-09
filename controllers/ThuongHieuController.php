<?php
include_once '../model/ThuongHieu.php';

class ThuongHieuController {
    private $db;
    private $th_model;

    public function __construct($db) {
        $this->db = $db;
        $this->th_model = new ThuongHieu($db);
    }

    // Điểm tiếp nhận duy nhất cho các hành động của Thương Hiệu
    public function handle($act) {
        switch ($act) {
            case 'QuanLyThuongHieu':
                $this->danhSach();
                break;
            case 'themTH': // Thay vì xl_themTH, ta gọi ngắn gọn là themTH
                $this->xuLyThem();
                break;
            case 'suaTH':
                $this->xuLySua();
                break;
            case 'xoaTH':
                $this->xuLyXoa();
                break;
            default:
                $this->danhSach();
                break;
        }
    } 
    private function danhSach() {
        $brands = $this->th_model->getTatCaThuongHieu();
        include "../views/pages/admin/QuanLyThuongHieu.php";
    }
                      
    private function xuLyThem() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $hinh_anh = $this->uploadFile();
            
            $this->th_model->setData(
                $_POST['ten_thuong_hieu'], 
                $_POST['mo_ta'], 
                $hinh_anh, 
                $_POST['trang_thai']
            );

            if ($this->th_model->createThuongHieu()) {
                $_SESSION['success'] = "Thêm thành công!";
            } else {
                $_SESSION['error'] = "Thêm thất bại!";
            }
            header('Location: index.php?act=thuonghieu');
            exit();
        }
    }

    private function xuLySua() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $hinh_anh = $this->uploadFile();
            
            // Nếu không chọn ảnh mới, lấy ảnh cũ từ hidden input
            if (empty($hinh_anh)) {
                $hinh_anh = $_POST['old_image'] ?? '';
            }

            $this->th_model->setData(
                $_POST['ten_thuong_hieu'], 
                $_POST['mo_ta'], 
                $hinh_anh, 
                $_POST['trang_thai'], 
                $_POST['id_thuong_hieu']
            );

            if ($this->th_model->updateThuongHieu()) {
                $_SESSION['success'] = "Cập nhật thành công!";
            } else {
                $_SESSION['error'] = "Cập nhật thất bại!";
            }
            header('Location: index.php?act=QuanLyThuongHieu');
            exit();
        }
    }

    private function xuLyXoa() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_thuong_hieu'])) {
            try {
                $id = $_POST['id_thuong_hieu'];
                
                // Thiết lập ID vào model
                $this->th_model->setIdThuongHieu($id);
                
                // Thực hiện xóa
                if ($this->th_model->deleteThuongHieu()) {
                    $_SESSION['success'] = "Xóa thương hiệu thành công!";
                } else {
                    // Nếu xóa không thành công mà không có lỗi DB, ta throw Exception
                    throw new Exception("Không thể xóa thương hiệu này.");
                }
            } catch (PDOException $e) {
                // Kiểm tra mã lỗi 23000 (Integrity constraint violation - lỗi khóa ngoại)
                if ($e->getCode() == '23000') {
                    $_SESSION['error'] = "Không thể xóa: Thương hiệu này đang chứa sản phẩm!";
                } else {
                    $_SESSION['error'] = "Lỗi Database: " . $e->getMessage();
                }
            } catch (Exception $e) {
                // Bắt các lỗi logic thông thường
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        header("Location: index.php?act=QuanLyThuongHieu");
        exit();
    }

    private function uploadFile() {
        if (isset($_FILES['hinh_anh_logo_file']) && $_FILES['hinh_anh_logo_file']['error'] == 0) {
            $targetDir = "../assets/images/brands/";
            $fileName = time() . '_' . basename($_FILES['hinh_anh_logo_file']['name']);
            if (move_uploaded_file($_FILES['hinh_anh_logo_file']['tmp_name'], $targetDir . $fileName)) {
                return $fileName;
            }
        }
        return '';
    }
}