<?php
    class DanhMuc{
        private $id_danh_muc;
        private $id_danh_muc_cha;
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
        public function getIdDanhMucCha() { return $this->id_danh_muc_cha; }
        // --- SETTERS ---
        public function setIdDanhMuc($id) { $this->id_danh_muc = (int)$id; }
        
        public function setTenDanhMuc($ten) { 
            $this->ten_danh_muc = htmlspecialchars(strip_tags($ten)); 
        }
        public function setIdDanhMucCha($id_cha) { 
            $this->id_danh_muc_cha = (!empty($id_cha) && $id_cha != 0) ? (int)$id_cha : null; 
        }
        public function setMoTa($mo_ta) { 
            $this->mo_ta = htmlspecialchars(strip_tags($mo_ta)); 
        }
        
        public function setTrangThai($tt) { 
            $this->trang_thai = (int)$tt; 
        }        
        public function __construct($db, $data = null){
            $this->conn = $db;

            if (is_array($data)) {
                $ten = $data['ten_danh_muc'] ?? $data['ten'] ?? null;
                $mo_ta = $data['mo_ta'] ?? null;
                $trang_thai = $data['trang_thai'] ?? $data['trangthai'] ?? 1;
                $id = $data['id_danh_muc'] ?? $data['id'] ?? null;
                $id_cha = $data['id_danh_muc_cha'] ?? null;
                $this->setData($ten, $mo_ta, $trang_thai, $id, $id_cha);
            }
        }
        // Trong file DanhMuc.php
        public function setData($ten, $mo_ta, $trang_thai, $id = null, $id_cha = null) {
            $this->setTenDanhMuc($ten);
            $this->setMoTa($mo_ta);
            $this->setTrangThai($trang_thai);
            if ($id !== null) {
                $this->setIdDanhMuc($id);
            }
            $this->setIdDanhMucCha($id_cha);
        }
        public function getDanhMucShop() {
            $sql = "SELECT * FROM danh_muc ORDER BY id_danh_muc_cha ASC, id_danh_muc ASC";            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt; // Trả về statement để Controller fetch
        }
        // Trong file DanhMuc.php
        public function getDanhMuc() {
            // Sử dụng LEFT JOIN để lấy cả tên danh mục cha
            $query = "SELECT d1.*, d2.ten_danh_muc AS ten_danh_muc_cha 
                    FROM " . $this->table_name . " d1 
                    LEFT JOIN " . $this->table_name . " d2 ON d1.id_danh_muc_cha = d2.id_danh_muc 
                    ORDER BY d1.id_danh_muc DESC";
                    
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt; 
        }
        public function ThemDanhMuc() {
            $query = "INSERT INTO " . $this->table_name . "
                    SET ten_danh_muc = :ten,
                        mo_ta        = :mo_ta,
                        id_danh_muc_cha = :id_cha,
                        trang_thai   = :trang_thai,
                        ngay_tao     = NOW()";
            
            try {
                $stmt = $this->conn->prepare($query);

                // Lấy giá trị
                $ten        = $this->getTenDanhMuc();
                $mo_ta      = $this->getMoTa();
                $trang_thai = $this->getTrangThai();

                // Bind giá trị
                $stmt->bindValue(":ten", $ten);
                $stmt->bindValue(":mo_ta", $mo_ta);
                $stmt->bindValue(":id_cha", $this->getIdDanhMucCha(), $this->getIdDanhMucCha() ? PDO::PARAM_INT : PDO::PARAM_NULL);
                $stmt->bindValue(":trang_thai", $trang_thai, PDO::PARAM_INT);
                
                return $stmt->execute();

            } catch (PDOException $e) {
                // Kiểm tra mã lỗi 23000 (Integrity constraint violation - ví dụ: trùng lặp UNIQUE)
                if ($e->getCode() == 23000) {
                    $_SESSION['error'] = "Lỗi: Danh mục '{$this->getTenDanhMuc()}' đã tồn tại trong hệ thống!";
                } else {
                    // Ghi log lỗi để dev kiểm tra, không hiện chi tiết lỗi cho người dùng
                    error_log("Database Error: " . $e->getMessage());
                    $_SESSION['error'] = "Có lỗi xảy ra, vui lòng thử lại sau.";
                }
                return false;
            }
        }
        public function updateDanhMuc() {
            $query = "UPDATE " . $this->table_name . " 
                    SET ten_danh_muc = :ten, 
                        mo_ta = :mo_ta, 
                        id_danh_muc_cha = :id_cha,
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
            $stmt->bindValue(":id_cha", $this->getIdDanhMucCha(), $this->getIdDanhMucCha() ? PDO::PARAM_INT : PDO::PARAM_NULL);
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
            // 1. Kiểm tra xem danh mục này có đang là "cha" của bất kỳ danh mục nào khác không
            $queryCheck = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE id_danh_muc_cha = :id";
            $stmtCheck = $this->conn->prepare($queryCheck);
            $stmtCheck->bindParam(":id", $this->id_danh_muc, PDO::PARAM_INT);
            $stmtCheck->execute();
            
            // Nếu kết quả trả về lớn hơn 0, nghĩa là nó đang có danh mục con
            if ($stmtCheck->fetchColumn() > 0) {
                $_SESSION['error'] = "Không thể xóa: Danh mục này đang chứa danh mục con, vui lòng xóa danh mục con trước!";
                return false;
            }

            // 2. Nếu không có danh mục con, tiến hành xóa
            $queryDelete = "DELETE FROM " . $this->table_name . " WHERE id_danh_muc = :id";
            $stmtDelete = $this->conn->prepare($queryDelete);
            $stmtDelete->bindParam(":id", $this->id_danh_muc, PDO::PARAM_INT);
            
            try {
                return $stmtDelete->execute();
            } catch (PDOException $e) {
                error_log("Lỗi xóa danh mục: " . $e->getMessage());
                $_SESSION['error'] = "Có lỗi xảy ra trong quá trình xóa.";
                return false;
            }
        }
    }
?>