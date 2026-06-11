<div class="text-center mb-4">
    <h1 class="fw-bold m-0 lkstore-logo-text text-white">
        <i class="fas fa-shield-alt me-2"></i>Xác thực OTP
    </h1>
    <p class="text-muted mt-2">Mã xác thực đã được gửi đến email của bạn.</p>
    <?php if(isset($error)): ?>
    <div class="alert alert-danger text-center"><?= $error ?></div>
<?php endif; ?>
</div>

<form action="index.php?act=xacnhanotp" method="POST">
    <div class="mb-3">
        <label class="form-label text-white">Nhập mã OTP</label>
        <input type="text" name="otp_input" class="form-control" placeholder="6 chữ số" required>
    </div>
    <button type="submit" class="btn auth-submit-btn w-100">XÁC NHẬN</button>
</form>