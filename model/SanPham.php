<?php
class SanPham {
    private $conn;
    private $table_name = "san_pham";

    // Các thuộc tính ánh xạ
    public $id_san_pham;
    public $ten_san_pham;
    public $gia_co_ban; // Thay cho 'gia'
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

    // Ánh xạ dữ liệu
    public function setData($ten, $gia_cb, $mota, $img, $id_dm, $id_th, $trang_thai) {
        $this->ten_san_pham = $ten;
        $this->gia_co_ban = $gia_cb;
        $this->mo_ta = $mota;
        $this->hinh_anh = $img;
        $this->id_danh_muc = $id_dm;
        $this->id_thuong_hieu = $id_th;
        $this->trang_thai = $trang_thai;
    }

    // lấy sản phẩm cho cửa hàng
    public function getAllProductsForShop($limit, $offset) {
            // Thêm LIMIT và OFFSET vào cuối câu truy vấn
            $sql = "SELECT 
                    sp.id_san_pham, 
                    sp.ten_san_pham, 
                    sp.gia_co_ban, 
                    sp.hinh_anh, 
                    sp.ngay_tao,
                    dm.ten_danh_muc, 
                    th.ten_thuong_hieu, 
                    COALESCE(SUM(bt.so_luong_ton), 0) AS so_luong_kho,

                    COALESCE(
                        MAX(
                            CASE 
                                WHEN km.trang_thai = 1 
                                AND NOW() BETWEEN km.ngay_bat_dau AND km.ngay_ket_thuc
                                THEN km.phan_tram_giam
                                ELSE 0
                            END
                        ),
                    0) AS phan_tram_giam

                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
                LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id_thuong_hieu
                LEFT JOIN bien_the_san_pham bt ON sp.id_san_pham = bt.id_san_pham

                LEFT JOIN chi_tiet_khuyen_mai ct 
                    ON ct.id_san_pham = sp.id_san_pham

                LEFT JOIN chuong_trinh_khuyen_mai km 
                    ON ct.id_khuyen_mai = km.id_khuyen_mai

                GROUP BY sp.id_san_pham
                ORDER BY sp.id_san_pham DESC
                LIMIT :limit OFFSET :offset"; 
        
        $stmt = $this->conn->prepare($sql);
        
        // Gán giá trị cho các tham số
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChiTietSanPham($id) {
        // Truy vấn lấy thông tin sản phẩm và tất cả biến thể liên quan
        $query = "
            SELECT sp.*, 
                dm.ten_danh_muc, 
                th.ten_thuong_hieu,
                bt.id_bien_the, 
                bt.kich_co, 
                bt.mau_sac, 
                bt.gia_ban, 
                bt.so_luong_ton, 
                bt.hinh_anh_bien_the,

                COALESCE(km_best.phan_tram_giam, 0) AS phan_tram_giam

            FROM san_pham sp
            LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
            LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id_thuong_hieu
            LEFT JOIN bien_the_san_pham bt ON sp.id_san_pham = bt.id_san_pham

            LEFT JOIN (
                SELECT ct.id_san_pham,
                    MAX(km.phan_tram_giam) AS phan_tram_giam
                FROM chi_tiet_khuyen_mai ct
                JOIN chuong_trinh_khuyen_mai km 
                    ON ct.id_khuyen_mai = km.id_khuyen_mai
                WHERE km.trang_thai = 1
                AND NOW() BETWEEN km.ngay_bat_dau AND km.ngay_ket_thuc
                GROUP BY ct.id_san_pham
            ) km_best ON km_best.id_san_pham = sp.id_san_pham

            WHERE sp.id_san_pham = :id
            ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) return null;

        // Tổ chức lại dữ liệu: Thông tin chính + Mảng các biến thể
        $sanPham = [
            'id_san_pham'    => $data[0]['id_san_pham'],
            'ten_san_pham'   => $data[0]['ten_san_pham'],
            'gia_co_ban'     => $data[0]['gia_co_ban'],
            'mo_ta'          => $data[0]['mo_ta'],
            'hinh_anh'       => $data[0]['hinh_anh'],
            'ten_danh_muc'   => $data[0]['ten_danh_muc'],
            'ten_thuong_hieu'=> $data[0]['ten_thuong_hieu'],
            'bien_the'       => []
        ];

        foreach ($data as $row) {
            if ($row['id_bien_the']) {

                $giaGoc = (float)$row['gia_ban'];
                $giam   = (float)$row['phan_tram_giam'];

                $giaSauGiam = $giaGoc;

                if ($giam > 0 && $giam <= 100) {
                    $giaSauGiam = round($giaGoc * (1 - $giam / 100), 0);
                }

                $sanPham['bien_the'][] = [
                    'id_bien_the'       => $row['id_bien_the'],
                    'kich_co'           => $row['kich_co'],
                    'mau_sac'           => $row['mau_sac'],
                    'gia_ban'           => $giaGoc,
                    'gia_sau_giam'      => $giaSauGiam,   
                    'phan_tram_giam'    => $giam,
                    'so_luong_ton'      => $row['so_luong_ton'],
                    'hinh_anh_bien_the' => $row['hinh_anh_bien_the']
                ];
            }
        }

        return $sanPham;
    }

