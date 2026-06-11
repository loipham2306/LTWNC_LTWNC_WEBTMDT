<?php
class Vouchers {
    private $db;
    private $table_name = "voucher"; 

    public function __construct($db) {
        $this->db = $db;
    }

    // Lấy danh sách tất cả voucher
    public function layTatCaVoucher() {
        $sql = "SELECT * FROM " . $this->table_name . " ORDER BY id_voucher DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lấy thông tin voucher theo mã (để kiểm tra khi khách nhập)
    public function layVoucherTheoMa($ma_voucher) {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE ma_voucher = ? AND trang_thai = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ma_voucher]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function capNhatVoucher($data) {
        $sql = "UPDATE " . $this->table_name . " 
                SET ma_voucher = ?, loai_giam_gia = ?, gia_tri_giam = ?, 
                    ngay_het_han = ?, so_luong_ma = ?, don_toi_thieu = ? 
                WHERE id_voucher = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['ma_voucher'],
            $data['loai_giam_gia'],
            $data['gia_tri_giam'],
            $data['ngay_het_han'],
            $data['so_luong_ma'],
            $data['don_toi_thieu'],
            $data['id_voucher'] // Quan trọng để xác định voucher nào cần sửa
        ]);
    }
    public function layVoucherTheoId($id_voucher) {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE id_voucher = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_voucher]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Thêm mới voucher
    public function themVoucher($data) {
        $sql = "INSERT INTO " . $this->table_name . " (ma_voucher, loai_giam_gia, gia_tri_giam, ngay_het_han, so_luong_ma, don_toi_thieu) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['ma_voucher'],
            $data['loai_giam_gia'],
            $data['gia_tri_giam'],
            $data['ngay_het_han'],
            $data['so_luong_ma'],
            $data['don_toi_thieu']
        ]);
    }

    // Tăng số lượng đã dùng khi khách hàng sử dụng thành công
    public function tangSoLuongDaDung($id_voucher) {
        $sql = "UPDATE " . $this->table_name . " SET so_luong_da_dung = so_luong_da_dung + 1 WHERE id_voucher = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_voucher]);
    }

    // Xóa voucher
    public function xoaVoucher($id_voucher) {
        $sql = "DELETE FROM " . $this->table_name . " WHERE id_voucher = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_voucher]);
    }
    public function kiemTraVoucherDaDung($id) {
        $sql = "SELECT so_luong_da_dung FROM vouchers WHERE id_voucher = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $voucher = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Nếu số lượng đã dùng > 0 thì trả về true (đã có người dùng)
        return ($voucher && $voucher['so_luong_da_dung'] > 0);
    }
}