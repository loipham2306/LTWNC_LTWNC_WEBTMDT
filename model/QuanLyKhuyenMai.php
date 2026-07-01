<?php
class QuanLyKhuyenMai {
    private $conn;
    private $table_chuong_trinh_KM = 'chuong_trinh_khuyen_mai';
    private $table_ct_KM = 'chi_tiet_khuyen_mai';
    private $table_san_pham = 'san_pham';
    private $table_bien_the = 'bien_the_san_pham';

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Lấy tất cả chương trình khuyến mãi
    public function getAllKhuyenMai() {
        $query = "SELECT * FROM " . $this->table_chuong_trinh_KM . " ORDER BY id_khuyen_mai DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy % giảm giá (Ưu tiên biến thể trước, rồi đến sản phẩm)
    public function getKhuyenMaiCuaSanPham($id_san_pham, $id_bien_the = null) {
        $query = "SELECT km.phan_tram_giam 
                  FROM " . $this->table_chuong_trinh_KM . " km
                  JOIN " . $this->table_ct_KM . " ct ON km.id_khuyen_mai = ct.id_khuyen_mai
                  WHERE km.trang_thai = 1 
                  AND NOW() BETWEEN km.ngay_bat_dau AND km.ngay_ket_thuc
                  AND (
                      (ct.id_san_pham = ? AND ct.id_bien_the IS NULL) OR 
                      (ct.id_bien_the = ?)
                  )
                  ORDER BY ct.id_bien_the DESC LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id_san_pham, $id_bien_the]);
        return $stmt->fetchColumn(); 
    }
    
    // 3. Thêm mới chương trình
    public function createKhuyenMai($ten_km, $phan_tram, $start, $end, $trang_thai, $banner) {
        try {
            $query = "INSERT INTO chuong_trinh_khuyen_mai
                    (ten_km, phan_tram_giam, ngay_bat_dau, ngay_ket_thuc, trang_thai, hinh_anh_banner)
                    VALUES (:ten, :phan_tram, :start, :end, :status, :banner)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':ten' => $ten_km,
                ':phan_tram' => $phan_tram,
                ':start' => $start,
                ':end' => $end,
                ':status' => $trang_thai,
                ':banner' => $banner
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            // Ghi log lỗi vào file hoặc xuất ra màn hình để sửa
            error_log("Lỗi tạo KM: " . $e->getMessage());
            return false;
        }
    }

    // 4. Lấy danh sách biến thể để gán khuyến mãi
    public function getDanhSachBienThe()
    {
        $sql = "
            SELECT
                bt.id_bien_the,
                bt.id_san_pham,
                sp.ten_san_pham,
                bt.mau_sac,
                bt.kich_co,
                bt.so_luong_ton
            FROM bien_the_san_pham bt
            INNER JOIN san_pham sp
                ON sp.id_san_pham=bt.id_san_pham
            ORDER BY
                sp.ten_san_pham,
                bt.id_bien_the
        ";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute();
        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $result=[];
        foreach($rows as $row){
            $id=$row['id_san_pham'];
            if(!isset($result[$id])){

                $result[$id]=[
                    "id_san_pham"=>$id,
                    "ten_san_pham"=>$row['ten_san_pham'],
                    "bien_the"=>[]
                ];
            }
            $result[$id]['bien_the'][]=[

                "id_bien_the"=>$row['id_bien_the'],
                "kich_co"=>$row['kich_co'],
                "mau_sac"=>$row['mau_sac'],
                "so_luong_ton"=>$row['so_luong_ton']
            ];
        }
        return array_values($result);
    }

    // 5. Gán sản phẩm/biến thể vào chương trình (Cập nhật bảng chi_tiet_khuyen_mai)
    public function addProductToKhuyenMai($id_km, $id_san_pham, $id_bien_the = null) {
        // Kiểm tra trùng lặp
        $check = "SELECT id_ctkm FROM " . $this->table_ct_KM . " 
                  WHERE id_khuyen_mai = ? AND id_san_pham = ? AND (id_bien_the = ? OR (id_bien_the IS NULL AND ? IS NULL))";
        
        $stmt = $this->conn->prepare($check);
        $stmt->execute([$id_km, $id_san_pham, $id_bien_the, $id_bien_the]);
        
        if ($stmt->rowCount() == 0) {
            $query = "INSERT INTO " . $this->table_ct_KM . " (id_khuyen_mai, id_san_pham, id_bien_the) VALUES (?, ?, ?)";
            return $this->conn->prepare($query)->execute([$id_km, $id_san_pham, $id_bien_the]);
        }
        return false;
    }

    // 6. Xóa sản phẩm khỏi chương trình
    public function removeProductFromKhuyenMai($id_ctkm) {
        $query = "DELETE FROM " . $this->table_ct_KM . " WHERE id_ctkm = ?";
        return $this->conn->prepare($query)->execute([$id_ctkm]);
    }

    // 7. Cập nhật trạng thái
    public function toggleStatus($id_km, $status) {
        $query = "UPDATE " . $this->table_chuong_trinh_KM . " SET trang_thai = ? WHERE id_khuyen_mai = ?";
        return $this->conn->prepare($query)->execute([$status, $id_km]);
    }
    public function getSanPhamByBienThe($id_bien_the)
    {
        $sql = "SELECT id_san_pham
                FROM bien_the_san_pham
                WHERE id_bien_the = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_bien_the]);

        return $stmt->fetchColumn();
    }
    public function getSanPhamKhuyenMai() {
        $sql = "
            SELECT
                sp.*,
                dm.ten_danh_muc,
                COALESCE(MAX(km.phan_tram_giam),0) AS phan_tram_giam
            FROM san_pham sp
            LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc

            LEFT JOIN chi_tiet_khuyen_mai ct 
                ON (
                    ct.id_san_pham = sp.id_san_pham 
                    OR ct.id_san_pham IN (
                        SELECT id_san_pham 
                        FROM bien_the_san_pham 
                        WHERE id_san_pham = sp.id_san_pham
                    )
                )

            LEFT JOIN chuong_trinh_khuyen_mai km 
                ON ct.id_khuyen_mai = km.id_khuyen_mai
                AND km.trang_thai = 1
                AND NOW() BETWEEN km.ngay_bat_dau AND km.ngay_ket_thuc

            GROUP BY sp.id_san_pham
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // lấy banner khuyến mãi
   
}

?>