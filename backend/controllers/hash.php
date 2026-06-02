<?php
// Mật khẩu bạn muốn đặt là gì thì điền vào đây, ví dụ: 123
$password_tho = 'admin123'; 

// Hàm tự động băm chuẩn mã hóa của PHP
$password_hash = password_hash($password_tho, PASSWORD_BCRYPT);

echo "<h3>Mật khẩu thô: " . $password_tho . "</h3>";
echo "<h3>Chuỗi băm chuẩn để dán vào DB (hãy copy toàn bộ dòng dưới):</h3>";
echo "<textarea rows='2' cols='70' style='font-size:16px; font-family:monospace;'>" . $password_hash . "</textarea>";
?>