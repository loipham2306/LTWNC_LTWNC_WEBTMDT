<?php
    require_once __DIR__.'/../model/BinhLuanVaDanhGia.php';
    require_once __DIR__.'/../config/database.php';
    class BinhLuanVaDanhGiaController{
        private $db;
        private $model;

        public function __construct($db) {
            $this->db = $db;
            $this->model = new BinhLuanVaDanhGia($this->db);
        }
        public function handle($act)
        {
            switch ($act) {

                case 'QuanLyBinhLuan':
                    $this->indexAdmin();
                    break;
                case 'CapNhatTrangThaiBinhLuan':
                    $this->capNhatTrangThai();
                    break;

                case 'XoaBinhLuan':
                    $this->XoaBinhLuan();
                    break;

                default:
                    $this->indexAdmin();
                    break;
            }
        }
        public function indexAdmin() {
            $danhSachBinhLuan = $this->model->layTatCaBinhLuan();
            include __DIR__ . '/../views/pages/admin/QuanLyBinhLuanDanhGia.php';
        }
        public function CapNhatTrangThai() {
            $id = $_GET['id'] ?? null;
            $trang_thai = $_GET['trang_thai'] ?? null;

            if ($id && ($trang_thai == 0 || $trang_thai == 1)) {
                if ($this->model->capNhatTrangThai($id, $trang_thai)) {
                    $_SESSION['success'] = "Cập nhật trạng thái thành công!";
                } else {
                    $_SESSION['error'] = "Có lỗi xảy ra.";
                }
            }
            header("Location: index.php?act=QuanLyBinhLuan");
            exit();
        }
        public function XoaBinhLuan() {
            $id = $_GET['id'] ?? null;
            if ($id) {
                if ($this->model->xoaBinhLuan($id)) {
                    $_SESSION['success'] = "Đã xóa bình luận.";
                } else {
                    $_SESSION['error'] = "Không thể xóa bình luận.";
                }
            }
            header("Location: index.php?act=QuanLyBinhLuan");
            exit();
        }
    }

?>