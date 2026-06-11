<div class="text-center mb-4">
    <h1 class="fw-bold m-0 lkstore-logo-text text-white">
        <i class="fas fa-lock me-2"></i>Đặt mật khẩu
    </h1>
    <p class="text-muted mt-2">Vui lòng thiết lập mật khẩu cho tài khoản.</p>
</div>

<form action="index.php?act=hoantatdangky" method="POST">
    <div class="mb-3">
        <label class="form-label text-white">Tên đăng nhập</label>
        <input type="text" name="username" class="form-control" placeholder="Ví dụ: luan123" required>
    </div>
    <div class="mb-3">
        <label class="form-label text-white">Mật khẩu mới</label>
        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
    </div>
    <div class="mb-3">
        <label class="form-label text-white">Xác nhận mật khẩu</label>
        <input type="password" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu" required>
    </div>
    <button type="submit" class="btn auth-submit-btn w-100">HOÀN TẤT ĐĂNG KÝ</button>
</form>