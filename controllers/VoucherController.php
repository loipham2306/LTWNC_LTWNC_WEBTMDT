<?php
require_once '../model/Vouchers.php';

class VoucherController {
    private $voucherModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->voucherModel = new Vouchers($db);
    }
    public function handle($act) {
        switch ($act) {
            // --- Voucher ---
            case 'QuanLyVoucher':
                $this->danhSach();
                break;
            case 'ThemVoucher':
                $this->themMoi();
                break;
            case 'SuaVoucher':
                $this->formSua();
                break;
            case 'CapNhatVoucher':
                $this->capNhat();
                break;
            case 'XoaVoucher':
                $this->xoa();
                break;

            // --- Mặc định nếu không tìm thấy trang ---
            default:
                echo "404 - Trang không tồn tại";
                break;
        }
    }
    // Hiển thị danh sách voucher cho Admin
    public function danhSach() {
        $danhSachVoucher = $this->voucherModel->layTatCaVoucher();
        include '../views/pages/admin/VoucherAdmin.php';
    }
    // Xử lý thêm voucher
    public function themMoi() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ma_voucher'    => $_POST['ma_voucher'],
                'loai_giam_gia' => $_POST['loai_giam_gia'],
                'gia_tri_giam'  => $_POST['gia_tri_giam'],
                'ngay_het_han'  => $_POST['ngay_het_han'],
                'so_luong_ma'   => $_POST['so_luong_ma'],
                'don_toi_thieu' => $_POST['don_toi_thieu']
            ];

            if ($this->voucherModel->themVoucher($data)) {
                $_SESSION['success'] = "Thêm voucher thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại.";
            }
            header("Location: index.php?act=QuanLyVoucher");
            exit();
        }
    }

    // Xử lý xóa voucher
    public function xoa() {
        if (isset($_GET['id_voucher'])) {
            $id = $_GET['id_voucher'];

            // Kiểm tra xem đã có người dùng chưa
            if ($this->voucherModel->kiemTraVoucherDaDung($id)) {
                $_SESSION['error'] = "Không thể xóa: Voucher này đã có người sử dụng!";
            } else {
                // Nếu chưa ai dùng thì mới cho xóa
                if ($this->voucherModel->xoaVoucher($id)) {
                    $_SESSION['success'] = "Đã xóa voucher thành công!";
                } else {
                    $_SESSION['error'] = "Có lỗi xảy ra khi xóa.";
                }
            }
        }
        header("Location: index.php?act=QuanLyVoucher");
        exit();
    }
    // Hiển thị form sửa
    public function formSua() {
        $id = $_GET['id_voucher'];
        $voucher = $this->voucherModel->layVoucherTheoId($id);
        include '../views/pages/admin/VoucherAdmin.php';
    }

    // Xử lý lưu sau khi sửa
    public function capNhat() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra xem biến 'id_voucher' đã được truyền qua chưa
            $id = $_POST['id_voucher'] ?? null;
            
            if ($id) {
                $data = [
                    'id_voucher'    => $id,
                    'ma_voucher'    => $_POST['ma_voucher'],
                    'loai_giam_gia' => $_POST['loai_giam_gia'],
                    'gia_tri_giam'  => $_POST['gia_tri_giam'],
                    'don_toi_thieu' => $_POST['don_toi_thieu'],
                    'ngay_het_han'  => $_POST['ngay_het_han'],
                    'so_luong_ma'   => $_POST['so_luong_ma']
                ];
                
                $this->voucherModel->capNhatVoucher($data);
                $_SESSION['success'] = "Cập nhật thành công!";
            }
            header("Location: index.php?act=QuanLyVoucher");
            exit();
        }
    }
}