<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - LuLoShop</title>
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
                    <img src="/LTWNC_LTWNC_WEBTMDT/assets/images/img/th.png" alt="Logo" class="h-100px" style="height: 80px; object-fit: contain;" onerror="this.style.display='none';"> 
                </h1>
                <p class="text-muted mt-2">Chào mừng bạn quay trở lại!</p>
            </div>
            <form action="/LTWNC_LTWNC_WEBTMDT/controllers/index.php?act=xuly_dangnhap" method="POST">
                <div class="mb-3 auth-input-group">
                    <label class="form-label fw-bold text-white">Tên đăng nhập hoặc Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Nhập tài khoản..." required>
                    </div>
                </div>

                <div class="mb-4 auth-input-group">
                    <div class="d-flex justify-content-between">
                        <label class="form-label fw-bold text-white">Mật khẩu</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." required>
                    </div>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger text-center my-3 fw-bold py-2 rounded"
                        style="color: #ff4d4f; background-color: #fff1f0; border: 1px solid #ffa39e; font-size: 14px;">
                        ❌ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <button type="submit" class="btn w-100 py-2 text-uppercase fw-bold auth-submit-btn rounded-pill shadow-sm transition-all mb-3">
                    ĐĂNG NHẬP
                </button>

                <div class="text-center mt-3 d-flex flex-column gap-2 pb-3">
                    <div>
                        <span class="text-muted">Chưa có tài khoản? </span>
                        <a href="/LTWNC_LTWNC_WEBTMDT/controllers/index.php?act=Register" class="auth-switch-link fw-bold">Đăng ký ngay</a>
                    </div>
                    <a href="#" class="padding text-decoration-none small text-warning">Quên mật khẩu?</a>
                </div>
                
            </form>

        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>