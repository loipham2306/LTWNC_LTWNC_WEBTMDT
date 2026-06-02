<?php
class SanPham {
    private $conn;
    private $table_name = "san_pham";

    // Các thuộc tính ánh xạ
    public $id_san_pham;
    public $ten_san_pham;
    public $gia;
    public $giam_gia;
    public $mo_ta;
    public $hinh_anh;
    public $trang_thai;
    public $ngay_tao;
    public $ngay_cap_nhat;
    public $id_danh_muc;
    public $id_thuong_hieu;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ánh xạ dữ liệu từ mảng/đối tượng vào thuộc tính
    public function setData($ten, $gia, $giam, $mota, $img, $id_dm, $id_th, $trang_thai) {
        $this->ten_san_pham = $ten;
        $this->gia = $gia;
        $this->giam_gia = $giam;
        $this->mo_ta = $mota;
        $this->hinh_anh = $img;
        $this->id_danh_muc = $id_dm;
        $this->id_thuong_hieu = $id_th;
        $this->trang_thai = $trang_thai;
    }
    // Lấy tất cả sản phẩm
    public function LayTatCaSanPham() {
        $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu 
                  FROM " . $this->table_name . " sp
                  LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
                  LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id_thuong_hieu
                  ORDER BY sp.id_san_pham DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
   // THÊM SẢN PHẨM
    public function ThemSanPham() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET ten_san_pham=:ten, gia=:gia, giam_gia=:giam, mo_ta=:mota, 
                      hinh_anh=:hinh, id_danh_muc=:id_dm, id_thuong_hieu=:id_th, 
                      trang_thai=:tt, ngay_tao=NOW()";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            ':ten'  => $this->ten_san_pham,
            ':gia'  => $this->gia,
            ':giam' => $this->giam_gia,
            ':mota' => $this->mo_ta,
            ':hinh' => $this->hinh_anh,
            ':id_dm'=> $this->id_danh_muc,
            ':id_th'=> $this->id_thuong_hieu,
            ':tt'   => $this->trang_thai
        ]);
    }

    // CẬP NHẬT SẢN PHẨM
    public function CapNhatSanPham() {
        $query = "UPDATE " . $this->table_name . " 
                  SET ten_san_pham=:ten, gia=:gia, giam_gia=:giam, mo_ta=:mota, 
                      hinh_anh=:hinh, id_danh_muc=:id_dm, id_thuong_hieu=:id_th, 
                      trang_thai=:tt, ngay_cap_nhat=NOW()
                  WHERE id_san_pham=:id";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            ':ten'  => $this->ten_san_pham,
            ':gia'  => $this->gia,
            ':giam' => $this->giam_gia,
            ':mota' => $this->mo_ta,
            ':hinh' => $this->hinh_anh,
            ':id_dm'=> $this->id_danh_muc,
            ':id_th'=> $this->id_thuong_hieu,
            ':tt'   => $this->trang_thai,
            ':id'   => $this->id_san_pham
        ]);
    }

    // XÓA SẢN PHẨM
    public function XoaSanPham() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_san_pham = :id";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([':id' => $this->id_san_pham]);
    }
}
?>