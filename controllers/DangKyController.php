<?php
session_start();

// Gọi file kết nối Database
require_once '../config/database.php';

// Kiểm tra xem người dùng có thực sự bấm nút Đăng Ký (gửi dạng POST) hay không
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Database();
    $conn = $db->getConnection();

    // Lấy dữ liệu từ form và xóa khoảng trắng thừa
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 1. Kiểm tra xem có nhập thiếu thông tin không
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
        header("Location: ../views/pages/register.php"); // Sửa tên file này nếu file đăng ký của bạn tên khác
        exit();
    }

    try {
        // 2. Kiểm tra xem Tài khoản hoặc Email này đã tồn tại trong Database chưa
        // LƯU Ý: Đổi tên bảng 'tai_khoan' và các cột cho khớp với phpMyAdmin của bạn
        $checkSql = "SELECT * FROM tai_khoan WHERE ten_dang_nhap = :username OR email = :email";
        $stmtCheck = $conn->prepare($checkSql);
        $stmtCheck->bindParam(':username', $username);
        $stmtCheck->bindParam(':email', $email);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            $_SESSION['error'] = "Tên đăng nhập hoặc Email này đã có người sử dụng!";
            header("Location: ../views/pages/register.php");
            exit();
        }

        // 3. Mã hóa mật khẩu (Tuyệt đối không lưu mật khẩu thô vào Database)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 4. Thêm tài khoản mới vào Database
        $insertSql = "INSERT INTO tai_khoan (ten_dang_nhap, email, mat_khau) VALUES (:username, :email, :password)";
        $stmtInsert = $conn->prepare($insertSql);
        $stmtInsert->bindParam(':username', $username);
        $stmtInsert->bindParam(':email', $email);
        $stmtInsert->bindParam(':password', $hashed_password);

        if ($stmtInsert->execute()) {
            // Đăng ký thành công, báo xanh và chuyển về trang Đăng Nhập
            $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
            header("Location: ../views/pages/login.php");
            exit();
        } else {
            $_SESSION['error'] = "Có lỗi hệ thống xảy ra, vui lòng thử lại!";
            header("Location: ../views/pages/register.php");
            exit();
        }

    } catch (PDOException $e) {
        // Bắt lỗi nếu Database có vấn đề
        $_SESSION['error'] = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
        header("Location: ../views/pages/register.php");
        exit();
    }
} else {
    // Nếu ai đó cố tình gõ đường link trực tiếp vào Controller thì đuổi về trang đăng ký
    header("Location: ../views/pages/register.php");
    exit();
}
?>