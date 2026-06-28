<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt Lại Mật Khẩu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #111; color: #fff; }
        .auth-container { max-width: 450px; margin: 80px auto; background: #1a1a1a; padding: 40px; border-radius: 12px; border: 1px solid #333; }
        .form-control { background-color: #111; border: 1px solid #444; color: #fff; padding: 12px; }
        .form-control:focus { background-color: #222; border-color: #F28B00; box-shadow: none; color: #fff; }
        .btn-orange { background-color: #F28B00; color: #fff; font-weight: bold; padding: 12px; border: none; border-radius: 8px; transition: 0.3s; }
        .btn-orange:hover { background-color: #d67a00; transform: translateY(-2px); }
        
        /* Chỉnh màu chữ cho popup SweetAlert2 hợp với theme tối */
        .swal2-popup { background: #1a1a1a !important; color: #fff !important; border: 1px solid #333 !important; }
        .swal2-title { color: #F28B00 !important; }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-container shadow-lg">
            <div class="text-center mb-4">
                <img src="../assets/images/img/th.png" alt="Logo" style="height: 80px; margin-bottom: 20px; object-fit: contain;">
                <h3 class="fw-bold text-warning">Tạo Mật Khẩu Mới</h3>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger text-center"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="index.php?act=XuLyDatLaiMatKhau" method="POST" id="resetForm">
                <div class="mb-4">
                    <label class="form-label text-white-50">Nhập mật khẩu mới</label>
                    <input type="password" name="mat_khau_moi" class="form-control" required minlength="6" placeholder="Ít nhất 6 ký tự">
                </div>
                
                <button type="submit" class="btn btn-orange w-100">Cập Nhật Mật Khẩu</button>
            </form>
        </div>
    </div>

    <?php if (isset($_SESSION['reset_success'])): ?>
    <script>
        // Ẩn form đi cho đẹp lúc hiện popup
        document.getElementById('resetForm').style.display = 'none';

        Swal.fire({
            title: 'Thành Công!',
            text: 'Mật khẩu của bạn đã được cập nhật an toàn.',
            icon: 'success',
            background: '#1a1a1a',
            color: '#fff',
            confirmButtonColor: '#F28B00',
            confirmButtonText: 'Đăng nhập ngay',
            allowOutsideClick: false // Không cho bấm ra ngoài để tắt
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php?act=Login';
            }
        });
    </script>
    <?php unset($_SESSION['reset_success']); endif; ?>
</body>
</html>