<?php
class BinhLuanVaDanhGia{
    private $db;
    // Bổ sung các thuộc tính khớp với bảng
    public $id_binh_luan;
    public $id_khach_hang;
    public $id_san_pham;
    public $so_sao;
    public $noi_dung;
    public $trang_thai;
    public $ngay_binh_luan;
    // --- GETTERS ---
    public function getIdBinhLuan() { return $this->id_binh_luan; }
    public function getIdKhachHang() { return $this->id_khach_hang; }
    public function getIdSanPham() { return $this->id_san_pham; }
    public function getSoSao() { return $this->so_sao; }
    public function getNoiDung() { return $this->noi_dung; }
    public function getTrangThai() { return $this->trang_thai; }
    public function getNgayBinhLuan() { return $this->ngay_binh_luan; }

    // --- SETTERS ---
    public function setIdBinhLuan($id) { $this->id_binh_luan = $id; }
    public function setIdKhachHang($id) { $this->id_khach_hang = $id; }
    public function setIdSanPham($id) { $this->id_san_pham = $id; }
    public function setSoSao($sao) { $this->so_sao = $sao; }
    public function setNoiDung($nd) { $this->noi_dung = $nd; }
    public function setTrangThai($tt) { $this->trang_thai = $tt; }
    public function setNgayBinhLuan($date) { $this->ngay_binh_luan = $date; }
    public function __construct($db) {
        $this->db = $db;
    }

    // 1. Lấy tất cả bình luận (Admin dùng)
    // Đã thêm so_sao và ngay_binh_luan vào danh sách lấy dữ liệu
    public function layTatCaBinhLuan() {
        $sql = "SELECT bl.*, CONCAT(kh.ho_ten_dem, ' ', kh.ten) AS ho_ten, sp.ten_san_pham 
            FROM binh_luan_va_danh_gia bl
            JOIN khach_hang kh ON bl.id_khach_hang = kh.id_khach_hang
            JOIN san_pham sp ON bl.id_san_pham = sp.id_san_pham
            ORDER BY bl.ngay_binh_luan DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Thêm bình luận mới (Cần thiết cho phía người dùng)
    public function themBinhLuan($id_kh, $id_sp, $so_sao, $noi_dung) {
        $sql = "INSERT INTO binh_luan_va_danh_gia (id_khach_hang, id_san_pham, so_sao, noi_dung, trang_thai) 
                VALUES (?, ?, ?, ?, 0)"; // Mặc định trạng thái là 1 (đã duyệt)
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_kh, $id_sp, $so_sao, $noi_dung]);
    }

    // 3. Cập nhật trạng thái duyệt/ẩn
    public function capNhatTrangThai($id, $trang_thai) {
        $sql = "UPDATE binh_luan_va_danh_gia SET trang_thai = ? WHERE id_binh_luan = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$trang_thai, $id]);
    }

    // 4. Xóa bình luận
    public function xoaBinhLuan($id) {
        $sql = "DELETE FROM binh_luan_va_danh_gia WHERE id_binh_luan = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // 5. Lấy bình luận theo sản phẩm (Dùng để hiển thị ở trang chi tiết sản phẩm)
    public function layBinhLuanTheoSanPham($id_san_pham) {
        $sql = "SELECT bl.*, CONCAT(kh.ho_ten_dem, ' ', kh.ten) AS ho_ten 
            FROM binh_luan_va_danh_gia bl
            JOIN khach_hang kh ON bl.id_khach_hang = kh.id_khach_hang
            WHERE bl.id_san_pham = ? AND bl.trang_thai = 1
            ORDER BY bl.ngay_binh_luan DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_san_pham]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}