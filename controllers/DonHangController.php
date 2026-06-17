<?php

require_once '../model/DonHangModel.php';

class DonHangController
{
    private $db;
    private $donHangModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->donHangModel = new DonHangModel($db);
    }

    public function handle($act)
    {
        switch ($act) {

            case 'QuanLyDonHang':
                $this->danhSachDonHang();
                break;

            case 'ChiTietDonHang':
                $this->chiTietDonHang();
                break;

            case 'CapNhatTrangThaiDonHang':
                $this->capNhatTrangThai();
                break;

            case 'HuyDonHang':
                $this->huyDonHang();
                break;

            case 'XoaDonHang':
                $this->xoaDonHang();
                break;

            default:
                $this->danhSachDonHang();
                break;
        }
    }

    /**
     * DANH SÁCH ĐƠN HÀNG
     */
    private function danhSachDonHang()
    {
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';

        $limit = 10;
        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;

        $orders = $this->donHangModel->getFilteredOrders(
            $search,
            $status,
            $offset,
            $limit
        );
        foreach ($orders as &$order)
        {
            $order['chi_tiet'] =
                $this->donHangModel->getChiTietDonHang(
                    $order['id_don_hang']
                );
        }
        
        unset($order);        
        $totalOrders = $this->donHangModel->countFilteredOrders(
            $search,
            $status
        );

        $totalPages = ceil($totalOrders / $limit);

        include '../views/pages/admin/OrderAdmin.php';
    }

    /**
     * CHI TIẾT ĐƠN HÀNG
     */
    private function chiTietDonHang()
    {
        $id = $_GET['id'] ?? 0;

        $donHang = $this->donHangModel->getDonHangById($id);

        if (!$donHang) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng";
            header("Location:index.php?act=QuanLyDonHang");
            exit;
        }

        $chiTiet = $this->donHangModel->getChiTietDonHang($id);

        include '../views/pages/admin/OrderAdmin.php';
    }

    /**
     * CẬP NHẬT TRẠNG THÁI
     */
    private function capNhatTrangThai()
    {
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = $_POST['id_don_hang'];
            
            $order = $this->donHangModel->getDonHangById($id);

            if (!$order) {
                $_SESSION['error'] = "Không tìm thấy đơn hàng";
                header("Location:index.php?act=QuanLyDonHang");
                exit;
            }

            $current = trim($order['trang_thai_don_hang']);
            $newStatus = trim($_POST['trang_thai']);
            // CHẶN LUỒNG SAI
           $allowed = [
                'Chờ duyệt'   => ['Đã xác nhận', 'Đã hủy'],
                'Đã xác nhận' => ['Đang giao', 'Đã hủy'],
                'Đang giao'   => ['Đã giao'],
                'Đã giao'     => [],
                'Đã hủy'      => []
            ];
            

            if (!in_array($newStatus, $allowed[$current] ?? [])) {
                $_SESSION['error'] = "Không thể chuyển trạng thái từ '$current' sang '$newStatus'";
                header("Location:index.php?act=QuanLyDonHang");
                exit;
            }

            if ($this->donHangModel->updateTrangThai($id, $newStatus)) {
                
                $_SESSION['success'] = "Cập nhật trạng thái thành công";
            } else {
                $_SESSION['error'] = "Cập nhật thất bại";
            }

            header("Location:index.php?act=QuanLyDonHang");
            exit;
        }
    }

    /**
     * HỦY ĐƠN
     */
   private function huyDonHang()
    {
        $id = $_GET['id'] ?? 0;

        $donHang = $this->donHangModel->getDonHangById($id);

        if (!$donHang) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng";
            header("Location:index.php?act=QuanLyDonHang");
            exit;
        }

        if (in_array($donHang['trang_thai_don_hang'], ['Đang giao', 'Đã giao'])) {
            $_SESSION['error'] = "Không thể hủy đơn";
            header("Location:index.php?act=QuanLyDonHang");
            exit;
        }

        $this->db->beginTransaction();

        try {
            $chiTiet = $this->donHangModel->getChiTietDonHang($id);

            foreach ($chiTiet as $item) {
                $this->donHangModel->restoreTonKho(
                    $item['id_bien_the'],
                    $item['so_luong']
                );
            }

            $ok = $this->donHangModel->cancelDonHang($id);

            if (!$ok) {
                throw new Exception("Cancel fail");
            }

            $this->db->commit();

            $_SESSION['success'] = "Hủy đơn + hoàn kho thành công";

        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = $e->getMessage();
        }

        header("Location:index.php?act=QuanLyDonHang");
        exit;
    }
    /**
     * XÓA ĐƠN ĐÃ HỦY
     */
    private function xoaDonHang()
    {
        $id = $_POST['id_don_hang'] ?? 0;

        if (
            $this->donHangModel->deleteDonHang($id)
        ) {
            $_SESSION['success'] =
                "Đã xóa đơn hàng";
        } else {
            $_SESSION['error'] =
                "Chỉ được xóa đơn hàng đã hủy";
        }

        header("Location:index.php?act=QuanLyDonHang");
        exit;
    }
}