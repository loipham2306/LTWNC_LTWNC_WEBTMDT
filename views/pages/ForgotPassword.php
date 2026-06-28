<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên Mật Khẩu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #111; color: #fff; }
        .auth-container { max-width: 450px; margin: 80px auto; background: #1a1a1a; padding: 40px; border-radius: 12px; border: 1px solid #333; }
        .form-control { background-color: #111; border: 1px solid #444; color: #fff; padding: 12px; }
        .form-control:focus { background-color: #222; border-color: #F28B00; box-shadow: none; color: #fff; }
        .btn-orange { background-color: #F28B00; color: #fff; font-weight: bold; padding: 12px; border: none; border-radius: 8px; transition: 0.3s; }
        .btn-orange:hover { background-color: #d67a00; transform: translateY(-2px); }
        .text-orange { color: #F28B00 !important; }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-container shadow-lg">
            <div class="text-center mb-4">
                <img src="../assets/images/img/th.png" alt="Logo" style="height: 80px; margin-bottom: 20px; object-fit: contain;">
                <h3 class="fw-bold text-orange">Quên Mật Khẩu</h3>
                <p class="text-white-50 small">Nhập email của bạn, chúng tôi sẽ gửi mã OTP.</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger text-center"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="index.php?act=XuLyQuenMatKhau" method="POST">
                <div class="mb-3">
                    <label class="form-label text-white-50">Địa chỉ Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="example@gmail.com">
                </div>
                <button type="submit" class="btn btn-orange w-100 mt-3">Gửi Mã OTP</button>
                <div class="text-center mt-4">
                    <a href="index.php?act=Login" class="text-orange text-decoration-none"><i class="fas fa-arrow-left me-2"></i>Quay lại Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>