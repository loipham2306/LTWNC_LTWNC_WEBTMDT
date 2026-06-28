<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận OTP</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #111; color: #fff; }
        .auth-container { max-width: 450px; margin: 80px auto; background: #1a1a1a; padding: 40px; border-radius: 12px; border: 1px solid #333; }
        .form-control { background-color: #111; border: 1px solid #444; color: #fff; padding: 12px; text-align: center; font-size: 1.2rem; letter-spacing: 2px;}
        .form-control:focus { background-color: #222; border-color: #F28B00; box-shadow: none; color: #fff; }
        .btn-orange { background-color: #F28B00; color: #fff; font-weight: bold; padding: 12px; border: none; border-radius: 8px; transition: 0.3s; }
        .btn-orange:hover { background-color: #d67a00; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-container shadow-lg">
            <div class="text-center mb-4">
                <img src="../assets/images/img/th.png" alt="Logo" style="height: 80px; margin-bottom: 20px; object-fit: contain;">
                <h3 class="fw-bold text-warning">Xác Nhận OTP</h3>
                <p class="text-white-50 small">Mã OTP gồm 6 số đã được gửi đến email:<br><strong class="text-white"><?= $_SESSION['reset_email'] ?? '' ?></strong></p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger text-center"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="index.php?act=XacNhanOTPQuenMat" method="POST">
                <div class="mb-4">
                    <input type="text" name="otp_input" class="form-control fw-bold text-warning" required maxlength="6" placeholder="Nhập mã 6 số">
                </div>
                <button type="submit" class="btn btn-orange w-100">Xác Nhận Ngay</button>
            </form>
        </div>
    </div>
</body>
</html>