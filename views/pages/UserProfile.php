<?php
session_start();
// KHI CHƯA ĐĂNG NHẬP MÀ VÀO TRANG NÀY SẼ BỊ ĐÁ VỀ TRANG LOGIN
if (!isset($_SESSION['user'])) {
    header("Location: /LTWNC_LTWNC_WEBTMDT/views/pages/login.php");
    exit();
}
$user = $_SESSION['user'];

// Xử lý tên hiển thị
$ho_ten_dem = $user['ho_ten_dem'] ?? '';
$ten = $user['ten'] ?? '';
$fullName = trim($ho_ten_dem . ' ' . $ten);
if (empty($fullName)) {
    $fullName = $user['ten_dang_nhap'] ?? 'Khách Hàng';
}
$avatarChar = !empty($ten) ? mb_substr($ten, 0, 1, "UTF-8") : 'U';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài Khoản Của Tôi - LuLoShop</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">

    <style>
        body { background-color: #111; color: #fff; font-family: 'Segoe UI', sans-serif; }
        .profile-wrapper { background-color: #111; min-height: 80vh; padding-bottom: 50px; }
        .profile-card { background-color: #1a1a1a; border: 1px solid #333; }
        
        .list-group-custom .list-group-item {
            background-color: transparent; color: #aaa; border: none;
            border-bottom: 1px solid #2a2a2a; transition: all 0.3s ease; cursor: pointer;
        }
        .list-group-custom .list-group-item:hover { background-color: #222; color: #F28B00; }
        .list-group-custom .list-group-item.active { background-color: #F28B00 !important; color: #fff !important; }
        
        .form-control { background-color: #111 !important; border: 1px solid #444 !important; color: #fff !important; }
        .form-control:focus { border-color: #F28B00 !important; box-shadow: 0 0 0 0.25rem rgba(242, 139, 0, 0.25) !important; }
        .form-control:disabled { background-color: #222 !important; color: #666 !important; border-color: #333 !important; }
        
        .table-profile th { background-color: #222; border-bottom: 2px solid #F28B00; color: #F28B00; text-transform: uppercase; }
        .table-profile td { background-color: #1a1a1a; border-bottom: 1px solid #2a2a2a; color: #eee; vertical-align: middle; }
        
        .btn-orange { background-color: #F28B00 !important; color: #fff !important; border: none; }
        .btn-orange:hover { background-color: #d67a00 !important; }
        .btn-outline-orange { border: 1px solid #F28B00; color: #F28B00; background: transparent; }
        .btn-outline-orange:hover { background-color: #F28B00; color: #fff; }
        .text-orange { color: #F28B00 !important; }
        .avatar-circle { background-color: #F28B00 !important; color: #fff; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>

    <?php
    $pageTitle = "Tài Khoản Của Tôi";
    $pageBreadcrumb = "Tài Khoản";
    include __DIR__ . '/../components/Header.php'; 
    include __DIR__ . '/../components/PageHeader.php';
    ?>

    <div class="container-fluid profile-wrapper py-5">
        <div class="container py-5">
            <div class="row g-4">

                <div class="col-lg-3 wow fadeInLeft" data-wow-delay="0.1s">
                    <div class="card profile-card rounded p-4 text-center mb-4">
                        <div class="d-flex justify-content-center mb-3">
                            <div class="avatar-circle rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px; font-size: 30px;">
                                <?= htmlspecialchars($avatarChar) ?>
                            </div>
                        </div>
                        <h5 class="text-white fw-bold mb-1"><?= htmlspecialchars($fullName) ?></h5>
                        <p class="text-white-50 small mb-0">Hạng: Thành Viên Mới</p>
                    </div>

                    <div class="card profile-card rounded overflow-hidden">
                        <div class="list-group list-group-flush list-group-custom" id="profileTabs">
                            <button class="list-group-item py-3 fw-bold active" onclick="switchTab('info', this)">
                                <i class="fas fa-user me-3"></i>Thông Tin Cá Nhân
                            </button>
                            <button class="list-group-item py-3 fw-bold" onclick="switchTab('orders', this)">
                                <i class="fas fa-shopping-bag me-3"></i>Lịch Sử Đơn Hàng
                            </button>
                            <button class="list-group-item py-3 fw-bold" onclick="switchTab('password', this)">
                                <i class="fas fa-lock me-3"></i>Đổi Mật Khẩu
                            </button>
                            <a href="/LTWNC_LTWNC_WEBTMDT/controllers/LogoutController.php" class="list-group-item py-3 fw-bold text-danger text-decoration-none" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
                                <i class="fas fa-sign-out-alt me-3"></i>Đăng Xuất
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 wow fadeInRight" data-wow-delay="0.2s">
                    <div class="card profile-card rounded p-4 h-100">

                        <div id="tabContent-info" class="tab-pane-custom">
                            <h4 class="text-white fw-bold border-bottom border-secondary pb-3 mb-4 text-orange">Hồ Sơ Của Tôi</h4>
                            
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success fw-bold"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger fw-bold"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                            <?php endif; ?>
                            <form id="infoForm" action="/LTWNC_LTWNC_WEBTMDT/controllers/UpdateProfileController.php" method="POST">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 fw-bold">Họ & Tên Đệm</label>
                                        <input type="text" name="ho_ten_dem" value="<?= htmlspecialchars($ho_ten_dem) ?>" class="form-control py-2" placeholder="Nhập họ và tên đệm">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 fw-bold">Tên</label>
                                        <input type="text" name="ten" value="<?= htmlspecialchars($ten) ?>" class="form-control py-2" placeholder="Nhập tên">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 fw-bold">Số Điện Thoại</label>
                                        <input type="text" name="so_dien_thoai" value="<?= htmlspecialchars($user['so_dien_thoai'] ?? '') ?>" class="form-control py-2" placeholder="Nhập số điện thoại">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white-50 fw-bold">Tên đăng nhập</label>
                                        <input type="text" value="<?= htmlspecialchars($user['ten_dang_nhap'] ?? '') ?>" class="form-control py-2" disabled>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label text-white-50 fw-bold">Địa Chỉ Giao Hàng</label>
                                        <textarea name="dia_chi" class="form-control py-2" rows="3" placeholder="Nhập số nhà, tên đường, quận/huyện..."><?= htmlspecialchars($user['dia_chi'] ?? '') ?></textarea>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-orange px-5 py-2 fw-bold rounded-pill">
                                            Lưu Thay Đổi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="tabContent-orders" class="tab-pane-custom" style="display: none;">
                            <h4 class="text-white fw-bold border-bottom border-secondary pb-3 mb-4 text-orange">Đơn Hàng Gần Đây</h4>
                            <p class="text-white-50">Tính năng đang cập nhật...</p>
                        </div>

                        <div id="tabContent-password" class="tab-pane-custom" style="display: none;">
                            <h4 class="text-white fw-bold border-bottom border-secondary pb-3 mb-4 text-orange">Đổi Mật Khẩu</h4>
                            <p class="text-white-50">Tính năng đang cập nhật...</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../components/Footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function switchTab(tabName, element) {
            document.querySelectorAll('.tab-pane-custom').forEach(pane => pane.style.display = 'none');
            document.querySelectorAll('#profileTabs button').forEach(btn => btn.classList.remove('active'));
            document.getElementById(`tabContent-${tabName}`).style.display = 'block';
            element.classList.add('active');
        }
    </script>
</body>
</html>