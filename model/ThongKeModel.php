<?php
class ThongKeModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Thống kê doanh thu theo tuần (5 tuần gần nhất)
    public function getDoanhThuTheoTuan() {
        $query = "
            SELECT 
                WEEK(ngay_dat) as tuan, 
                YEAR(ngay_dat) as nam, 
                SUM(tong_tien) as doanh_thu 
            FROM don_hang 
            WHERE trang_thai_don_hang = 'Đã giao' 
            GROUP BY YEAR(ngay_dat), WEEK(ngay_dat) 
            ORDER BY nam DESC, tuan DESC 
            LIMIT 5
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thống kê 10 sản phẩm bán chạy nhất
    // Thống kê 10 sản phẩm bán chạy nhất
    public function getTopSanPhamBanChay() {
        $query = "
            SELECT 
                sp.ten_san_pham, 
                SUM(ct.so_luong) as tong_ban, 
                /* Sử dụng cột 'gia_luc_mua' từ bảng chi_tiet_don_hang (ct) */
                SUM(ct.so_luong * ct.gia_luc_mua) as doanh_thu 
            FROM chi_tiet_don_hang ct
            INNER JOIN bien_the_san_pham bt ON ct.id_bien_the = bt.id_bien_the
            INNER JOIN san_pham sp ON bt.id_san_pham = sp.id_san_pham
            INNER JOIN don_hang dh ON ct.id_don_hang = dh.id_don_hang
            WHERE dh.trang_thai_don_hang = 'Đã giao'
            GROUP BY sp.id_san_pham
            ORDER BY tong_ban DESC 
            LIMIT 10
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>