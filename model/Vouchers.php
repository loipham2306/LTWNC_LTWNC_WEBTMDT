<?php
class Vouchers {
    private $db;
    private $table_name = "voucher"; 
    private $table_voucher_nguoi_dung= "voucher_cua_nguoi_dung";
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
    // Xóa voucher
    public function xoaVoucher($id_voucher) {
        $sql = "DELETE FROM " . $this->table_name . " WHERE id_voucher = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_voucher]);
    }
    public function kiemTraVoucherDaDung($id) {
        $sql = "SELECT so_luong_da_dung FROM " . $this->table_name . " WHERE id_voucher = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($voucher && $voucher['so_luong_da_dung'] > 0);
    }
    public function luuVoucherVaoVi($id_tai_khoan, $id_voucher) {
        try {
            $this->db->beginTransaction();

            // Lưu vào ví
            $sql = "INSERT INTO " . $this->table_voucher_nguoi_dung . " (id_tai_khoan, id_voucher) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id_tai_khoan, $id_voucher]);

            // Tăng số lượng đã dùng trong bảng gốc
            $this->tangSoLuongDaDung($id_voucher);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // 2. Kiểm tra khách đã có mã này chưa
    public function kiemTraDaLuuVoucher($id_tai_khoan, $id_voucher) {
        $sql = "SELECT id FROM " . $this->table_voucher_nguoi_dung . " WHERE id_tai_khoan = ? AND id_voucher = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_tai_khoan, $id_voucher]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    // 3. Lấy danh sách voucher khách đang sở hữu (để hiện ở giỏ hàng/checkout)
    public function layVoucherCuaNguoiDung($id_tai_khoan) {
        $sql = "SELECT v.* FROM " . $this->table_name . " v
                JOIN " . $this->table_voucher_nguoi_dung . " vcn ON v.id_voucher = vcn.id_voucher
                WHERE vcn.id_tai_khoan = ? AND vcn.da_su_dung = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_tai_khoan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Khi thanh toán thành công, đánh dấu voucher đã dùng
    public function suDungVoucher($id_tai_khoan, $id_voucher) {
        $sql = "UPDATE " . $this->table_voucher_nguoi_dung . " 
                SET da_su_dung = 1 
                WHERE id_tai_khoan = ? AND id_voucher = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_tai_khoan, $id_voucher]);
    }
    // HỖ TRỢ & KIỂM TRA
    public function tangSoLuongDaDung($id_voucher) {
        $sql = "UPDATE " . $this->table_name . " SET so_luong_da_dung = so_luong_da_dung + 1 WHERE id_voucher = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_voucher]);
    }
        // Trong Vouchers.php
    public function layVoucherCuaTaiKhoan($id_tai_khoan) {
        // Lấy tất cả thông tin từ bảng voucher (v.*) và cột da_su_dung từ bảng trung gian (vnd.da_su_dung)
        $sql = "SELECT v.*, vnd.da_su_dung 
                FROM " . $this->table_voucher_nguoi_dung . " vnd
                JOIN " . $this->table_name . " v ON vnd.id_voucher = v.id_voucher
                WHERE vnd.id_tai_khoan = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_tai_khoan]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function capNhatTrangThaiVoucher($id_tai_khoan, $id_voucher, $trang_thai) {
        $sql = "UPDATE vi_voucher SET da_su_dung = :trang_thai 
                WHERE id_tai_khoan = :id_tai_khoan AND id_voucher = :id_voucher";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':trang_thai' => $trang_thai,
            ':id_tai_khoan' => $id_tai_khoan,
            ':id_voucher' => $id_voucher
        ]);
    }
}