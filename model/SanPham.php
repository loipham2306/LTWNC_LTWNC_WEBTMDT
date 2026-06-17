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
    // Trong class SanPham, cập nhật hàm này:
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
                    COALESCE(SUM(bt.so_luong_ton), 0) AS so_luong_kho
                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
                LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id_thuong_hieu
                LEFT JOIN bien_the_san_pham bt ON sp.id_san_pham = bt.id_san_pham
                GROUP BY sp.id_san_pham
                ORDER BY sp.id_san_pham DESC 
                LIMIT :limit OFFSET :offset"; // Thêm dòng này
        
        $stmt = $this->conn->prepare($sql);
        
        // Gán giá trị cho các tham số
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getChiTietSanPham($id) {
        // Truy vấn lấy thông tin sản phẩm và tất cả biến thể liên quan
        $query = "SELECT sp.*, 
                        dm.ten_danh_muc, 
                        th.ten_thuong_hieu,
                        bt.id_bien_the, bt.kich_co, bt.mau_sac, 
                        bt.gia_ban, bt.so_luong_ton, bt.hinh_anh_bien_the
                FROM san_pham sp
                LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
                LEFT JOIN thuong_hieu th ON sp.id_thuong_hieu = th.id_thuong_hieu
                LEFT JOIN bien_the_san_pham bt ON sp.id_san_pham = bt.id_san_pham
                WHERE sp.id_san_pham = :id";

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
                $sanPham['bien_the'][] = [
                    'id_bien_the'       => $row['id_bien_the'],
                    'kich_co'           => $row['kich_co'],
                    'mau_sac'           => $row['mau_sac'],
                    'gia_ban'           => $row['gia_ban'],
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
                sp.*,
                GROUP_CONCAT(DISTINCT bt.kich_co SEPARATOR ', ') as danh_sach_size,
                GROUP_CONCAT(DISTINCT bt.mau_sac SEPARATOR ', ') as danh_sach_mau,
                SUM(bt.so_luong_ton) AS tong_ton_kho,

                CASE 
                    WHEN SUM(bt.so_luong_ton) IS NULL OR SUM(bt.so_luong_ton) = 0 
                    THEN 'HẾT HÀNG'
                    WHEN SUM(bt.so_luong_ton) < 5 
                    THEN 'SẮP HẾT'
                    ELSE NULL
                END AS trang_thai_sp

            FROM san_pham sp
            LEFT JOIN bien_the_san_pham bt 
                ON sp.id_san_pham = bt.id_san_pham

            GROUP BY sp.id_san_pham
            ORDER BY sp.id_san_pham DESC
            LIMIT :limit
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // THÊM SẢN PHẨM (Đã bỏ cột 'giam_gia' và 'gia')
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
                ORDER BY so_luong DESC"; // Thương hiệu nhiều SP nhất lên đầu
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

    // Lấy danh sách sản phẩm (Kết hợp lấy giá biến thể rẻ nhất nếu cần)
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
    // model/SanPham.php


    public function countAllProductsForShop() {
        $query = "SELECT COUNT(*) FROM san_pham WHERE trang_thai = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }   
}
?>