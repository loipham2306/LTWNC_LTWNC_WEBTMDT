<?php
    class TaiKhoan {
        private $id_tai_khoan;
        private $ten_dang_nhap;
        private $email;
        private $mat_khau;
        private $vai_tro;
        private $trang_thai;
        private $ngay_tao;
        
        // KHOẢNG TRỐNG CHO CÁC CỘT MỚI THÊM
        private $ho_ten_dem;
        private $ten;
        private $so_dien_thoai;
        private $dia_chi;

        private $conn;
        private $table_name="tai_khoan";

        public function getIdTaiKhoan() { return $this->id_tai_khoan; }
        public function setIdTaiKhoan($id_tai_khoan) { $this->id_tai_khoan = $id_tai_khoan; }
        public function getTenDangNhap() { return $this->ten_dang_nhap; }
        public function setTenDangNhap($ten_dang_nhap) { $this->ten_dang_nhap = $ten_dang_nhap; }
        public function getEmail() { return $this->email; }
        public function setEmail($email) { $this->email = $email; }
        public function getMatKhau() { return $this->mat_khau; }
        public function setMatKhau($mat_khau) { $this->mat_khau = $mat_khau; }
        public function getVaiTro() { return $this->vai_tro; }
        public function setVaiTro($vai_tro) { $this->vai_tro = $vai_tro; }
        public function getTrangThai() { return $this->trang_thai; }
        public function setTrangThai($trang_thai) { $this->trang_thai = $trang_thai; }
        public function getNgayTao() { return $this->ngay_tao; }
        public function setNgayTao($ngay_tao) { $this->ngay_tao = $ngay_tao; }
        
        public function __construct($db, $id_tai_khoan = null, $ten_dang_nhap = null, $email = null, $mat_khau = null, $vai_tro = null, $trang_thai = null, $ngay_tao = null) {
            $this->conn = $db;
            $this->id_tai_khoan = $id_tai_khoan;
            $this->ten_dang_nhap = $ten_dang_nhap;
            $this->email = $email;
            $this->mat_khau = $mat_khau;
            $this->vai_tro = $vai_tro;
            $this->trang_thai = $trang_thai;
            $this->ngay_tao = $ngay_tao;
        }
        public function checkUserExists($email, $so_dien_thoai) {
                // Lưu ý: Đảm bảo bảng 'khach_hang' của bạn có cột 'id_tai_khoan' làm khóa ngoại
                $sql = "SELECT *
                        FROM tai_khoan t
                        LEFT JOIN khach_hang k ON t.id_tai_khoan = k.id_tai_khoan 
                        WHERE t.email = ? OR k.so_dien_thoai = ?";
                        
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$email, $so_dien_thoai]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        public function TimKiemTaiKhoan($ten_dang_nhap){
            // Câu lệnh truy vấn
            $query ="SELECT *
                    FROM " . $this->table_name . "
                    WHERE (ten_dang_nhap = :ten_dang_nhap OR email = :ten_dang_nhap)
                    LIMIT 1";
            try {
                $stmt = $this->conn->prepare($query);
                $ten_dang_nhap = htmlspecialchars(strip_tags($ten_dang_nhap));
                $stmt->bindParam(':ten_dang_nhap', $ten_dang_nhap);
                $stmt->execute();
                
                if($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Ánh xạ dữ liệu từ DB vào các thuộc tính nội bộ của Class
                    $this->id_tai_khoan = $row['id_tai_khoan'];
                    $this->ten_dang_nhap = $row['ten_dang_nhap'];
                    $this->email = $row['email'];
                    $this->mat_khau = $row['mat_khau']; 
                    $this->vai_tro = $row['vai_tro'];
                    $this->trang_thai = $row['trang_thai'];
                    
                    // Ánh xạ thêm các cột mới
                    $this->ho_ten_dem = $row['ho_ten_dem'] ?? '';
                    $this->ten = $row['ten'] ?? '';
                    $this->so_dien_thoai = $row['so_dien_thoai'] ?? '';
                    $this->dia_chi = $row['dia_chi'] ?? '';

                    // Trả về mảng dữ liệu CÓ ĐẦY ĐỦ THÔNG TIN để file Controller sử dụng
                    return $row;
                }
                return null; 
            } catch(PDOException $e) {
                die("Lỗi kiểm tra đăng nhập: " . $e->getMessage());
            }
        }

        public function KiemTraTaiKhoan($password_input){
            if(empty($this->mat_khau)){
                return false;
            }
            return password_verify($password_input,$this->mat_khau);
        }

        public function updateTrangThaiTaiKhoan($id_tai_khoan, $trang_thai) {
            $query = "UPDATE " . $this->table_name . " SET trang_thai = :trang_thai WHERE id_tai_khoan = :id";
            try {
                $stmt = $this->conn->prepare($query);
                return $stmt->execute([
                    ':trang_thai' => $trang_thai, 
                    ':id' => $id_tai_khoan
                ]);
            } catch (PDOException $e) {
                error_log("Lỗi DB: " . $e->getMessage());
                return false;
            }
        }
        public function softDeleteTaiKhoan($id_tai_khoan) {
            // Cập nhật trạng thái = -1 để "ẩn" tài khoản khỏi danh sách
            return $this->updateTrangThaiTaiKhoan($id_tai_khoan, -1);
        }
        public function countTotalCustomers() {
            try {
                $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE vai_tro != 'admin'";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt->fetchColumn();
            } catch (PDOException $e) {
                error_log("Lỗi đếm tài khoản: " . $e->getMessage());
                return 0;
            }
        }
        public function themTaiKhoan($user, $email, $pass) {
            $query = "INSERT INTO tai_khoan (ten_dang_nhap, email, mat_khau,vai_tro) VALUES (?, ?, ?,'khach hang')";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$user, $email, $pass]);
            return $this->conn->lastInsertId(); // Trả về ID vừa tạo
        }
        // 1. Hàm lấy thông tin tài khoản theo ID
        public function getTaiKhoanById($id) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_tai_khoan = :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // 2. Hàm cập nhật mật khẩu
        public function updatePassword($id, $newHashedPassword) {
            $query = "UPDATE " . $this->table_name . " SET mat_khau = :mat_khau WHERE id_tai_khoan = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                ':mat_khau' => $newHashedPassword,
                ':id' => $id
            ]);
        }
    }
?>