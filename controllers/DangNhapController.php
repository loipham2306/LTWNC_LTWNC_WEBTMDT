<?php
// 1. BẬT SESSION ĐỂ LƯU TRẠNG THÁI ĐĂNG NHẬP (Bắt buộc phải có ở dòng đầu tiên)
session_start();

require_once '../config/database.php';
// Lưu ý: Nếu thư mục của bạn tên là 'models' (có s) thì nhớ thêm chữ s vào nhé
require_once '../model/taikhoan.php'; 
require_once '../model/khachhang.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 2. NHẬN DỮ LIỆU TỪ FORM HTML (Dùng $_POST thay vì json_decode)
    $ten_dang_nhap = trim($_POST['username'] ?? '');
    $mat_khau_nhap = $_POST['password'] ?? '';

    // Nếu để trống
    if (empty($ten_dang_nhap) || empty($mat_khau_nhap)) {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin đăng nhập!";
        header("Location: ../views/login.php"); // Đá về trang đăng nhập
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();
    
    $taiKhoanModel = new taikhoan($db);
    $khachHangModel = new khachhang($db);

    $userRow = $taiKhoanModel->checkTaiKhoan($ten_dang_nhap);

    if ($userRow) {
        // Kiểm tra xem tài khoản có bị khóa không
        if ($userRow['trang_thai'] == 0) {
            $_SESSION['error'] = "Tài khoản của bạn đã bị khóa!";
            header("Location: ../views/login.php");
            exit;
        }

        if (password_verify($mat_khau_nhap, $userRow['mat_khau'])) {
            
            $userData = [
                "id_tai_khoan"   => $userRow['id_tai_khoan'],
                "ten_dang_nhap"  => $userRow['ten_dang_nhap'],
                "vai_tro"        => $userRow['vai_tro'],
                "ho_ten_dem"     => "",
                "ten"            => $userRow['vai_tro'] === 'admin' ? 'Admin' : 'Khách Hàng',
                "so_dien_thoai"  => "",
                "dia_chi"        => "",
                "hang_thanh_vien"=> ""
            ];

            if ($userRow['vai_tro'] === 'khach hang') {
                $thongTinKhach = $khachHangModel->getThongTinByTaiKhoanId($userRow['id_tai_khoan']);
                
                if ($thongTinKhach) {
                    $ten_day_du = isset($thongTinKhach['ten_day_du']) ? $thongTinKhach['ten_day_du'] : '';
                    if (!empty($ten_day_du)) {
                        $parts = explode(' ', trim($ten_day_du));
                        $userData['ten'] = array_pop($parts); 
                        $userData['ho_ten_dem'] = implode(' ', $parts); 
                    }
                    
                    $userData['so_dien_thoai']   = isset($thongTinKhach['sdt']) ? $thongTinKhach['sdt'] : '';
                    $userData['dia_chi']         = isset($thongTinKhach['diachi']) ? $thongTinKhach['diachi'] : '';
                    $userData['hang_thanh_vien'] = isset($thongTinKhach['hang_thanh_vien']) ? $thongTinKhach['hang_thanh_vien'] : 'silver';
                }
            }

            // 3. LƯU THÔNG TIN VÀO SESSION VÀ CHUYỂN TRANG
            $_SESSION['user'] = $userData;

            // Dựa vào vai trò để đá sang trang tương ứng
            if ($userData['vai_tro'] === 'admin') {
                header("Location: ../views/admin/dashboard.php"); // Sẽ tạo sau
            } else if ($userData['vai_tro'] === 'nhanvien') {
                header("Location: ../views/employee/orders.php"); // Sẽ tạo sau
            } else {
                header("Location: ../index.php"); // Đá ra trang chủ gốc
            }
            exit;

        } else {
            $_SESSION['error'] = "Mật khẩu không chính xác!";
            header("Location: ../views/login.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Tài khoản hoặc email không tồn tại!";
        header("Location: ../views/login.php");
        exit;
    }
} else {
    // Truy cập bậy bạ thì tống về trang login
    header("Location: ../views/login.php");
    exit;
}S
?>