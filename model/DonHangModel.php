<?php

class DonHangModel
{
    private $conn;
    private $table_don_hang = 'don_hang';
    private $table_chi_tiet_don_hang = 'chi_tiet_don_hang';

    public function __construct($db)
    {
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    // Trong DonHangModel.php
    public static function generateVietQR($orderId, $amount, $content) {
        $bank_id = "MB"; // Sử dụng mã chuẩn của MB Bank
        $account_no = "0388046213";
        $account_name = "NGUYEN MINH LUAN";
        
        // Đảm bảo amount không chứa dấu phẩy hoặc ký tự lạ
        $amount = (int)$amount; 
        
        return "https://img.vietqr.io/image/{$bank_id}-{$account_no}-compact.png?amount={$amount}&addInfo=" . urlencode($content) . "&accountName=" . urlencode($account_name);
    }
    // ĐƠN HÀNG
    public function createDonHang($data)
    {
        $sql = "INSERT INTO {$this->table_don_hang}
                (id_khach_hang, id_voucher, tong_tien, phuong_thuc_thanh_toan, trang_thai_thanh_toan, ten_nguoi_nhan, sdt_nguoi_nhan, dia_chi_giao_hang, ghi_chu)
                VALUES (:id_khach_hang, :id_voucher, :tong_tien, :pttt, :trang_thai_thanh_toan, :ten, :sdt, :dia_chi, :ghi_chu)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id_khach_hang'          => $data['id_khach_hang'],
            ':id_voucher'             => $data['id_voucher'] ?? null,
            ':tong_tien'              => $data['tong_tien'],
            ':pttt'                   => $data['phuong_thuc_thanh_toan'],
            ':trang_thai_thanh_toan'  => $data['trang_thai_thanh_toan'] ?? 'Chưa thanh toán',
            ':ten'                    => $data['ten_nguoi_nhan'],
            ':sdt'                    => $data['sdt_nguoi_nhan'],
            ':dia_chi'                => $data['dia_chi_giao_hang'],
            ':ghi_chu'                => $data['ghi_chu'] ?? ''
        ]);

