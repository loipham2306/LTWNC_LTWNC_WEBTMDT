<?php
    class taikhoan{
        private  $id_tai_khoan;
        private  $ten_dang_nhap;
        private $email;
        private $mat_khau;
        private $vai_tro;
        private $trang_thai;
        private $ngay_tao;
        private $conn;
        private $table_name="tai_khoan";
        
        public function __construct($db){
            $this->conn=$db;
        }
        
        public function checkTaiKhoan($ten_dang_nhap){
            // Câu lệnh truy vấn
            $query ="SELECT id_tai_khoan, ten_dang_nhap, email, mat_khau, vai_tro, trang_thai
                    FROM " . $this->table_name . "
                    WHERE (ten_dang_nhap = :ten_dang_nhap OR email = :ten_dang_nhap)
                    LIMIT 1";
            try {
                //Chuẩn bị đường dẫn truy vấn 
                $stmt = $this->conn->prepare($query);
                // mã hóa dữ liệu đầu vào
                $ten_dang_nhap = htmlspecialchars(strip_tags($ten_dang_nhap));
                //Truyền dữ liệu đầu vào câu truy vấn
                $stmt->bindParam(':ten_dang_nhap', $ten_dang_nhap);
                // thực thi câu truy vấn
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
                    // Trả về mảng dữ liệu này để file Controller sử dụng
                    return $row;
                }
                return null; 
            } catch(PDOException $e) {
                die("Lỗi kiểm tra đăng nhập: " . $e->getMessage());
            }
        }
    }
?>