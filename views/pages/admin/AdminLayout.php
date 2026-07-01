<?php
// Đảm bảo session đã được bật để lấy thông tin Admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy giá trị 'act' từ URL, nếu không có thì mặc định là 'admin_dashboard'
$current_act = $_GET['act'] ?? 'admin_dashboard';

// Lấy thông tin admin từ session (nếu có)
$adminName = $_SESSION['user']['ten'] ?? 'Quản Trị Viên';

// Gom toàn bộ Menu vào Mảng để code ngắn gọn và dễ kiểm tra Active
$menuItems = [
    ['act' => 'admin_dashboard', 'icon' => 'fa-tachometer-alt', 'title' => 'Tổng Quan'],
    ['act' => 'ThongKeDoanhThu', 'icon' => 'fa-chart-line', 'title' => 'Thống Kê'],
    ['act' => 'QuanLyThuongHieu', 'icon' => 'fa-tag', 'title' => 'Quản Lý Thương Hiệu'],
    ['act' => 'QuanLyDanhMuc', 'icon' => 'fa-box', 'title' => 'Quản Lý Danh Mục'],
    ['act' => 'QuanLySanPham', 'icon' => 'fa-box', 'title' => 'Quản Lý Sản Phẩm'],
    ['act' => 'QuanLyDonHang', 'icon' => 'fa-clipboard-list', 'title' => 'Quản Lý Đơn Hàng'],
    ['act' => 'QuanLyKhachHang', 'icon' => 'fa-users', 'title' => 'Quản Lý Khách Hàng'],
    ['act' => 'QuanLyVoucher', 'icon' => 'fa-ticket-alt', 'title' => 'Quản Lý Voucher'],
    ['act' => 'QuanLyKhuyenMai', 'icon' => 'fa-ticket-alt', 'title' => 'Quản Lý Khuyến Mãi'],
    ['act' => 'QuanLyBinhLuan', 'icon' => 'fa-comments', 'title' => 'Quản Lý Bình Luận Và Đánh Giá']
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - TramHieuShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/LTWNC_LTWNC_WEBTMDT/assets/style.css">
    <style>
    body { background-color: #111; color: #fff; overflow-x: hidden; margin: 0; }
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #F28B00; }
    
    /* Chỉnh style cho Popup Modal của Bootstrap */
    .modal-content { background-color: #1a1a1a; border: 1px solid #444; border-radius: 15px; }
    .modal-header { border-bottom: 2px solid #F28B00; background-color: #222; }
    .modal-footer { border-top: 1px solid #333; background-color: #222; }
    
    /* Hiệu ứng hover menu */
    .nav-link { color: #e0e0e0 !important; transition: all 0.3s ease !important; }
    .nav-link:hover { color: #fff !important; background-color: #333 !important; border-radius: 8px; }

    /* Style mục ĐANG CHỌN (Active) */
    .nav-link.active-menu { background-color: #F28B00 !important; color: #000 !important; font-weight: bold !important; border-radius: 8px; }
</style>
</head>
<body>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" style="background-color: #1a1a1a; width: 280px; border-right: 1px solid #333;">
    <div class="offcanvas-header border-bottom border-secondary">
        <h4 class="offcanvas-title fw-bold m-0" style="color: #F28B00;"><i class="fas fa-user-shield me-2"></i>Admin</h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column p-3">
        <ul class="nav nav-pills flex-column mb-auto gap-2">
            <?php foreach ($menuItems as $item): ?>
                <li>
                    <a href="index.php?act=<?= $item['act'] ?>" 
                       class="nav-link d-flex align-items-center w-100 text-start border-0 <?= ($current_act === $item['act']) ? 'active-menu' : '' ?>">
                        <i class="fas <?= $item['icon'] ?> me-3" style="width: 20px;"></i> <?= $item['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <hr class="text-secondary" />
        <a href="index.php?act=logout" onclick="return confirm('Thoát khỏi trang Quản Trị?');" 
           class="btn btn-outline-danger fw-bold w-100 d-flex align-items-center justify-content-center text-decoration-none">
            <i class="fas fa-sign-out-alt me-2"></i> Đăng Xuất
        </a>
    </div>
</div>

<div class="d-flex flex-column flex-lg-row" style="min-height: 100vh; background-color: #111;">

    <div class="d-lg-none d-flex justify-content-between align-items-center p-3 shadow-sm w-100" style="background-color: #1a1a1a; border-bottom: 1px solid #333;">
    <h4 class="fw-bold m-0" style="color: #F28B00;"><i class="fas fa-user-shield me-2"></i>Admin</h4>
    <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
        <i class="fas fa-bars"></i>
    </button>
</div>

<div class="d-none d-lg-flex flex-column flex-shrink-0 p-3 shadow-lg" style="width: 260px; background-color: #1a1a1a; border-right: 1px solid #333; min-height: 100vh;">
    <a href="index.php?act=admin_dashboard" class="d-flex align-items-center mb-4 mt-2 text-decoration-none justify-content-center w-100">
        <h2 class="fw-bold m-0" style="color: #F28B00;">
            <i class="fas fa-user-shield me-2"></i>Admin
        </h2>
    </a>
    <hr class="text-secondary" />

    <ul class="nav nav-pills flex-column mb-auto gap-2">
        <?php foreach ($menuItems as $item): ?>
            <li>
                <a href="index.php?act=<?= $item['act'] ?>" 
                   class="nav-link d-flex align-items-center w-100 text-start border-0 <?= ($current_act === $item['act']) ? 'active-menu' : '' ?>">
                    <i class="fas <?= $item['icon'] ?> me-3" style="width: 20px;"></i> <?= $item['title'] ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <hr class="text-secondary" />
    <div class="mt-auto position-sticky bottom-0 py-3" style="background:#1a1a1a;">
        <hr class="text-secondary">
        <a href="index.php?act=logout"
        onclick="return confirm('Thoát khỏi trang Quản Trị?');"
        class="btn btn-outline-danger fw-bold w-100 d-flex align-items-center justify-content-center text-decoration-none">
            <i class="fas fa-sign-out-alt me-2"></i>
            Đăng Xuất
        </a>
    </div>
</div>

<div class="flex-grow-1 d-flex flex-column" style="width: 100%; max-width: 100%; min-width: 0; overflow-x: hidden;">

    <div class="p-3 p-md-4 border-bottom border-secondary d-flex justify-content-between align-items-center gap-2" style="background-color: #111;">
        <h5 class="text-white fw-bold mb-0 text-truncate">Quản Trị TramHieu</h5>
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-dark border-secondary position-relative d-none d-sm-block">
                <i class="fas fa-bell text-warning"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
            </button>
            <div class="d-flex align-items-center gap-2">
                <img src="[https://ui-avatars.com/api/?name=](https://ui-avatars.com/api/?name=)<?= urlencode($adminName) ?>&background=F28B00&color=fff" alt="Admin" class="rounded-circle" width="35" height="35" />
                <span class="text-white fw-bold d-none d-sm-inline"><?= htmlspecialchars($adminName) ?></span>
            </div>
        </div>
    </div>

    <div class="p-3 p-md-4 flex-grow-1 w-100" style="background-color: #111; overflow-x: hidden;">
        <?php 
        if (isset($PAGE_CONTENT)) {
            echo $PAGE_CONTENT;
        } 
        ?>
    </div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>