        return $this->conn->lastInsertId();
    }
    public function addChiTietDonHang($data)
    {
        // Cập nhật tên cột SQL khớp với database: gia_luc_mua và thêm id_san_pham
        $sql = "INSERT INTO {$this->table_chi_tiet_don_hang} 
                (id_don_hang, id_bien_the, id_san_pham, so_luong, gia_luc_mua)
                VALUES (:id_don_hang, :id_bien_the, :id_san_pham, :so_luong, :gia_luc_mua)";
                
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
    public function getFilteredOrders($search = '', $status = '', $offset = 0, $limit = 10) {
        // 1. Xây dựng điều kiện WHERE động
        $where = [];
        $params = [];

        if (!empty($search)) {
            $where[] = "(id_don_hang LIKE :search OR ten_nguoi_nhan LIKE :search OR sdt_nguoi_nhan LIKE :search)";
            $params[':search'] = "%$search%";
        }

        if (!empty($status)) {
            $where[] = "trang_thai_don_hang = :status";
            $params[':status'] = $status;
        }

        $whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        // 2. Câu lệnh SQL
        $sql = "SELECT * FROM {$this->table_don_hang} 
                $whereSql 
                ORDER BY ngay_dat DESC 
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        
        // Bind các biến phụ
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Phương thức đếm để phục vụ phân trang
    public function countFilteredOrders($search = '', $status = '') {
        $where = [];
        $params = [];
        if (!empty($search)) {
            $where[] = "(id_don_hang LIKE :search OR ten_nguoi_nhan LIKE :search)";
            $params[':search'] = "%$search%";
        }
        if (!empty($status)) {
            $where[] = "trang_thai_don_hang = :status";
            $params[':status'] = $status;
        }
        $whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $sql = "SELECT COUNT(*) FROM {$this->table_don_hang} $whereSql";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    public function getDonHangById($id)
    {
        $sql = "SELECT * FROM {$this->table_don_hang} WHERE id_don_hang = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getDonHangByKhachHang($id_khach_hang)
    {
        $sql = "SELECT * FROM {$this->table_don_hang} WHERE id_khach_hang = ? ORDER BY ngay_dat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_khach_hang]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // CHI TIẾT ĐƠN HÀNG
    public function getChiTietDonHang($id_don_hang){
        $sql = "SELECT ct.*, bt.kich_co, bt.mau_sac, bt.hinh_anh_bien_the, sp.ten_san_pham
                FROM {$this->table_chi_tiet_don_hang} ct
                INNER JOIN bien_the_san_pham bt ON ct.id_bien_the = bt.id_bien_the
                INNER JOIN san_pham sp ON bt.id_san_pham = sp.id_san_pham
                WHERE ct.id_don_hang = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_don_hang]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // TỒN KHO
    public function getSoLuongTon($id_bien_the){
        $sql = "
            SELECT so_luong_ton
            FROM bien_the_san_pham
            WHERE id_bien_the = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_bien_the]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['so_luong_ton'] : 0;
    }
    public function checkTonKho($id_bien_the, $so_luong){
        if ($so_luong <= 0) {
            return false;
        }

        return $this->getSoLuongTon($id_bien_the) >= $so_luong;
    }
    public function updateTonKho($id_bien_the, $so_luong){
        if ($so_luong <= 0) {
            return false;
        }
        $sql = "
            UPDATE bien_the_san_pham
            SET so_luong_ton = so_luong_ton - ?
            WHERE id_bien_the = ?
            AND so_luong_ton >= ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $so_luong,
            $id_bien_the,
            $so_luong
        ]);
        return $stmt->rowCount() > 0;
    }
    public function restoreTonKho($id_bien_the, $so_luong){
        $sql = "
            UPDATE bien_the_san_pham
            SET so_luong_ton = so_luong_ton + ?
            WHERE id_bien_the = ?
        ";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $so_luong,
            $id_bien_the
        ]);
    }
    // Thêm vào trong class DonHangModel
    public function getDonHangByKhachHangId($id_khach_hang) {
        $sql = "SELECT * FROM {$this->table_don_hang} 
                WHERE id_khach_hang = :id_khach_hang 
                ORDER BY ngay_dat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_khach_hang' => $id_khach_hang]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
     // TRẠNG THÁI ĐƠN HÀNG
    public function updateTrangThai($id_don_hang, $trang_thai){
        $sql = "UPDATE {$this->table_don_hang} SET trang_thai_don_hang = ? WHERE id_don_hang = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$trang_thai, $id_don_hang]);
    }
    public function cancelDonHang($id)
    {
        // LẤY ĐÚNG CỘT
        $sql = "SELECT trang_thai_don_hang FROM don_hang WHERE id_don_hang = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $current = $stmt->fetchColumn();

        if (!$current) return false;

        // CHẶN HỦY
        if (in_array($current, ['Đang giao', 'Hoàn thành'])) {
            return false;
        }

        // UPDATE ĐÚNG CỘT
        $sql = "UPDATE don_hang SET trang_thai_don_hang = 'Hủy' WHERE id_don_hang = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$id]);
    }
     //THỐNG KÊ
    public function countDonHang() {
        return $this->conn->query("SELECT COUNT(*) FROM {$this->table_don_hang}")->fetchColumn();
    }

    public function getTongDoanhThu(){
        $sql = "SELECT SUM(tong_tien) FROM {$this->table_don_hang} WHERE trang_thai_don_hang = 'Đã giao'";
        return $this->conn->query($sql)->fetchColumn();
    }
    //XÓA ĐƠN HÀNG
    public function deleteDonHang($id_don_hang){
        $donHang = $this->getDonHangById($id_don_hang);
        if (!$donHang || $donHang['trang_thai_don_hang'] !== 'Đã hủy') return false;
        
        try {
            $this->conn->beginTransaction();
            $stmt1 = $this->conn->prepare("DELETE FROM {$this->table_chi_tiet_don_hang} WHERE id_don_hang = ?");
            $stmt1->execute([$id_don_hang]);
            
            $stmt2 = $this->conn->prepare("DELETE FROM {$this->table_don_hang} WHERE id_don_hang = ?");
            $stmt2->execute([$id_don_hang]);
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    public function getIdSanPhamByBienThe($id_bien_the) {
        $sql = "SELECT id_san_pham FROM bien_the_san_pham WHERE id_bien_the = :id_bien_the";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_bien_the' => $id_bien_the]);
        return $stmt->fetchColumn(); // Trả về giá trị id_san_pham
    }
    public function getVouchersByKhachHang($id_khach_hang) {
        try {
            // Truy vấn lấy các voucher của khách hàng mà còn hạn sử dụng
            $sql = "SELECT * FROM voucher 
                    WHERE id_khach_hang = :id_khach_hang 
                    AND trang_thai = 1 
                    AND ngay_ket_thuc >= CURDATE()";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id_khach_hang' => $id_khach_hang]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy voucher: " . $e->getMessage());
            return [];
        }
    }
    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(tong_tien)
                FROM {$this->table_don_hang}
                WHERE trang_thai_don_hang = 'Hoàn thành'";

        return $this->conn->query($sql)->fetchColumn() ?? 0;
    }
    public function countNewOrders()
    {
        $sql = "SELECT COUNT(*)
                FROM {$this->table_don_hang}
                WHERE trang_thai_don_hang = 'Chờ duyệt'";

        return $this->conn->query($sql)->fetchColumn() ?? 0;
    }
    public function countAllOrders()
    {
        $sql = "SELECT COUNT(*) FROM {$this->table_don_hang}";
        return $this->conn->query($sql)->fetchColumn() ?? 0;
    }
    public function countShippingOrders()
    {
        $sql = "SELECT COUNT(*)
                FROM {$this->table_don_hang}
                WHERE trang_thai_don_hang = 'Đang giao'";

        return $this->conn->query($sql)->fetchColumn() ?? 0;
    }
    public function countCancelledOrders()
    {
        $sql = "SELECT COUNT(*)
                FROM {$this->table_don_hang}
                WHERE trang_thai_don_hang = 'Hủy'";

        return $this->conn->query($sql)->fetchColumn() ?? 0;
    }
    public function getSoLuongTonByBienThe($id_bien_the) {
        // Truy vấn SQL của bạn
        $sql = "SELECT so_luong_ton FROM bien_the_san_pham WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id_bien_the]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['so_luong_ton'] : 0;
    }
    public function getRevenueByMonth()
        {
            $sql = "SELECT 
                        DATE_FORMAT(ngay_dat, '%Y-%m') as month,
                        SUM(tong_tien) as revenue
                    FROM {$this->table_don_hang}
                    WHERE trang_thai_don_hang = 'Hoàn thành'
                    GROUP BY DATE_FORMAT(ngay_dat, '%Y-%m')
                    ORDER BY month ASC
                    LIMIT 6";

            return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }
}