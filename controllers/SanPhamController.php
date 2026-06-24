<?php
include_once '../model/SanPham.php';
include_once '../model/BienTheSanPham.php'; 
include_once '../model/Danhmuc.php';
include_once '../model/ThuongHieu.php';

class SanPhamController {
    private $db;
    private $sp_model;
    private $bt_model; // Model biến thể

    public function __construct($db) {
        $this->db = $db;
        $this->sp_model = new SanPham($db);
        $this->bt_model = new BienTheSanPham($db);
    }

    public function handle($act) {
        switch ($act) {
            case 'San_Pham':
                $this->danhSach();
                break;
            case 'themSP':
                $this->xuLyThem();
                break;
            case 'suaSP':
                $this->xuLySua();
                break;
            case 'xoaSP':
                $this->xuLyXoa();
                break;
            case 'layBienThe':
                $id = $_GET['id'] ?? null; 
    
                if ($id) {
                    $this->hienThiBienThe($id);
                } else {
                    echo "Không tìm thấy sản phẩm";
                }
                break;
            default:
                $this->danhSach();
                break;
        }
    }
    // Trong Controller (index.php)
    public function hienThiBienThe($id_sp) {
        $danh_sach_bien_the = $this->bt_model->LayBienTheTheoSanPham($id_sp);
        
        // Đừng gọi require_once trang Admin nữa! 
        // Hãy tạo file: views/components/render_bien_the.php
        require_once '../views/components/render_bien_the.php'; 
        exit; // Dừng lại ở đây, không in thêm gì nữa
    }  
    // Ví dụ tại SanPhamController.php     
    private function danhSach() {
        // 1. Cấu hình phân trang
        $limit = 10; // Số sản phẩm mỗi trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // 2. Lấy dữ liệu
        // Bạn cần viết thêm hàm đếm tổng sản phẩm trong Model (ví dụ: countSanPham)
        $totalProducts = $this->sp_model->countSanPham(); 
        $totalPages = ceil($totalProducts / $limit);

        // Lấy danh sách sản phẩm có phân trang (Cần sửa query trong model thêm LIMIT và OFFSET)
        $stmt = $this->sp_model->LayTatCaSanPhamPhanTrang($limit, $offset);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Lấy dữ liệu cho dropdown
        $dmModel = new DanhMuc($this->db);
        $thModel = new ThuongHieu($this->db);
        $categories = $dmModel->getDanhMuc()->fetchAll(PDO::FETCH_ASSOC);
        $brands = $thModel->getTatCaThuongHieu();

        include "../views/pages/admin/QuanLySanPham.php";
    }
     // Trong SanPhamController.php
    public function hienThiTrangChu() {
        $raw_products = $this->sp_model->getSanPhamHome(24); 
    $products = [];
    
    foreach ($raw_products as $row) {
        $products[] = [
            'id'       => $row['id_san_pham'],
            'name'     => $row['ten_san_pham'],
            'img'      => $row['hinh_anh'],
            'price'    => $row['gia_co_ban'],
            'oldPrice' => $row['gia_khuyen_mai'] ?? 0,
            'category' => $row['ten_danh_muc'] ?? 'Chưa phân loại',
            'brand'    => $row['ten_thuong_hieu'] ?? 'Khác'
        ];
    }
    return $products; // TRẢ VỀ MẢNG DỮ LIỆU THAY VÌ INCLUDE VIEW
}
    private function xuLyThem() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $this->db->beginTransaction(); // Bắt đầu transaction
                if ($_POST['gia_co_ban'] < 0) {
                    throw new Exception("Giá sản phẩm không hợp lệ!");
                }
                $img = $this->uploadFile();
                
                // 1. Lưu sản phẩm chính
                $this->sp_model->setData(
                    $_POST['ten_san_pham'],
                    $_POST['gia_co_ban'], // Đã đổi tên
                    $_POST['mo_ta'],
                    $img,
                    $_POST['id_danh_muc'],
                    $_POST['id_thuong_hieu'],
                    $_POST['trang_thai']
                );

