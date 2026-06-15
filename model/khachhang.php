<?php
    class khachhang{
        // Khai báo các thuộc tính ánh xạ với các cột trong bảng Khach_hang
        private $ho_ten_dem;
        private $ten;
        private $sdt;
        private $diachi;
        private $hang_thanh_vien;
        private $id_tai_khoan;

        private $conn;
        private $table_name = "Khach_hang"; 
        // Hàm khởi tạo nhận kết nối database từ Controller truyền vào
        public function __construct($db){
            $this->conn = $db;
        }
        // Thêm vào class khachhang trong model/khachhang.php
        public function setThongTin($ho_ten_dem, $ten, $sdt, $diachi, $hang_thanh_vien, $id_tai_khoan) {
            $this->ho_ten_dem = $ho_ten_dem;
            $this->ten = $ten;
            $this->sdt = $sdt;
            $this->diachi = $diachi;
            $this->hang_thanh_vien = $hang_thanh_vien;
            $this->id_tai_khoan = $id_tai_khoan;
        }
        // Hàm lấy thông tin cá nhân dựa vào ID tài khoản đã đăng nhập thành công
        public function getThongTinByTaiKhoanId($id_tai_khoan) {
            // JOIN bảng khach_hang (k) và tai_khoan (t)
            $query = "SELECT  k.id_khach_hang, k.ho_ten_dem, k.ten, k.so_dien_thoai, k.dia_chi, k.hang_thanh_vien, 
                            t.email, t.ten_dang_nhap 
                    FROM khach_hang k
                    JOIN tai_khoan t ON k.id_tai_khoan = t.id_tai_khoan
                    WHERE k.id_tai_khoan = :id_tai_khoan LIMIT 1";
                    
            try {
                $stmt = $this->conn->prepare($query);
                $id_tai_khoan = intval($id_tai_khoan);
                $stmt->bindParam(':id_tai_khoan', $id_tai_khoan);
                $stmt->execute();

                if($stmt->rowCount() > 0) {
                    return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về tất cả cột đã select
                }
                return null;
            } catch(PDOException $e) {
                return null;
            }
        }
        public function CapNhatThongTinKhachHang() {
            // 1. Cấu trúc SQL chuẩn (Không có INTO)
            $query = "UPDATE " . $this->table_name . "
                    SET ho_ten_dem = :ho_ten_dem,
                        ten = :ten,
                        so_dien_thoai = :sdt,
                        dia_chi = :diachi,
                        hang_thanh_vien = :hang_thanh_vien
                    WHERE id_tai_khoan = :id_tai_khoan";

            try {
                $stmt = $this->conn->prepare($query);

                // 2. Ràng buộc các giá trị từ thuộc tính của class vào tham số
                $stmt->bindParam(':ho_ten_dem', $this->ho_ten_dem);
                $stmt->bindParam(':ten', $this->ten);
                $stmt->bindParam(':sdt', $this->sdt);
                $stmt->bindParam(':diachi', $this->diachi);
                $stmt->bindParam(':hang_thanh_vien', $this->hang_thanh_vien);
                $stmt->bindParam(':id_tai_khoan', $this->id_tai_khoan);

                // 3. Thực thi
                return $stmt->execute();
            } catch (PDOException $e) {
                // Ghi log lỗi thay vì die để bảo mật
                error_log("Lỗi cập nhật khách hàng: " . $e->getMessage());
                return false;
            }
        }
                /**
         * Lấy tất cả khách hàng có phân trang
         * @param int $limit Số lượng bản ghi trên mỗi trang
         * @param int $offset Vị trí bắt đầu lấy
         */
        public function getAllKhachHang($limit, $offset) {
            // Đã thêm tk.ngay_tao vào câu SELECT
            $query = "SELECT kh.*, tk.trang_thai, tk.ngay_tao,
                            (SELECT COUNT(*) FROM don_hang dh WHERE dh.id_khach_hang = kh.id_khach_hang) as so_don,
                            (SELECT SUM(tong_tien) FROM don_hang dh WHERE dh.id_khach_hang = kh.id_khach_hang) as tong_chi_tieu
                    FROM khach_hang kh 
                    JOIN tai_khoan tk ON kh.id_tai_khoan = tk.id_tai_khoan   
                    WHERE tk.vai_tro != 'admin'  
                    LIMIT :limit OFFSET :offset"; 
                    
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Đếm tổng số khách hàng (Hữu ích để làm số trang phân trang)
         */
        public function countAllKhachHang() {
            // JOIN với bảng tài khoản để kiểm tra vai trò
            $query = "SELECT COUNT(*) 
                    FROM khach_hang kh 
                    JOIN tai_khoan tk ON kh.id_tai_khoan = tk.id_tai_khoan 
                    WHERE tk.vai_tro != 'admin'";
                    
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            // Trả về số lượng là một số nguyên
            return (int)$stmt->fetchColumn();
        }
        public function themKhachHang($id_tk, $ho, $ten, $sdt, $diachi) {
            $query = "INSERT INTO khach_hang (id_tai_khoan, ho_ten_dem, ten, so_dien_thoai, dia_chi) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id_tk, $ho, $ten, $sdt, $diachi]);
        }
    }
?>