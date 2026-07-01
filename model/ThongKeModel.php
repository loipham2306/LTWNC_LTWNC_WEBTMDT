<?php
class ThongKeModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Thống kê doanh thu theo tuần (5 tuần gần nhất)
    public function getDoanhThuTheoTuan()
    {
        $query = "
            SELECT
                WEEK(ngay_dat,1) as tuan,
                YEAR(ngay_dat) as nam,
                SUM(tong_tien) as doanh_thu
            FROM don_hang
            WHERE trang_thai_don_hang='Đã giao'
            GROUP BY YEAR(ngay_dat), WEEK(ngay_dat,1)
            ORDER BY nam DESC,tuan DESC
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
    // Thống kê doanh thu theo Tháng
        public function getDoanhThuTheoThang() {
            $query = "SELECT MONTH(ngay_dat) as thang, YEAR(ngay_dat) as nam, SUM(tong_tien) as doanh_thu 
                    FROM don_hang WHERE trang_thai_don_hang = 'Đã giao' 
                    GROUP BY YEAR(ngay_dat), MONTH(ngay_dat) ORDER BY nam DESC, thang DESC LIMIT 6";
            return $this->conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
        }

        // Thống kê Khách hàng chi tiêu nhiều nhất
        public function getTopKhachHang() {
            $query = "SELECT 
                        kh.ho_ten_dem, 
                        kh.ten, 
                        COUNT(dh.id_don_hang) as so_don, 
                        SUM(dh.tong_tien) as chi_tieu 
                    FROM khach_hang kh 
                    JOIN don_hang dh ON kh.id_khach_hang = dh.id_khach_hang 
                    WHERE dh.trang_thai_don_hang = 'Đã giao' 
                    GROUP BY kh.id_khach_hang 
                    ORDER BY chi_tieu DESC 
                    LIMIT 10";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    public function getThongKeSanPham()
    {
        $sql = "
            SELECT
                sp.id_san_pham,
                sp.ten_san_pham,
                sp.hinh_anh,
                COALESCE(SUM(ct.so_luong),0) AS da_ban,
                (
                    SELECT SUM(so_luong_ton)
                    FROM bien_the_san_pham bt2
                    WHERE bt2.id_san_pham = sp.id_san_pham
                ) AS ton_kho,
                COALESCE(SUM(ct.so_luong * ct.gia_luc_mua),0) AS doanh_thu,
                  CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM chi_tiet_khuyen_mai ctkm
                        INNER JOIN chuong_trinh_khuyen_mai km
                            ON km.id_khuyen_mai = ctkm.id_khuyen_mai
                        WHERE ctkm.id_san_pham = sp.id_san_pham
                        AND km.trang_thai = 1
                        AND CURDATE() BETWEEN km.ngay_bat_dau
                                        AND km.ngay_ket_thuc
                    )
                    THEN 1
                    ELSE 0
                END AS co_khuyen_mai
            FROM san_pham sp
            LEFT JOIN bien_the_san_pham bt
                ON sp.id_san_pham = bt.id_san_pham
            LEFT JOIN chi_tiet_don_hang ct
                ON bt.id_bien_the = ct.id_bien_the
            LEFT JOIN don_hang dh
                ON ct.id_don_hang = dh.id_don_hang
                AND dh.trang_thai_don_hang='Đã giao'
            GROUP BY sp.id_san_pham
            ORDER BY da_ban DESC
        ";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getThongKeBienThe()
    {
        $sql = "
            SELECT
                bt.id_bien_the,
                sp.ten_san_pham,
                bt.kich_co,
                bt.mau_sac,
                bt.so_luong_ton,
                COALESCE(SUM(ct.so_luong),0) AS da_ban,
                COALESCE(SUM(ct.so_luong*ct.gia_luc_mua),0) AS doanh_thu
            FROM bien_the_san_pham bt
            INNER JOIN san_pham sp
                ON bt.id_san_pham=sp.id_san_pham
            LEFT JOIN chi_tiet_don_hang ct
                ON bt.id_bien_the=ct.id_bien_the
            LEFT JOIN don_hang dh
                ON ct.id_don_hang=dh.id_don_hang
                AND dh.trang_thai_don_hang='Đã giao'
            GROUP BY bt.id_bien_the
            ORDER BY da_ban DESC
        ";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>