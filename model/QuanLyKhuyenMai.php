<?php
class QuanLyKhuyenMai {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Lấy tất cả chương trình khuyến mãi
    public function getAllKhuyenMai() {
        $query = "SELECT * FROM chuong_trinh_khuyen_mai ORDER BY id_khuyen_mai DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Thêm mới chương trình
    public function createKhuyenMai($ten_km, $phan_tram, $start, $end, $trang_thai, $banner) {
        $query = "INSERT INTO chuong_trinh_khuyen_mai (ten_km, phan_tram_giam, ngay_bat_dau, ngay_ket_thuc, trang_thai, hinh_anh_banner) 
                  VALUES (:ten, :phan_tram, :start, :end, :status, :banner)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':ten' => $ten_km,
            ':phan_tram' => $phan_tram,
            ':start' => $start,
            ':end' => $end,
            ':status' => $trang_thai,
            ':banner' => $banner
        ]);
    }

    // 3. Gán sản phẩm vào chương trình (Cập nhật bảng chi_tiet_khuyen_mai)
    public function addProductToKhuyenMai($id_km, $id_san_pham) {
       $check = "SELECT id_ctkm FROM chi_tiet_khuyen_mai WHERE id_khuyen_mai = ? AND id_san_pham = ?";
        $stmt = $this->conn->prepare($check);
        $stmt->execute([$id_km, $id_san_pham]);
        
        if ($stmt->rowCount() == 0) {
            $query = "INSERT INTO chi_tiet_khuyen_mai (id_khuyen_mai, id_san_pham) VALUES (?, ?)";
            return $this->conn->prepare($query)->execute([$id_km, $id_san_pham]);
        }
        return false;
    }

    // 4. Xóa sản phẩm khỏi chương trình
    public function removeProductFromKhuyenMai($id_ctkm) {
        $query = "DELETE FROM chi_tiet_khuyen_mai WHERE id_ctkm = ?";
        return $this->conn->prepare($query)->execute([$id_ctkm]);
    }

    // 5. Cập nhật trạng thái (Tắt/Bật)
    public function toggleStatus($id_km, $status) {
        $query = "UPDATE chuong_trinh_khuyen_mai SET trang_thai = ? WHERE id_khuyen_mai = ?";
        return $this->conn->prepare($query)->execute([$status, $id_km]);
    }
}
?>