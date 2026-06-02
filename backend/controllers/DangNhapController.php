<?php
// Cho phép React (đang chạy ở cổng 5173) có quyền gọi vào API này (Sửa lỗi CORS)
header("Access-Control-Allow-Origin: http://localhost:5173"); 
// Cho phép React gửi dữ liệu dạng JSON và các thông tin xác thực qua Header
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
// Định dạng dữ liệu trả về của API bắt buộc luôn luôn là JSON
header("Content-Type: application/json; charset=UTF-8"); 
// Chỉ chấp nhận phương thức POST (vì đăng nhập cần bảo mật)
header("Access-Control-Allow-Methods: POST, OPTIONS");

// Mẹo xử lý khi trình duyệt gửi yêu cầu kiểm tra trước (OPTIONS request)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require_once '../config/database.php';
require_once '../model/taikhoan.php';
require_once '../model/khachhang.php';
// Vì React gửi dữ liệu dạng JSON thô (raw data), nên ta phải dùng luồng php://input để đọc
$data = json_decode(file_get_contents("php://input"), true);
// Kiểm tra xem có đúng là khách gửi yêu cầu POST không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin tài khoản (username hoặc email) và mật khẩu từ React gửi lên
    $ten_dang_nhap = $data['username_or_email'] ?? '';
    $mat_khau_nhap = $data['password'] ?? '';

    // Nếu người dùng để trống 1 trong 2 ô
    if (empty($ten_dang_nhap) || empty($mat_khau_nhap)) {
        http_response_code(400); // Mã 400: Dữ liệu gửi lên không hợp lệ
        echo json_encode(["success" => false, "message" => "Vui lòng nhập đầy đủ thông tin đăng nhập!"]);
        exit;
    }
    // ==========================================
    // BƯỚC 4: KHỞI TẠO ĐỐI TƯỢNG VÀ TRUY VẤN
    // ==========================================
    $database = new Database();
    $db = $database->getConnection();
    $taiKhoanModel = new taikhoan($db);
    $khachHangModel = new khachhang($db);
    // Gọi hàm checkTaiKhoan mà bạn vừa viết bên Model TaiKhoan để tìm user
    $userRow = $taiKhoanModel->checkTaiKhoan($ten_dang_nhap);
    // Nếu tìm thấy tài khoản tồn tại trong hệ thống
    if ($userRow) {
        // Dùng hàm trọng tài password_verify để so mật khẩu thô và mật khẩu đã băm trong DB
        if (password_verify($mat_khau_nhap, $userRow['mat_khau'])) {
            // 1. Nhúng Model và khởi tạo đối tượng khách hàng
            include_once '../model/khachhang.php';
            $khachhang = new khachhang($db);
            
            // Chuẩn bị sẵn cấu trúc dữ liệu mặc định để trả về cho React
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

            // 2. Nếu vai trò là 'khach hang', lội vào DB lấy thông tin chi tiết
            if ($userRow['vai_tro'] === 'khach hang') {
                // ĐÃ SỬA: Thay $userRow['$id_tai_khoan'] thành $userRow['id_tai_khoan'] để hết lỗi Warning
                $thongTinKhach = $khachhang->getThongTinByTaiKhoanId($userRow['id_tai_khoan']);
                
                if ($thongTinKhach) {
                    // Tách "Nguyễn Minh Luân" thành Họ tên đệm và Tên để React tự động hiển thị lên form
                    $ten_day_du = isset($thongTinKhach['ten_day_du']) ? $thongTinKhach['ten_day_du'] : '';
                    if (!empty($ten_day_du)) {
                        $parts = explode(' ', trim($ten_day_du));
                        $userData['ten'] = array_pop($parts); // Lấy chữ cuối cùng làm Tên (Ví dụ: Luân)
                        $userData['ho_ten_dem'] = implode(' ', $parts); // Các chữ còn lại làm Họ đệm (Ví dụ: Nguyễn Minh)
                    }
                    
                    // Map chính xác các key từ Database (sdt, diachi) sang biến form của React (so_dien_thoai, dia_chi)
                    $userData['so_dien_thoai']   = isset($thongTinKhach['sdt']) ? $thongTinKhach['sdt'] : '';
                    $userData['dia_chi']         = isset($thongTinKhach['diachi']) ? $thongTinKhach['diachi'] : '';
                    $userData['hang_thanh_vien'] = isset($thongTinKhach['hang_thanh_vien']) ? $thongTinKhach['hang_thanh_vien'] : 'silver';
                }
            }

            // 3. Đăng nhập thành công rực rỡ -> Trả về JSON một lần duy nhất sạch sẽ
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Đăng nhập thành công!",
                "user"    => $userData
            ]);
            exit;

        } else {
            http_response_code(401); 
            echo json_encode(["success" => false, "message" => "Mật khẩu không chính xác!"]);
            exit;
        }
    } else {
        // Không tìm thấy tên đăng nhập hoặc email nào như vậy
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Tài khoản hoặc email không tồn tại!"]);
    }
} else {
    // Nếu cố tình dùng các phương thức khác như GET, PUT, DELETE truy cập vào link này
    http_response_code(405); // Mã 405: Phương thức không được phép
    echo json_encode(["success" => false, "message" => "Phương thức API không hỗ trợ!"]);
}
?>