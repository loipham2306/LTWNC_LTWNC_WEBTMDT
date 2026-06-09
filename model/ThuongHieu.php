<?php
    class ThuongHieu{
        private	$id_thuong_hieu;	
        private $ten_thuong_hieu;
        private	$mo_ta;
        private	$hinh_anh_logo;
        private	$trang_thai;	
        private $ngay_tao;
        private $table_name="thuong_hieu";
        private $conn=null;	
        // Sửa hàm __construct thành như thế này:
        public function __construct($db = null) {
            $this->conn = $db;
        }
        public function setData($ten, $mo_ta, $hinh_anh, $trang_thai, $id = null) {
            $this->ten_thuong_hieu = $ten;
            $this->mo_ta = $mo_ta;
            $this->hinh_anh_logo = $hinh_anh;
            $this->trang_thai = $trang_thai;
            if ($id !== null) {
                $this->id_thuong_hieu = $id;
            }
        }

        public function getIdThuongHieu() {
            return $this->id_thuong_hieu;
        }

        public function setIdThuongHieu($id_thuong_hieu) {
            $this->id_thuong_hieu = (int)$id_thuong_hieu;
        }

        public function getTenThuongHieu() {
            return $this->ten_thuong_hieu;
        }

        public function setTenThuongHieu($ten_thuong_hieu) {
            $this->ten_thuong_hieu = htmlspecialchars(strip_tags($ten_thuong_hieu));
        }

        public function getMoTa() {
            return $this->mo_ta;
        }

        public function setMoTa($mo_ta) {
            $this->mo_ta = htmlspecialchars(strip_tags($mo_ta));
        }

        public function getHinhAnhLogo() {
            return $this->hinh_anh_logo;
        }

        public function setHinhAnhLogo($hinh_anh_logo) {
            $this->hinh_anh_logo = htmlspecialchars(strip_tags($hinh_anh_logo));
        }

        public function getTrangThai() {
            return $this->trang_thai;
        }

        public function setTrangThai($trang_thai) {
            $this->trang_thai = (int)$trang_thai;
        }

        public function getNgayTao() {
            return $this->ngay_tao;
        }

        public function setNgayTao($ngay_tao) {
            $this->ngay_tao = $ngay_tao;
        }
      
        public function getTatCaThuongHieu() {
            // 1. Sử dụng LEFT JOIN để đếm sản phẩm. 
            // COUNT(sp.id_san_pham) sẽ trả về 0 nếu thương hiệu đó chưa có sản phẩm nào.
            $query = "SELECT th.*, COUNT(sp.id_san_pham) AS count 
                    FROM " . $this->table_name . " th
                    LEFT JOIN san_pham sp ON th.id_thuong_hieu = sp.id_thuong_hieu
                    GROUP BY th.id_thuong_hieu
                    ORDER BY th.id_thuong_hieu DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            // Trả về mảng dữ liệu có kèm cột 'count'
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        }
        public function createThuongHieu() {
            $query = "INSERT INTO " . $this->table_name . " (ten_thuong_hieu, mo_ta, hinh_anh_logo, trang_thai) 
                    VALUES (:ten_thuong_hieu, :mo_ta, :hinh_anh_logo, :trang_thai)";
            
            $stmt = $this->conn->prepare($query);

            // Làm sạch dữ liệu đầu vào chống SQL Injection / XSS
            $this->ten_thuong_hieu = htmlspecialchars(strip_tags($this->ten_thuong_hieu));
            $this->mo_ta = htmlspecialchars(strip_tags($this->mo_ta));
            $this->hinh_anh_logo = htmlspecialchars(strip_tags($this->hinh_anh_logo));
            $this->trang_thai = htmlspecialchars(strip_tags($this->trang_thai));

            // ĐIỀN ĐỦ 2 THAM SỐ: Nhớ truyền các biến tương ứng ở vế thứ 2 bạn nhé
            $stmt->bindParam(':ten_thuong_hieu', $this->ten_thuong_hieu);
            $stmt->bindParam(':mo_ta', $this->mo_ta);
            $stmt->bindParam(':hinh_anh_logo', $this->hinh_anh_logo);
            $stmt->bindParam(':trang_thai', $this->trang_thai);

            if($stmt->execute()) {
                return true;
            }
            
            return false;
        }
        public function updateThuongHieu(){
            // 1. Sửa lỗi dính chữ SET, sửa thanh_thai thành trang_thai, sửa id_tai_khoan thành id_thuong_hieu
            $query = "UPDATE " . $this->table_name . " 
                    SET ten_thuong_hieu = :ten_thuong_hieu, 
                        mo_ta = :mo_ta, 
                        hinh_anh_logo = :hinh_anh_logo, 
                        trang_thai = :trang_thai
                    WHERE id_thuong_hieu = :id_thuong_hieu";
                    
            $stmt = $this->conn->prepare($query);

            // 2. Làm sạch dữ liệu
            $this->ten_thuong_hieu = htmlspecialchars(strip_tags($this->ten_thuong_hieu));
            $this->mo_ta           = htmlspecialchars(strip_tags($this->mo_ta));
            $this->trang_thai      = htmlspecialchars(strip_tags($this->trang_thai));
            $this->hinh_anh_logo   = htmlspecialchars(strip_tags($this->hinh_anh_logo));
            $this->id_thuong_hieu  = (int)$this->id_thuong_hieu; // Ép kiểu số nguyên cho ID

            // 3. Sửa dấu chấm (.) thành dấu phẩy (,) để truyền đủ 2 tham số riêng biệt
            $stmt->bindParam(":ten_thuong_hieu", $this->ten_thuong_hieu);
            $stmt->bindParam(":mo_ta", $this->mo_ta);
            $stmt->bindParam(":hinh_anh_logo", $this->hinh_anh_logo);
            $stmt->bindParam(":trang_thai", $this->trang_thai);
            
            // Bắt buộc phải bind thêm khóa chính ID để câu lệnh WHERE tìm đúng thương hiệu cần sửa
            $stmt->bindParam(":id_thuong_hieu", $this->id_thuong_hieu, PDO::PARAM_INT);

            // 4. Thực thi
            if($stmt->execute()){
                return true;
            } else {
                return false;
            }
        }
        public function deleteThuongHieu(){
            $query = "DELETE FROM " . $this->table_name . " WHERE id_thuong_hieu = :id_thuong_hieu";
            $stmt= $this->conn->prepare($query);
            $this->id_thuong_hieu = (int)$this->id_thuong_hieu;
            $this->id_thuong_hieu=htmlspecialchars(strip_tags($this->id_thuong_hieu));
            $stmt->bindParam(":id_thuong_hieu", $this->id_thuong_hieu, PDO::PARAM_INT);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }
        public function getAllThuongHieuWithCount() {
            // Sử dụng LEFT JOIN để đếm cả những thương hiệu chưa có sản phẩm nào (số lượng là 0)
            $sql = "SELECT th.id_thuong_hieu, th.ten_thuong_hieu, COUNT(sp.id_san_pham) as count 
                    FROM thuong_hieu th
                    LEFT JOIN san_pham sp ON th.id_thuong_hieu = sp.id_thuong_hieu
                    GROUP BY th.id_thuong_hieu";
                    
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    }
?>