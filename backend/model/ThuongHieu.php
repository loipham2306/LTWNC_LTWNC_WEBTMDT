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
        public function __construct($db){
            $this->conn=$db;
        }	
        public function setIdThuongHieu($id) {
            $this->id_thuong_hieu = (int)$id;
        }
        public function setData($ten_thuong_hieu, $mo_ta, $hinh_anh_logo, $trang_thai, $id_thuong_hieu = null) {
            $this->ten_thuong_hieu = $ten_thuong_hieu;
            $this->mo_ta = $mo_ta;
            $this->hinh_anh_logo = $hinh_anh_logo;
            $this->trang_thai = $trang_thai;
            
            // Nếu có truyền ID vào thì gán nội bộ bên trong class
            if ($id_thuong_hieu !== null) {
                $this->id_thuong_hieu = (int)$id_thuong_hieu;
            }
        }
        public function getTatCaThuongHieu(){
            $query="SELECT * FROM ".$this->table_name." ORDER BY id_thuong_hieu DESC";
            $stmt=$this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
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
        
    }
?>