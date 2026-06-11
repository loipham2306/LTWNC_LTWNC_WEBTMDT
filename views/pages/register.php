<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
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
        <div class="card p-4 rounded border auth-card login-card pb-3">

            <div class="text-center mb-4">
                <h1 class="fw-bold m-0 lkstore-logo-text">
                    <img src="/LTWNC_LTWNC_WEBTMDT/assets/images/img/th.png" alt="Logo" class="h-100px" style="height: 80px; object-fit: contain;" onerror="this.style.display='none';"> 
                </h1>
                <p class="text-muted mt-2">Tạo tài khoản mới để mua sắm!</p>
            </div>

            <form id="registerForm" action="index.php?act=xulydangky" method="POST">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label text-white">Họ và tên đệm</label>
                        <input type="text"
                            name="ho_ten_dem"
                            class="form-control"
                            required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label text-white">Tên</label>
                        <input type="text"
                            name="ten"
                            class="form-control"
                            required>
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Email</label>
                    <input type="email"
                        name="email"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Số điện thoại</label>
                    <input type="text"
                        name="so_dien_thoai"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Địa chỉ</label>
                    <textarea name="dia_chi"
                            class="form-control"
                            rows="3"
                            required></textarea>
                </div>

                <button type="submit"
                        class="btn auth-submit-btn w-100">
                    GỬI MÃ OTP
                </button>
            </form>

        </div>
    </div>
<script>
    // Thêm vào cuối file Register.php
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault(); // CHẶN reload trang
    
    let formData = new FormData(this);
    
    fetch('index.php?act=xulydangky', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Nhận nội dung là HTML
    .then(html => {
        // Tìm thẻ card của bạn và thay nội dung bằng form mới
        document.querySelector('.card').innerHTML = html;
    })
    .catch(error => console.error('Lỗi:', error));
});
document.addEventListener('submit', function(e) {
    // Kiểm tra xem form được nhấn có phải là form trong .card không
    let form = e.target;
    if (form.closest('.card')) {
        e.preventDefault(); 
        
        let formData = new FormData(form);
        let card = document.querySelector('.card');
        
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            card.innerHTML = html;
        })
        .catch(error => console.error('Lỗi:', error));
    }
});
</script>
</body>
</html>