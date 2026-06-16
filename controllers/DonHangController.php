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

            $id_don_hang = $_POST['id_don_hang'];
            $trang_thai = $_POST['trang_thai'];

            if (
                $this->donHangModel->updateTrangThai(
                    $id_don_hang,
                    $trang_thai
                )
            ) {
                $_SESSION['success'] =
                    "Cập nhật trạng thái thành công";
            } else {
                $_SESSION['error'] =
                    "Cập nhật trạng thái thất bại";
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

            if (in_array($donHang['trang_thai'], ['Đang giao', 'Hoàn thành'])) {
                $_SESSION['error'] = "Không thể hủy đơn khi đang giao hoặc đã hoàn thành";
                header("Location:index.php?act=QuanLyDonHang");
                exit;
            }

            if ($this->donHangModel->cancelDonHang($id)) {
                $_SESSION['success'] = "Đơn hàng đã được hủy";
            } else {
                $_SESSION['error'] = "Hủy thất bại";
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