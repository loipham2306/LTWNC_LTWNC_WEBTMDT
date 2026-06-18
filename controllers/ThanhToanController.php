<?php
require_once __DIR__ . '/../controllers/MailController.php';
require_once __DIR__ . '/../model/DonHangModel.php';
require_once __DIR__ . '/../model/GioHang.php';
require_once __DIR__.'/../model/Vouchers.php';

class ThanhToanController
{
    private $db;
    private $donHangModel;
    private $gioHangModel;
    private $voucher_model;
    public function __construct($db)
    {
        $this->db = $db;

        $this->donHangModel = new DonHangModel($db);
        $this->gioHangModel = new GioHang($db);
        $this->voucher_model = new Vouchers($db);
    }
    public function showThanhCong($id) {
        // 1. Truy vấn đơn hàng từ Model dựa trên ID
        $donHang = $this->donHangModel->getDonHangById($id);

        // 2. Kiểm tra nếu không tìm thấy đơn hàng thì xử lý lỗi
        if (!$donHang) {
            echo "Đơn hàng không tồn tại!";
            return;
        }
        include '../views/components/ThanhToanThanhCong.php';
    }
    public function luuSanPhamThanhToan()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }    
        header('Content-Type: application/json');
        $selected = $_POST['selected_products'] ?? [];
        
        if(empty($selected)){
            echo json_encode(['status' => 'error', 'message' => 'Vui lòng chọn sản phẩm.']);
            exit;
        }

        $_SESSION['checkout_items'] = [];
        foreach($selected as $id_bien_the){
            if(isset($_SESSION['cart'][$id_bien_the])){
                $item = $_SESSION['cart'][$id_bien_the];
                $item['id_bien_the'] = $id_bien_the;
                error_log(print_r($item, true));
                // KIỂM TRA VÀ GÁN id_san_pham
                if (!isset($item['id_san_pham'])) {
                    // Giả sử bạn có hàm này trong Model để lấy id_san_pham từ id_bien_the
                    $item['id_san_pham'] = $this->donHangModel->getIdSanPhamByBienThe($id_bien_the);
                }
                
                $_SESSION['checkout_items'][$id_bien_the] = $item;
            }
        }
        
        echo json_encode(['status' => 'success']);
        exit;
    }
    // HIỂN THỊ TRANG THANH TOÁN
    public function showCheckout(){
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }    
        if(!isset($_SESSION['checkout_items']) || empty($_SESSION['checkout_items'])){
            header("Location:index.php?act=GioHang");
            exit;
        }

        $checkoutItems = $_SESSION['checkout_items'];
        $userInfo = $_SESSION['user'] ?? [];
        
        $id_khach_hang =$_SESSION['user']['id_tai_khoan'];
        $danhSachVoucher = [];
        if ($id_khach_hang) {
            $danhSachVoucher = $this->voucher_model->layVoucherCuaTaiKhoan($id_khach_hang);
        } else {
            $danhSachVoucher = [];
        }
        // -----------------------

        include '../views/pages/Checkout.php';
    }
    // Trong ThanhToanController (hàm xử lý đặt hàng)

    public function xuLyThanhToan()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            // 1. Kiểm tra đăng nhập và giỏ hàng
            if (!isset($_SESSION['user']['id_tai_khoan'])) throw new Exception('Vui lòng đăng nhập.');
            if (empty($_SESSION['checkout_items'])) throw new Exception('Giỏ hàng trống.');
            if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 'admin') {
                throw new Exception('Tài khoản Admin không được phép mua hàng.');
            }
            // 2. Lấy và kiểm tra dữ liệu từ POST
            $ten_nguoi_nhan = trim($_POST['ten_nguoi_nhan'] ?? '');
            $sdt_nguoi_nhan = trim($_POST['sdt_nguoi_nhan'] ?? '');
            $dia_chi_giao_hang = trim($_POST['dia_chi_giao_hang'] ?? '');
            $ghi_chu = trim($_POST['ghi_chu'] ?? '');

            if (empty($ten_nguoi_nhan) || empty($sdt_nguoi_nhan) || empty($dia_chi_giao_hang)) {
                throw new Exception('Vui lòng nhập đầy đủ thông tin giao hàng.');
            }

            if (!preg_match('/^[0-9]{10,11}$/', $sdt_nguoi_nhan)) {
                throw new Exception('Số điện thoại không hợp lệ.');
            }

            // 3. Tính tổng tiền
           $tong_goc = 0;

            foreach ($_SESSION['checkout_items'] as $item) {
                $tong_goc += ($item['gia'] ?? 0) * ($item['so_luong'] ?? 0);
            }

            $id_voucher_input = $_POST['id_voucher'] ?? null;
            $giam = 0;

            if (!empty($id_voucher_input)) {

                $voucher = $this->voucher_model->layVoucherTheoId($id_voucher_input);

                if (!$voucher) {
                    throw new Exception("Voucher không tồn tại");
                }

                //  hết hạn
                if (!empty($voucher['ngay_het_han']) && strtotime($voucher['ngay_het_han']) < time()) {
                    throw new Exception("Voucher đã hết hạn");
                }

                // đơn tối thiểu
                if ($tong_goc < $voucher['don_toi_thieu']) {
                    throw new Exception("Đơn chưa đủ điều kiện áp dụng voucher");
                }

                //  hết lượt
                if ($voucher['so_luong_da_dung'] >= $voucher['so_luong_ma']) {
                    throw new Exception("Voucher đã hết lượt sử dụng");
                }

                // ✔ tính giảm
                if ($voucher['loai_giam_gia'] === 'percent') {
                    $giam = ($tong_goc * $voucher['gia_tri_giam']) / 100;
                } else {
                    $giam = $voucher['gia_tri_giam'];
                }
            }

            $tong_tien = max(0, $tong_goc - $giam);
            // 4. Bắt đầu giao dịch
            $this->db->beginTransaction();

            // 5. Kiểm tra tồn kho
            foreach ($_SESSION['checkout_items'] as $item) {
                $ton_kho = $this->donHangModel->getSoLuongTon($item['id_bien_the']);
                if ($ton_kho <= 0 || $item['so_luong'] > $ton_kho) {
                    throw new Exception($item['ten_san_pham'] . ' không đủ hàng.');
                }
            }
            // XỬ LÝ LỖI KHÓA NGOẠI: Nếu chuỗi rỗng thì chuyển thành NULL
            if (empty($id_voucher_input)) {
                $id_voucher_input = null;
            }
           $id_tai_khoan = $_SESSION['user']['id_tai_khoan'];
            $stmt = $this->db->prepare("SELECT id_khach_hang FROM khach_hang WHERE id_tai_khoan = ?");
            $stmt->execute([$id_tai_khoan]);
            $khach_hang = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$khach_hang) {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy thông tin khách hàng tương ứng.']);
                exit;
            }
            $id_khach_hang_chuan = $khach_hang['id_khach_hang'];
            $dataDonHang = [
                'id_khach_hang'         => $id_khach_hang_chuan, // SỬ DỤNG ID ĐÃ PHIÊN DỊCH (SỐ 4)
                'id_voucher'            => $id_voucher_input,                    
                'tong_tien'             => $tong_tien,
                'phuong_thuc_thanh_toan' => $_POST['phuong_thuc_thanh_toan'],
                'trang_thai_thanh_toan'  => ($_POST['phuong_thuc_thanh_toan'] == 'bank') ? 'Chưa thanh toán' : 'Không áp dụng',
                'ten_nguoi_nhan'         => $ten_nguoi_nhan,
                'sdt_nguoi_nhan'         => $sdt_nguoi_nhan,
                'dia_chi_giao_hang'      => $dia_chi_giao_hang,
                'ghi_chu'                => $ghi_chu
            ];

            $id_don_hang = $this->donHangModel->createDonHang($dataDonHang);
            
            if (!$id_don_hang) {
                echo json_encode(['status' => 'error', 'message' => 'Lỗi tạo giỏ hàng...']);
                exit;
            }
            if (!empty($id_voucher_input)) {
                $this->voucher_model->setVoucherDaSuDung(
                    $id_tai_khoan,
                    $id_voucher_input
                );
            }
            // 7. Thêm chi tiết và cập nhật tồn kho
           foreach ($_SESSION['checkout_items'] as $item) {
                $this->donHangModel->addChiTietDonHang([
                    ':id_don_hang'  => $id_don_hang,
                    ':id_bien_the'  => $item['id_bien_the'],
                    ':id_san_pham'  => $item['id_san_pham'], // Phải có trường này vì cấu trúc bảng có
                    ':so_luong'     => $item['so_luong'],
                    ':gia_luc_mua'  => $item['gia']          // Đổi tên key khớp với SQL
                ]);
                
                $this->donHangModel->updateTonKho($item['id_bien_the'], $item['so_luong']);
            }

            // 8. Xóa giỏ hàng
            $id_gio_hang = $this->gioHangModel->getGioHangId($id_khach_hang_chuan);
            if ($id_gio_hang) {
                $this->gioHangModel->clearCart($id_gio_hang);
            }
            unset($_SESSION['checkout_items']);

            $this->db->commit();
            $donHang = $this->donHangModel->getDonHangById($id_don_hang);
            $chiTiet = $this->donHangModel->getChiTietDonHang($id_don_hang);

            $email = $_SESSION['user']['email'] ?? null;

            if ($email) {
                MailController::sendOrderConfirmation($email, $donHang, $chiTiet, $tong_tien, $giam);
            }
            // 9. Trả về phản hồi thành công
            echo json_encode([
                'status' => 'success',
                'message' => 'Đặt hàng thành công.',
                'id_don_hang' => $id_don_hang,
                'redirect' => ($_POST['phuong_thuc_thanh_toan'] == 'bank')
                    ? 'index.php?act=ThanhToanBank&id=' . $id_don_hang
                    : 'index.php?act=ThanhToanThanhCong&id=' . $id_don_hang            ]);

        } catch (Exception $e) {
           if ($this->db->inTransaction()) $this->db->rollBack();

            // Thay vì chỉ báo lỗi, hãy log lỗi thật sự vào console hoặc file
            error_log("Lỗi đặt hàng: " . $e->getMessage()); 
            
            echo json_encode([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage() // Xem chi tiết lỗi ở đây trên giao diện
            ]);
        }
    }
    
}