                if ($this->sp_model->ThemSanPham()) {
                    $id_san_pham_moi = $this->db->lastInsertId();
                    
                    // 2. Lưu danh sách biến thể
                    if (!empty($_POST['size'])) {
                        foreach ($_POST['size'] as $index => $size) {
                            $gia_ban = $_POST['gia_ban'][$index] ?? 0;
                            $ton_kho = $_POST['ton_kho'][$index] ?? 0;
                            if ($gia_ban < 0 || $ton_kho < 0) {
                                throw new Exception("Biến thể tại dòng " . ($index + 1) . " có giá hoặc số lượng âm!");
                            }
                            $this->bt_model->id_san_pham = $id_san_pham_moi;
                            $this->bt_model->kich_co = $size;
                            $this->bt_model->mau_sac = $_POST['mau'][$index];
                            $this->bt_model->gia_ban = $_POST['gia_ban'][$index] ?? 0;
                            $this->bt_model->so_luong_ton = $_POST['ton_kho'][$index] ?? 0;
                            $this->bt_model->hinh_anh_bien_the = ''; // Xử lý nếu có upload ảnh riêng
                            // Debug để xem dữ liệu có được gửi lên không

                            $this->bt_model->hinh_anh_bien_the = $this->uploadFileBienThe($index) ?? '';
                            $this->bt_model->ThemBienThe();
                        }
                    }
                    $this->db->commit();
                    $_SESSION['success'] = "Thêm sản phẩm và biến thể thành công!";
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
            }
            header('Location: index.php?act=QuanLySanPham');
            exit();
        }
    }

    private function xuLySua() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $this->db->beginTransaction();
                
                $id_sp = $_POST['id_san_pham'];
                // FIX: Lấy ảnh cũ từ input hidden của form
                if ($_POST['gia_co_ban'] < 0) {
                    throw new Exception("Giá sản phẩm không không hợp lệ!");
                }
                $img_cu = $_POST['hinh_anh_cu'] ?? ''; // Đảm bảo khớp với name="hinh_anh_cu" trong HTML
                // Gọi hàm upload
                $img_moi = $this->uploadFile(); 
                // Logic: Nếu có upload ảnh mới thì dùng ảnh mới, nếu không thì dùng ảnh cũ
                $img = (!empty($img_moi)) ? $img_moi : $img_cu;
                // 1. Cập nhật SP chính
                $this->sp_model->id_san_pham = $id_sp;
                $this->sp_model->setData(
                    $_POST['ten_san_pham'],
                    $_POST['gia_co_ban'],
                    $_POST['mo_ta'],
                    $img, // Dùng biến $img đã xử lý ở trên
                    $_POST['id_danh_muc'],
                    $_POST['id_thuong_hieu'],
                    $_POST['trang_thai']
                );
                $this->sp_model->CapNhatSanPham();

                // 2. Xử lý cập nhật biến thể
                $this->bt_model->XoaBienTheTheoSanPham($id_sp);
                if (!empty($_POST['size'])) {
                    // Trong vòng lặp foreach của biến thể:
                    foreach ($_POST['size'] as $index => $size) {
                        $gia_ban = $_POST['gia_ban'][$index] ?? 0;
                        $ton_kho = $_POST['ton_kho'][$index] ?? 0;

                        // RÀNG BUỘC: Kiểm tra số âm cho biến thể
                        if ($gia_ban < 0 || $ton_kho < 0) {
                            throw new Exception("Giá bán hoặc tồn kho của biến thể không hợp lệ!");
                        }
                        // 1. Lấy tên ảnh cũ từ input hidden
                        $anh_bt_cu = $_POST['anh_cu_bien_the'][$index] ?? '';
                        
                        // 2. Thử upload ảnh mới cho dòng này
                        $anh_bt_moi = $this->uploadFileBienThe($index);
                        
                        // 3. Quyết định dùng ảnh nào: nếu có ảnh mới thì dùng, không thì lấy ảnh cũ
                        $img_bt = (!empty($anh_bt_moi)) ? $anh_bt_moi : $anh_bt_cu;

                        // 4. Gán dữ liệu vào model
                        $this->bt_model->id_san_pham = $id_sp;
                        $this->bt_model->kich_co = $size;
                        $this->bt_model->mau_sac = $_POST['mau'][$index];
                        $this->bt_model->gia_ban = $_POST['gia_ban'][$index] ?? 0;
                        $this->bt_model->so_luong_ton = $_POST['ton_kho'][$index] ?? 0;
                        $this->bt_model->hinh_anh_bien_the = $img_bt; // Lưu tên ảnh vào DB
                        
                        $this->bt_model->ThemBienThe();
                    }
                }
                
                $this->db->commit();
                $_SESSION['success'] = "Cập nhật sản phẩm thành công!";
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = $e->getMessage();
            }
            header('Location: index.php?act=QuanLySanPham');
            exit();
        }
    }

    private function xuLyXoa() {
        if (isset($_POST['id_san_pham'])) {
            try {
                $this->sp_model->id_san_pham = $_POST['id_san_pham'];
                
                // Thực hiện xóa
                $this->sp_model->XoaSanPham();
                
                // Nếu không có lỗi xảy ra, nghĩa là xóa thành công
                $_SESSION['success'] = "Đã xóa sản phẩm thành công!";
                
            } catch (PDOException $e) {
                // Kiểm tra mã lỗi 1451 (ràng buộc khóa ngoại)
                if ($e->getCode() == '23000') {
                    $_SESSION['error'] = "Không thể xóa: Sản phẩm này đang có biến thể tồn tại, vui lòng xóa biến thể trước!";
                } else {
                    $_SESSION['error'] = "Xóa thất bại: " . $e->getMessage();
                }
            }
        }
        header('Location: index.php?act=QuanLySanPham');
        exit();
    }
    private function uploadFile() {
        if (isset($_FILES['hinh_anh_file']) && $_FILES['hinh_anh_file']['error'] == 0) {
            $targetDir = "../assets/images/products/";
            $fileName = time() . '_' . basename($_FILES['hinh_anh_file']['name']);
            move_uploaded_file($_FILES['hinh_anh_file']['tmp_name'], $targetDir . $fileName);
            return $fileName;
        }
        return '';
    }
    private function uploadFileBienThe($index) {
        // Kiểm tra xem có tồn tại file và không có lỗi
        if (isset($_FILES['anh_bien_the']) && $_FILES['anh_bien_the']['error'][$index] === UPLOAD_ERR_OK) {
            
            $file_name = $_FILES['anh_bien_the']['name'][$index];
            $tmp_name = $_FILES['anh_bien_the']['tmp_name'][$index];
            
            // Đặt tên file mới để tránh trùng lặp
            $new_name = time() . '_' . $file_name;
            $upload_dir = '../assets/images/products/Bien_The_Products/'; 
            
            // Di chuyển file
            if (move_uploaded_file($tmp_name, $upload_dir . $new_name)) {
                return $new_name; // Trả về tên file đã upload thành công
            }
        }
        return null; // Trả về null nếu không có file hoặc lỗi
    }
}