<?php

class DonHangModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    // ĐƠN HÀNG
    public function createDonHang($data)
    {
        $sql = "
            INSERT INTO don_hang
            ( id_khach_hang,  id_voucher,   tong_tien, phuong_thuc_thanh_toan, ten_nguoi_nhan, sdt_nguoi_nhan, dia_chi_giao_hang, ghi_chu )
            VALUES ( :id_khach_hang,
                :id_voucher,
                :tong_tien,
                :pttt,
                :ten,
                :sdt,
                :dia_chi,
                :ghi_chu
            )
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id_khach_hang' => $data['id_khach_hang'],
            ':id_voucher'    => $data['id_voucher'] ?? null,
            ':tong_tien'     => $data['tong_tien'],
            ':pttt'          => $data['phuong_thuc_thanh_toan'],
            ':ten'           => $data['ten_nguoi_nhan'],
            ':sdt'           => $data['sdt_nguoi_nhan'],
            ':dia_chi'       => $data['dia_chi_giao_hang'],
            ':ghi_chu'       => $data['ghi_chu'] ?? ''
        ]);

        return $this->conn->lastInsertId();
    }

    public function addChiTietDonHang($data)
    {
        $sql = "
            INSERT INTO chi_tiet_don_hang (  id_don_hang, id_bien_the,  so_luong, don_gia
            )
            VALUES
            (
                :id_don_hang,
                :id_bien_the,
                :so_luong,
                :don_gia
            )
        ";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
    public function getAllDonHang(){
        $sql = "
            SELECT *
            FROM don_hang
            ORDER BY ngay_dat DESC
        ";
        return $this->conn
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDonHangById($id)
    {
        $sql = "
            SELECT *
            FROM don_hang
            WHERE id_don_hang = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getDonHangByKhachHang($id_khach_hang)
    {
        $sql = "
            SELECT *
            FROM don_hang
            WHERE id_khach_hang = ?
            ORDER BY ngay_dat DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_khach_hang]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // CHI TIẾT ĐƠN HÀNG
    public function getChiTietDonHang($id_don_hang){
        $sql = "
            SELECT
                ct.*,
                bt.kich_co,
                bt.mau_sac,
                bt.hinh_anh_bien_the,
                sp.ten_san_pham
            FROM chi_tiet_don_hang ct
            INNER JOIN bien_the_san_pham bt
                ON ct.id_bien_the = bt.id_bien_the
            INNER JOIN san_pham sp
                ON bt.id_san_pham = sp.id_san_pham
            WHERE ct.id_don_hang = ?
        ";
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
     // TRẠNG THÁI ĐƠN HÀNG
    public function updateTrangThai($id_don_hang, $trang_thai){
        $sql = "
            UPDATE don_hang
            SET trang_thai_don_hang = ?
            WHERE id_don_hang = ?
        ";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $trang_thai,
            $id_don_hang
        ]);
    }
    public function cancelDonHang($id_don_hang){
        try {
            $this->conn->beginTransaction();
            $donHang = $this->getDonHangById($id_don_hang);
            if (!$donHang) {
                throw new Exception("Không tìm thấy đơn hàng");
            }
            if (
                $donHang['trang_thai_don_hang'] == 'Đã hủy' ||
                $donHang['trang_thai_don_hang'] == 'Đã giao'
            ) {
                throw new Exception("Không thể hủy đơn hàng");
            }
            $items = $this->getChiTietDonHang($id_don_hang);
            foreach ($items as $item) {
                $this->restoreTonKho(
                    $item['id_bien_the'],
                    $item['so_luong']
                );
            }
            $this->updateTrangThai(
                $id_don_hang,
                'Đã hủy'
            );
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
     //THỐNG KÊ
    public function countDonHang() {
        return $this->conn
            ->query("SELECT COUNT(*) FROM don_hang")
            ->fetchColumn();
    }

    public function getTongDoanhThu(){
        $sql = "
            SELECT SUM(tong_tien)
            FROM don_hang
            WHERE trang_thai_don_hang = 'Đã giao'
        ";
        return $this->conn
            ->query($sql)
            ->fetchColumn();
    }
    //XÓA ĐƠN HÀNG
    public function deleteDonHang($id_don_hang){
        $donHang = $this->getDonHangById($id_don_hang);
        if (!$donHang) {
            return false;
        }
        if ($donHang['trang_thai_don_hang'] !== 'Đã hủy') {
            return false;
        }
        try {
            $this->conn->beginTransaction();
            $this->conn->prepare(
                "DELETE FROM chi_tiet_don_hang WHERE id_don_hang = ?"
            )->execute([$id_don_hang]);
            $this->conn->prepare(
                "DELETE FROM don_hang WHERE id_don_hang = ?"
            )->execute([$id_don_hang]);
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
}