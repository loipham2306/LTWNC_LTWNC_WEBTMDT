<?php
    class DanhMuc{
        private $id_danh_muc;
        private $ten_danh_muc;
        private $mo_ta;
        private $trang_thai;
        private $ngay_tao;
        private $ngay_cap_nhat;
        private $table_name="danh_muc";
        private $conn=null;
    // --- GETTERS ---
        public function getIdDanhMuc() { return $this->id_danh_muc; }
        public function getTenDanhMuc() { return $this->ten_danh_muc; }
        public function getMoTa() { return $this->mo_ta; }
        public function getTrangThai() { return $this->trang_thai; }
        public function getNgayTao() { return $this->ngay_tao; }
        public function getNgayCapNhat() { return $this->ngay_cap_nhat; }

        // --- SETTERS ---
        public function setIdDanhMuc($id) { $this->id_danh_muc = (int)$id; }
        
        public function setTenDanhMuc($ten) { 
            $this->ten_danh_muc = htmlspecialchars(strip_tags($ten)); 
        }
        
        public function setMoTa($mo_ta) { 
            $this->mo_ta = htmlspecialchars(strip_tags($mo_ta)); 
        }
        
        public function setTrangThai($tt) { 
            $this->trang_thai = (int)$tt; 
        }        
        public function __construct($db){
            $this->conn = $db;
        }
        public function setData($ten, $mo_ta, $trang_thai, $id = null) {
            $this->setTenDanhMuc($ten);
            $this->setMoTa($mo_ta);
            $this->setTrangThai($trang_thai);
            if ($id !== null) {
                $this->setIdDanhMuc($id);
            }
        }

        public function getDanhMuc(){
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_danh_muc DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt; 
        }
        public function ThemDanhMuc(){
            $query = "INSERT INTO ".$this->table_name."
                    SET ten_danh_muc=:ten,
                        mo_ta=:mo_ta,
                        trang_thai=:trang_thai,
                        ngay_tao=NOW()";
            
            $stmt = $this->conn->prepare($query);

            // Lấy giá trị vào biến trung gian
            $ten = $this->getTenDanhMuc();
            $mo_ta = $this->getMoTa();
            $trang_thai = $this->getTrangThai();

            // Sử dụng bindValue để an toàn hơn cho các giá trị đơn lẻ
            $stmt->bindValue(":ten", $ten);
            $stmt->bindValue(":mo_ta", $mo_ta);
            $stmt->bindValue(":trang_thai", $trang_thai, PDO::PARAM_INT);
            
            return $stmt->execute();
        }
        public function updateDanhMuc() {
            $query = "UPDATE " . $this->table_name . " 
                    SET ten_danh_muc = :ten, 
                        mo_ta = :mo_ta, 
                        trang_thai = :trang_thai, 
                        ngay_cap_nhat = NOW()
                    WHERE id_danh_muc = :id";
                    
            $stmt = $this->conn->prepare($query);

            // 1. Làm sạch dữ liệu (giống mẫu Thương hiệu)
            $this->ten_danh_muc = htmlspecialchars(strip_tags($this->ten_danh_muc));
            $this->mo_ta        = htmlspecialchars(strip_tags($this->mo_ta));
            $this->trang_thai   = (int)$this->trang_thai;
            $this->id_danh_muc  = (int)$this->id_danh_muc;

            // 2. Bind dữ liệu
            $stmt->bindParam(":ten", $this->ten_danh_muc);
            $stmt->bindParam(":mo_ta", $this->mo_ta);
            $stmt->bindParam(":trang_thai", $this->trang_thai, PDO::PARAM_INT);
            $stmt->bindParam(":id", $this->id_danh_muc, PDO::PARAM_INT);

            // 3. Thực thi và bắt lỗi
            try {
                if ($stmt->execute()) {
                    return true;
                }
            } catch (PDOException $e) {
                error_log("SQL Error: " . $e->getMessage());
                return false;
            }
            return false;
        }
        public function deleteDanhMuc() {
            $query = "DELETE FROM " . $this->table_name . " WHERE id_danh_muc = :id";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":id", $this->id_danh_muc, PDO::PARAM_INT);
            return $stmt->execute();
        }
    }
?>