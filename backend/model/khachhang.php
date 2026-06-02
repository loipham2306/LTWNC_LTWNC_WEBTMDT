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
        // Hàm lấy thông tin cá nhân dựa vào ID tài khoản đã đăng nhập thành công
        public function getThongTinByTaiKhoanId($id_tai_khoan){
            // 1. Khởi tạo câu lệnh truy vấn SQL với tham số ẩn (:id_tai_khoan) để bảo mật
            $query = "SELECT ho_ten_dem, ten, so_dien_thoai, dia_chi, hang_thanh_vien
                    FROM " . $this->table_name . "
                    WHERE id_tai_khoan = :id_tai_khoan 
                    LIMIT 1";
            try {
                // 2. Chuẩn bị câu lệnh truy vấn (Prepare statement) thông qua kết nối DB
                $stmt = $this->conn->prepare($query);
                // 3. Ép kiểu dữ liệu an toàn (Chuyển đầu vào chắc chắn thành Số Nguyên) để chống hack
                $id_tai_khoan = intval($id_tai_khoan);
                // 4. Ràng buộc tham số (Bind giá trị ID thật vào tham số ẩn trong câu SQL)
                $stmt->bindParam(':id_tai_khoan', $id_tai_khoan);
                // 5. Thực thi câu lệnh truy vấn trong MySQL
                $stmt->execute();
                // 6. Kiểm tra nếu tìm thấy thông tin khách hàng ứng với ID tài khoản đó
                if($stmt->rowCount() > 0) {
                    // Lấy dòng dữ liệu tìm được ra dưới dạng mảng kết hợp
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    // 7. Ánh xạ (Mapping) dữ liệu từ DB vào các thuộc tính nội bộ của Class khachhang
                    $this->ho_ten_dem = $row['ho_ten_dem'];
                    $this->ten = $row['ten'];
                    $this->sdt = $row['so_dien_thoai'];
                    $this->diachi = $row['dia_chi'];
                    $this->hang_thanh_vien = $row['hang_thanh_vien'];
                    $this->id_tai_khoan = $id_tai_khoan;
                    // 8. Đóng gói và trả về một mảng dữ liệu "sạch", đẹp để Controller xuất sang React
                    return [
                        "ten_day_du" => $this->ho_ten_dem . " " . $this->ten, // Tự động gộp họ và tên
                        "sdt" => $this->sdt,
                        "diachi" => $this->diachi,
                        "hang_thanh_vien" => $this->hang_thanh_vien
                    ];
                }
                // Trả về null nếu tài khoản này không có thông tin trong bảng Khach_hang
                return null; 
            } catch(PDOException $e) {
                // Dừng chương trình và xuất thông báo lỗi nếu truy vấn thất bại
                die("Lỗi lấy thông tin khách hàng: " . $e->getMessage());
            }
        }
    }
?>