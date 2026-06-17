<?php
class GiaHang {
    private $conn;
    private $table_gio_hang = 'gio_hang';
    private $table_chi_tiet = 'chi_tiet_gio_hang';

    public function __construct($db) {
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function createGioHang($id_khach_hang)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO ".$this->table_gio_hang." (id_khach_hang, ngay_tao)
            VALUES (?, NOW())
        ");
        $stmt->execute([$id_khach_hang]);

        return $this->conn->lastInsertId();
    }
    // 1. Lấy hoặc tạo mới giỏ hàng cho khách hàng
    public function getGioHangId($id_khach_hang) {

        // chỉ check giỏ hàng thôi, không check bảng khach_hang
        $query = "SELECT id_gio_hang FROM gio_hang WHERE id_khach_hang = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_khach_hang]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row['id_gio_hang'];
        }

        // tạo giỏ hàng mới
        $query = "INSERT INTO gio_hang (id_khach_hang, ngay_tao) VALUES (?, NOW())";
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute([$id_khach_hang]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            die("Lỗi tạo giỏ hàng: " . $e->getMessage());
        }
    }
    public function addToGioHang($id_gio_hang, $id_bien_the, $so_luong)
    {
        try {

            // Kiểm tra sản phẩm đã có trong giỏ chưa
            $query = "
                SELECT so_luong
                FROM chi_tiet_gio_hang
                WHERE id_gio_hang = ?
                AND id_bien_the = ?
            ";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id_gio_hang, $id_bien_the]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Đã tồn tại -> cộng số lượng
            if ($row) {

                $new_so_luong = $row['so_luong'] + $so_luong;

                $update = "
                    UPDATE chi_tiet_gio_hang
                    SET so_luong = ?
                    WHERE id_gio_hang = ?
                    AND id_bien_the = ?
                ";

                return $this->conn->prepare($update)->execute([
                    $new_so_luong,
                    $id_gio_hang,
                    $id_bien_the
                ]);
            }

            // Chưa có -> thêm mới
            $insert = "
                INSERT INTO chi_tiet_gio_hang
                (
                    id_gio_hang,
                    id_bien_the,
                    so_luong
                )
                VALUES
                (
                    ?,
                    ?,
                    ?
                )
            ";

            return $this->conn->prepare($insert)->execute([
                $id_gio_hang,
                $id_bien_the,
                $so_luong
            ]);

        } catch(PDOException $e) {

            die('Lỗi SQL: ' . $e->getMessage());

        }
    }
    public function getThongTinBienThe($id_bien_the) {
        $query = "SELECT bt.*, sp.ten_san_pham 
                FROM bien_the_san_pham bt 
                JOIN san_pham sp ON bt.id_san_pham = sp.id_san_pham 
                WHERE bt.id_bien_the = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_bien_the]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // 3. Lấy danh sách sản phẩm trong giỏ hàng
    public function getChiTietGioHang($id_gio_hang)
    {
        $query = "
            SELECT
                ct.*,
                bt.kich_co,
                bt.mau_sac,
                bt.gia_ban,
                bt.hinh_anh_bien_the,
                bt.so_luong_ton,
                sp.ten_san_pham
            FROM chi_tiet_gio_hang ct
            INNER JOIN bien_the_san_pham bt
                ON ct.id_bien_the = bt.id_bien_the
            INNER JOIN san_pham sp
                ON bt.id_san_pham = sp.id_san_pham
            WHERE ct.id_gio_hang = ?
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_gio_hang]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getStockByVariant($id_bien_the) {
        $stmt = $this->conn->prepare("
            SELECT so_luong_ton 
            FROM bien_the_san_pham 
            WHERE id_bien_the = ?
        ");
        $stmt->execute([$id_bien_the]);
        return (int)$stmt->fetchColumn();
    }
    // 4. Xóa sản phẩm khỏi giỏ hàng
    public function removeFromGioHang($id_gio_hang, $id_bien_the) {
        // Xóa dựa trên cả 2 điều kiện để chính xác
        $query = "DELETE FROM " . $this->table_chi_tiet . " WHERE id_gio_hang = ? AND id_bien_the = ?";
        return $this->conn->prepare($query)->execute([$id_gio_hang, $id_bien_the]);
    }
    public function clearCart($id_gio_hang)
        {
            $sql = "
                DELETE FROM chi_tiet_gio_hang
                WHERE id_gio_hang = ?
            ";

            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([$id_gio_hang]);
        }
    public function updateQty($id_gio_hang, $id_bien_the, $delta)
    {
        $sql = "
            UPDATE chi_tiet_gio_hang
            SET so_luong = so_luong + ?
            WHERE id_gio_hang = ? AND id_bien_the = ?
        ";

        $this->conn->prepare($sql)->execute([$delta, $id_gio_hang, $id_bien_the]);

        // xoá nếu <= 0
        $sql2 = "
            DELETE FROM chi_tiet_gio_hang
            WHERE so_luong <= 0
        ";

        $this->conn->query($sql2);
    }
    
}
    
?>