<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - LuLoShop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="/LTWNC_LTWNC_WEBTMDT/assets/bootstrap.min.css">
    <link rel="stylesheet" href="/LTWNC_LTWNC_WEBTMDT/assets/style.css">
    <link rel="stylesheet" href="/LTWNC_LTWNC_WEBTMDT/assets/AuthPages.css">
</head>
<body style="background-color: #111;">

    <div class="container-fluid d-flex auth-page-container">
        <div class="card p-4 rounded border auth-card login-card pb-0">

            <div class="text-center mb-4">
                <h1 class="fw-bold m-0 lkstore-logo-text">
                    <img src="/LTWNC_LTWNC_WEBTMDT/assets/images/img/th.png" alt="Logo" style="height: 60px; object-fit: contain;">
                </h1>
                <p class="text-muted mt-2">Tạo tài khoản mới để mua sắm!</p>
            </div>

            <form action="/LTWNC_LTWNC_WEBTMDT/controllers/DangKyController.php" method="POST">
                
                <div class="mb-3 auth-input-group">
                    <label class="form-label fw-bold text-white">Tên đăng nhập</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Ví dụ: luan1410..." required>
                    </div>
                </div>

                <div class="mb-3 auth-input-group">
                    <label class="form-label fw-bold text-white">Địa chỉ Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Nhập email của bạn..." required>
                    </div>
                </div>

                <div class="mb-4 auth-input-group">
                    <label class="form-label fw-bold text-white">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Tạo mật khẩu..." required>
                    </div>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger text-center my-3 fw-bold py-2 rounded"
                        style="color: #ff4d4f; background-color: #fff1f0; border: 1px solid #ffa39e; font-size: 14px;">
                        ❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success text-center my-3 fw-bold py-2 rounded"
                        style="color: #52c41a; background-color: #f6ffed; border: 1px solid #b7eb8f; font-size: 14px;">
                        ✅ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                
                <button type="submit" class="btn w-100 py-2 text-uppercase fw-bold auth-submit-btn rounded-pill shadow-sm transition-all mb-3">
                    ĐĂNG KÝ
                </button>

                <div class="text-center mt-3 pb-3">
                    <span class="text-muted">Đã có tài khoản? </span>
                    <a href="login.php" class="auth-switch-link fw-bold">Đăng nhập ngay</a>
                </div>
                
            </form>

        </div>
    </div>

</body>
</html>