    public function getSanPhamHome($limit = 8) {
        $query = "
            SELECT 
                    sp.id_san_pham, 
                    sp.ten_san_pham, 
                    sp.gia_co_ban, 
                    sp.hinh_anh, 
                    sp.ngay_tao,
                    dm.ten_danh_muc, 
                    th.ten_thuong_hieu, 
                    COALESCE(SUM(bt.so_luong_ton), 0) AS so_luong_kho,

                    COALESCE(
                        MAX(
                            CASE 
                                WHEN km.trang_thai = 1 
                                AND NOW() BETWEEN km.ngay_bat_dau AND km.ngay_ket_thuc
                                THEN km.phan_tram_giam
                                ELSE 0
                            END
                        ),
                    0) AS phan_tram_giam

                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
                LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id_thuong_hieu
                LEFT JOIN bien_the_san_pham bt ON sp.id_san_pham = bt.id_san_pham

                LEFT JOIN chi_tiet_khuyen_mai ct 
                    ON ct.id_san_pham = sp.id_san_pham

                LEFT JOIN chuong_trinh_khuyen_mai km 
                    ON ct.id_khuyen_mai = km.id_khuyen_mai

                GROUP BY sp.id_san_pham
                ORDER BY sp.id_san_pham DESC
            LIMIT :limit
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // THÊM SẢN PHẨM 
    public function ThemSanPham() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET ten_san_pham=:ten, gia_co_ban=:gia, mo_ta=:mota, 
                      hinh_anh=:hinh, id_danh_muc=:id_dm, id_thuong_hieu=:id_th, 
                      trang_thai=:tt, ngay_tao=NOW()";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            ':ten'  => $this->ten_san_pham,
            ':gia'  => $this->gia_co_ban,
            ':mota' => $this->mo_ta,
            ':hinh' => $this->hinh_anh,
            ':id_dm'=> $this->id_danh_muc,
            ':id_th'=> $this->id_thuong_hieu,
            ':tt'   => $this->trang_thai
        ]);
    }

    // 1. Đếm tổng để hiện trên thẻ Card
    public function countAllProducts() {
        return $this->conn->query("SELECT COUNT(*) FROM san_pham")->fetchColumn();
    }

    public function getTotalRevenue() {
        // Chỉ tính những đơn hàng đã thanh toán hoặc hoàn tất
        $sql = "SELECT SUM(tong_tien) FROM don_hang WHERE trang_thai = 'Hoàn Thành'";
        return $this->conn->query($sql)->fetchColumn() ?? 0;
    }

    public function countNewOrders() {
        // Đếm những đơn hàng đang ở trạng thái 'Chờ Duyệt'
        $sql = "SELECT COUNT(*) FROM don_hang WHERE trang_thai = 'Chờ Duyệt'";
        return $this->conn->query($sql)->fetchColumn();
    }

    // 2. Thống kê theo thương hiệu để vẽ biểu đồ/bảng
    public function getStatsByBrand() {
        $sql = "SELECT t.ten_thuong_hieu, COUNT(s.id_san_pham) as so_luong
                FROM thuong_hieu t
                LEFT JOIN san_pham s ON t.id_thuong_hieu = s.id_thuong_hieu
                GROUP BY t.id_thuong_hieu
                ORDER BY so_luong DESC"; 
        return $this->conn->query($sql)->fetchAll();
    }

    // CẬP NHẬT SẢN PHẨM
    public function CapNhatSanPham() {
        $query = "UPDATE " . $this->table_name . " 
                  SET ten_san_pham=:ten, gia_co_ban=:gia, mo_ta=:mota, 
                      hinh_anh=:hinh, id_danh_muc=:id_dm, id_thuong_hieu=:id_th, 
                      trang_thai=:tt, ngay_cap_nhat=NOW()
                  WHERE id_san_pham=:id";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            ':ten'  => $this->ten_san_pham,
            ':gia'  => $this->gia_co_ban,
            ':mota' => $this->mo_ta,
            ':hinh' => $this->hinh_anh,
            ':id_dm'=> $this->id_danh_muc,
            ':id_th'=> $this->id_thuong_hieu,
            ':tt'   => $this->trang_thai,
            ':id'   => $this->id_san_pham
        ]);
    }

    // Lấy danh sách sản phẩm 
    public function LayTatCaSanPhamPhanTrang($limit, $offset) {
        $query = "SELECT sp.*, dm.ten_danh_muc, th.ten_thuong_hieu, 
                        COALESCE(SUM(bt.so_luong_ton), 0) AS so_luong_kho
                FROM " . $this->table_name . " sp
                LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
                LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id_thuong_hieu
                LEFT JOIN bien_the_san_pham bt ON sp.id_san_pham = bt.id_san_pham
                GROUP BY sp.id_san_pham
                ORDER BY sp.id_san_pham DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // XÓA SẢN PHẨM
    public function XoaSanPham() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_san_pham = :id";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([':id' => $this->id_san_pham]);
    }
    
    // Đếm tổng số sản phẩm
    public function countSanPham() {
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function countAllProductsForShop($keyword = '')
    {
        $sql = "SELECT COUNT(*) FROM san_pham WHERE 1";

        if (!empty($keyword)) {
            $sql .= " AND ten_san_pham LIKE :keyword";
        }

        $stmt = $this->conn->prepare($sql);

        if (!empty($keyword)) {
            $stmt->bindValue(
                ':keyword',
                '%' . $keyword . '%',
                PDO::PARAM_STR
            );
        }

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function applyPromotion(&$products)
    {
          if (!is_array($products)) {
                $products = [];
                return;
            }
        foreach ($products as &$sp) {

            $giam = (float)($sp['phan_tram_giam'] ?? 0);
            $hasSale = $giam > 0;

            $sp['gia_sau_giam'] = $hasSale
                ? $sp['gia_co_ban'] - ($sp['gia_co_ban'] * $giam / 100)
                : $sp['gia_co_ban'];

            $sp['co_khuyen_mai'] = $hasSale ? 1 : 0;

        }

        unset($sp);
    }

    public function applyPromotionSingle(&$product)
    {
        if (!empty($product['bien_the'])) {
            foreach ($product['bien_the'] as &$bt) {
                // Lấy % giảm từ biến thể (nếu không có thì lấy từ sản phẩm cha)
                $giam = (float)($bt['phan_tram_giam'] ?? $product['phan_tram_giam'] ?? 0);
                
                if ($giam > 0) {
                    $bt['gia_sau_giam'] = $bt['gia_ban'] - ($bt['gia_ban'] * $giam / 100);
                } else {
                    $bt['gia_sau_giam'] = $bt['gia_ban'];
                }
            }
            unset($bt);
        }
    }

    // thống kê bán chạy
    public function getTopSellingProducts($limit = 10)
    {
        $sql = "
            SELECT 
                sp.id_san_pham,
                sp.ten_san_pham,
                SUM(ct.so_luong) AS tong_ban,
                SUM(ct.so_luong * ct.gia_luc_mua) AS doanh_thu
            FROM chi_tiet_don_hang ct
            JOIN don_hang dh 
                ON dh.id_don_hang = ct.id_don_hang
            JOIN bien_the_san_pham bt 
                ON bt.id_bien_the = ct.id_bien_the
            JOIN san_pham sp 
                ON sp.id_san_pham = bt.id_san_pham
            WHERE dh.trang_thai_don_hang = 'Đã giao'
            GROUP BY sp.id_san_pham, sp.ten_san_pham
            ORDER BY tong_ban DESC
            LIMIT ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getThongTinBienThe($id_bien_the) {
        $sql = "SELECT bt.id_bien_the, bt.gia_ban, sp.id_san_pham,
                COALESCE(MAX(km.phan_tram_giam), 0) AS phan_tram_giam
                FROM bien_the_san_pham bt
                JOIN san_pham sp ON bt.id_san_pham = sp.id_san_pham
                LEFT JOIN chi_tiet_khuyen_mai ct ON sp.id_san_pham = ct.id_san_pham
                LEFT JOIN chuong_trinh_khuyen_mai km ON ct.id_khuyen_mai = km.id_khuyen_mai 
                    AND km.trang_thai = 1 
                    AND NOW() BETWEEN km.ngay_bat_dau AND km.ngay_ket_thuc
                WHERE bt.id_bien_the = :id
                GROUP BY bt.id_bien_the";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id_bien_the]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // Tính giá sau giảm
            $gia_ban = (float)$item['gia_ban'];
            $phan_tram = (float)$item['phan_tram_giam'];
            $item['gia_sau_giam'] = $gia_ban * (1 - ($phan_tram / 100));
        }
        return $item;
    }

    // ==========================================
    // ĐÃ FIX: HÀM TÌM KIẾM THEO TỪ KHÓA (CHỈ TÌM THEO TÊN, GỘP SỐ LƯỢNG KHO VÀ % GIẢM GIÁ)
    // ==========================================
    // ==========================================
    // ĐÃ FIX: TÌM KIẾM THEO TÊN SẢN PHẨM HOẶC TÊN DANH MỤC
    // ==========================================
    // ==========================================
    // TÌM KIẾM THEO: TÊN SP, TÊN DANH MỤC VÀ TÊN THƯƠNG HIỆU
    // ==========================================
    public function searchProductsForShop($keyword, $limit, $offset) {
        $sql = "SELECT 
                    sp.id_san_pham, 
                    sp.ten_san_pham, 
                    sp.gia_co_ban, 
                    sp.hinh_anh, 
                    sp.ngay_tao,
                    dm.ten_danh_muc, 
                    th.ten_thuong_hieu, 
                    COALESCE(SUM(bt.so_luong_ton), 0) AS so_luong_kho,

                    COALESCE(
                        MAX(
                            CASE 
                                WHEN km.trang_thai = 1 
                                AND NOW() BETWEEN km.ngay_bat_dau AND km.ngay_ket_thuc
                                THEN km.phan_tram_giam
                                ELSE 0
                            END
                        ),
                    0) AS phan_tram_giam

                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
                LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id_thuong_hieu
                LEFT JOIN bien_the_san_pham bt ON sp.id_san_pham = bt.id_san_pham

                LEFT JOIN chi_tiet_khuyen_mai ct 
                    ON ct.id_san_pham = sp.id_san_pham

                LEFT JOIN chuong_trinh_khuyen_mai km 
                    ON ct.id_khuyen_mai = km.id_khuyen_mai

                -- BỔ SUNG TÌM KIẾM TRONG THƯƠNG HIỆU
                WHERE sp.ten_san_pham LIKE :keyword_sp 
                   OR dm.ten_danh_muc LIKE :keyword_dm 
                   OR th.ten_thuong_hieu LIKE :keyword_th

                GROUP BY sp.id_san_pham
                ORDER BY sp.id_san_pham DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($sql); 
        
        $searchKey = '%' . $keyword . '%';
        // Truyền 3 lần searchKey cho SP, Danh Mục và Thương Hiệu
        $stmt->bindValue(':keyword_sp', $searchKey, PDO::PARAM_STR);
        $stmt->bindValue(':keyword_dm', $searchKey, PDO::PARAM_STR);
        $stmt->bindValue(':keyword_th', $searchKey, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==========================================
    // ĐẾM SỐ LƯỢNG TÌM KIẾM (Đếm SP, DM và TH)
    // ==========================================
    public function countSearchProductsForShop($keyword) {
        $sql = "SELECT COUNT(DISTINCT sp.id_san_pham) 
                FROM san_pham sp 
                LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
                LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id_thuong_hieu
                WHERE sp.ten_san_pham LIKE ? 
                   OR dm.ten_danh_muc LIKE ? 
                   OR th.ten_thuong_hieu LIKE ?";
                
        $stmt = $this->conn->prepare($sql); 
        $searchKey = '%' . $keyword . '%';
        // Truyền mảng 3 biến $searchKey
        $stmt->execute([$searchKey, $searchKey, $searchKey]);
        
        return $stmt->fetchColumn();
    }
}
?>