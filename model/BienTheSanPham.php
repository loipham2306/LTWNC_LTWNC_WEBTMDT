<?php
class BienTheSanPham {
    private $conn;
    private $table_name = "bien_the_san_pham";

    public $id_bien_the;
    public $kich_co;
    public $mau_sac;
    public $gia_ban;
    public $so_luong_ton;
    public $hinh_anh_bien_the;
    public $trang_thai;
    public $id_san_pham;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Thêm một biến thể đơn lẻ
    public function ThemBienThe() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET id_san_pham = :id_sp, kich_co = :size, mau_sac = :mau, 
                      gia_ban = :gia, so_luong_ton = :sl, hinh_anh_bien_the = :anh, 
                      trang_thai = 1, ngay_tao = NOW()";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_sp' => $this->id_san_pham,
            ':size'  => $this->kich_co,
            ':mau'   => $this->mau_sac,
            ':gia'   => $this->gia_ban,
            ':sl'    => $this->so_luong_ton,
            ':anh'   => $this->hinh_anh_bien_the
        ]);
    }

    // Lấy tất cả biến thể của 1 sản phẩm
    public function LayBienTheTheoSanPham($id_san_pham) {
        $query = "SELECT id_bien_the,
            id_san_pham,
            kich_co,
            mau_sac,
            gia_ban,
            hinh_anh_bien_the,
            so_luong_ton AS stock
             FROM " . $this->table_name . " WHERE id_san_pham = :id_sp";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id_sp' => $id_san_pham]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Xóa tất cả biến thể của 1 sản phẩm (dùng khi cập nhật toàn bộ)
    public function XoaBienTheTheoSanPham($id_san_pham) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_san_pham = :id_sp";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id_sp' => $id_san_pham]);
    }
    // Cập nhật một biến thể cụ thể
    public function CapNhatBienThe() {
        $query = "UPDATE " . $this->table_name . " 
                  SET kich_co = :size, 
                      mau_sac = :mau, 
                      gia_ban = :gia, 
                      so_luong_ton = :sl, 
                      hinh_anh_bien_the = :anh, 
                      ngay_cap_nhat = NOW()
                  WHERE id_bien_the = :id_bt";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id_bt' => $this->id_bien_the,
            ':size'  => $this->kich_co,
            ':mau'   => $this->mau_sac,
            ':gia'   => $this->gia_ban,
            ':sl'    => $this->so_luong_ton,
            ':anh'   => $this->hinh_anh_bien_the
        ]);
    }
}
?>