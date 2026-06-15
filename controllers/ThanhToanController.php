<?php

require_once __DIR__ . '/../model/DonHangModel.php';
require_once __DIR__ . '/../model/GioHang.php';

class ThanhToanController
{
    private $db;
    private $donHangModel;
    private $gioHangModel;

    public function __construct($db)
    {
        $this->db = $db;

        $this->donHangModel = new DonHangModel($db);
        $this->gioHangModel = new GiaHang($db);
    }
    public function luuSanPhamThanhToan()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }    
        header('Content-Type: application/json');
        $selected = $_POST['selected_products'] ?? [];
        if(empty($selected)){
            echo json_encode([
                'status' => 'error',
                'message' => 'Vui lòng chọn sản phẩm.'
            ]);
            exit;
        }
        $_SESSION['checkout_items'] = [];
        foreach($selected as $id_bien_the){
            if(isset($_SESSION['cart'][$id_bien_the])){
               $item = $_SESSION['cart'][$id_bien_the];
                $item['id_bien_the'] = $id_bien_the;
                $_SESSION['checkout_items'][$id_bien_the] = $item;
            }
        }
        echo json_encode([
            'status' => 'success'
        ]);
        exit;
    }
    // HIỂN THỊ TRANG THANH TOÁN
    public function showCheckout(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }    
        if(
            !isset($_SESSION['checkout_items'])
            || empty($_SESSION['checkout_items'])
        ){
            header("Location:index.php?act=GioHang");
            exit;
        }
        $checkoutItems = $_SESSION['checkout_items'];
        $userInfo = $_SESSION['user'] ?? [];
        $checkoutItems = $_SESSION['checkout_items'];
        include '../views/pages/Checkout.php';
    }
    public function xuLyThanhToan()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
           // KIỂM TRA ĐĂNG NHẬP
            if (!isset($_SESSION['user']['id_tai_khoan'])) {
                throw new Exception('Vui lòng đăng nhập.');
            }
            $id_khach_hang = $_SESSION['user']['id_tai_khoan'];

           // KIỂM TRA GIỎ HÀNG

            if (
                !isset($_SESSION['checkout_items']) ||
                empty($_SESSION['checkout_items'])
            ) {
                throw new Exception('Không có sản phẩm để thanh toán.');
            }

            $ten_nguoi_nhan = trim($_POST['ten_nguoi_nhan'] ?? '');
            $sdt_nguoi_nhan = trim($_POST['sdt_nguoi_nhan'] ?? '');
            $dia_chi_giao_hang = trim($_POST['dia_chi_giao_hang'] ?? '');
            $ghi_chu = trim($_POST['ghi_chu'] ?? '');

            if (
                empty($ten_nguoi_nhan) ||
                empty($sdt_nguoi_nhan) ||
                empty($dia_chi_giao_hang)
            ) {
                throw new Exception('Vui lòng nhập đầy đủ thông tin giao hàng.');
            }

            if (!preg_match('/^[0-9]{10,11}$/', $sdt_nguoi_nhan)) {
                throw new Exception('Số điện thoại không hợp lệ.');
            }
           // TÍNH LẠI TỔNG TIỀN
            $tong_tien = 0;
            foreach ($_SESSION['checkout_items'] as $item) {
                $gia = $item['gia'] ?? 0;
                $so_luong = $item['so_luong'] ?? 0;
                $tong_tien += ($gia * $so_luong);
            }
            if ($tong_tien <= 0) {
                throw new Exception('Tổng tiền không hợp lệ.');
            }
            //BẮT ĐẦU TRANSACTION
            $this->db->beginTransaction();
           //KIỂM TRA TỒN KHO LẦN CUỐI
            foreach ($_SESSION['checkout_items'] as $item) {

                $id_bien_the = $item['id_bien_the'];
                $so_luong = $item['so_luong'];

                $ton_kho = $this->donHangModel->getSoLuongTon($id_bien_the);

                if ($ton_kho <= 0) {
                    throw new Exception(
                        $item['ten_san_pham'] . ' đã hết hàng.'
                    );
                }

                if ($so_luong > $ton_kho) {
                    throw new Exception(
                        $item['ten_san_pham'] .
                        ' chỉ còn ' .
                        $ton_kho .
                        ' sản phẩm.'
                    );
                }
            }
 
           // TẠO ĐƠN HÀNG
            $dataDonHang = [
                ':id_khach_hang' => $id_khach_hang,
                ':id_voucher' => $_POST['id_voucher'] ?? null,
                ':tong_tien' => $tong_tien,
                ':pttt' => $_POST['phuong_thuc_thanh_toan'] ?? 'COD',
                ':ten' => $ten_nguoi_nhan,
                ':sdt' => $sdt_nguoi_nhan,
                ':dia_chi' => $dia_chi_giao_hang,
                ':ghi_chu' => $ghi_chu
            ];

            $id_don_hang = $this->donHangModel->createDonHang(
                $dataDonHang
            );

            if (!$id_don_hang) {
                throw new Exception('Không thể tạo đơn hàng.');
            }
            //THÊM CHI TIẾT ĐƠN HÀNG
            foreach ($_SESSION['checkout_items'] as $item) {

                $this->donHangModel->addChiTietDonHang([
                    ':id_don_hang' => $id_don_hang,
                    ':id_bien_the' => $item['id_bien_the'],
                    ':so_luong' => $item['so_luong'],
                    ':don_gia' => $item['gia']
                ]);

                $success = $this->donHangModel->updateTonKho(
                    $item['id_bien_the'],
                    $item['so_luong']
                );

                if (!$success) {
                    throw new Exception(
                        'Không thể cập nhật tồn kho.'
                    );
                }
            }
           // XÓA GIỎ HÀNG DB
            $id_gio_hang = $this->gioHangModel
                ->getGioHangId($id_khach_hang);

            $this->gioHangModel
                ->clearCart($id_gio_hang);

            //XÓA GIỎ HÀNG SESSION

           unset($_SESSION['checkout_items']);
           // COMMIT

            $this->db->commit();

            echo json_encode([
                'status' => 'success',
                'message' => 'Đặt hàng thành công.',
                'id_don_hang' => $id_don_hang
            ]);
        } catch (Exception $e) {

            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}