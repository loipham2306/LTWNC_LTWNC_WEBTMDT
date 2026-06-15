<?php
session_start();
require_once '../config/database.php';

// Kiểm tra xem đã đăng nhập chưa và có gửi POST không
if (!isset($_SESSION['user']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/pages/login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();

// Lấy ID tài khoản từ Session
$id_tai_khoan = $_SESSION['user']['id_tai_khoan']; 
$ho_ten_dem = trim($_POST['ho_ten_dem']);
$ten = trim($_POST['ten']);
$so_dien_thoai = trim($_POST['so_dien_thoai']);
$dia_chi = trim($_POST['dia_chi']);

try {
    // Thực hiện cập nhật vào bảng tai_khoan
    $sql = "UPDATE tai_khoan 
            SET ho_ten_dem = :ho_ten_dem, ten = :ten, so_dien_thoai = :sdt, dia_chi = :dia_chi 
            WHERE id_tai_khoan = :id";
            
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':ho_ten_dem', $ho_ten_dem);
    $stmt->bindParam(':ten', $ten);
    $stmt->bindParam(':sdt', $so_dien_thoai);
    $stmt->bindParam(':dia_chi', $dia_chi);
    $stmt->bindParam(':id', $id_tai_khoan);
    
    if ($stmt->execute()) {
        // Cập nhật lại Session để hiển thị ngay lập tức lên giao diện
        $_SESSION['user']['ho_ten_dem'] = $ho_ten_dem;
        $_SESSION['user']['ten'] = $ten;
        $_SESSION['user']['so_dien_thoai'] = $so_dien_thoai;
        $_SESSION['user']['dia_chi'] = $dia_chi;

        $_SESSION['success'] = "Cập nhật thông tin thành công!";
    } else {
        $_SESSION['error'] = "Có lỗi xảy ra, không thể cập nhật.";
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Lỗi Database: " . $e->getMessage();
}

// ===== ĐÃ SỬA: Đổi từ 'userprofile.php' thành 'UserProfile.php' cho đúng tên file thật =====
header("Location: ../views/pages/UserProfile.php");
exit();
?>