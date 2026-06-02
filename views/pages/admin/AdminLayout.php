<?php
// Đảm bảo session đã được bật để lấy thông tin Admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy tên file hiện tại (ví dụ: đang ở trang 'dashboard.php' thì biến này sẽ là 'dashboard.php')
// Tương đương với hook useLocation() trong React
$current_page = basename($_SERVER['PHP_SELF']);

// Hàm phụ trợ kiểm tra menu nào đang active
function isActive($path, $current_page) {
    return ($current_page === $path);
}

// Lấy thông tin admin từ session (nếu có)
$adminName = isset($_SESSION['user']['ten']) ? $_SESSION['user']['ten'] : 'Quản Trị Viên';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Trị - LuLoShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #111; color: #fff; overflow-x: hidden; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #F28B00; }
        
        /* Chỉnh style cho Popup Modal của Bootstrap */
        .modal-content { background-color: #1a1a1a; border: 1px solid #444; border-radius: 15px; }
        .modal-header { border-bottom: 2px solid #F28B00; background-color: #222; }
        .modal-footer { border-top: 1px solid #333; background-color: #222; }
    </style>
</head>
<body>

<div class="d-flex" style="min-height: 100vh; background-color: #111;">

    <div class="d-flex flex-column flex-shrink-0 p-3 shadow-lg" style="width: 260px; background-color: #1a1a1a; border-right: 1px solid #333;">
        <a href="dashboard.php" class="d-flex align-items-center mb-4 mb-md-0 me-md-auto text-decoration-none justify-content-center w-100 mt-2">
            <h2 class="fw-bold m-0" style="color: #F28B00;">
                <i class="fas fa-user-shield me-2"></i>Admin
            </h2>
        </a>
        <hr class="text-secondary" />

        <ul class="nav nav-pills flex-column mb-auto gap-2">
            <li class="nav-item">
                <a href="Dashboard.php" 
                   class="nav-link fw-bold d-flex align-items-center w-100 text-start border-0 <?= isActive('Dashboard.php', $current_page) ? 'text-white' : 'text-muted' ?>"
                   style="background-color: <?= isActive('Dashboard.php', $current_page) ? '#F28B00' : 'transparent' ?>; transition: all 0.3s;">
                    <i class="fas fa-tachometer-alt me-3" style="width: 20px;"></i> Tổng Quan
                </a>
            </li>
            <li>
                <a href="CategoryMaganement.php" 
                   class="nav-link fw-bold d-flex align-items-center w-100 text-start border-0 <?= isActive('CashboarManagement.php', $current_page) ? 'text-white' : 'text-muted' ?>"
                   style="background-color: <?= isActive('CategoryManagement.php', $current_page) ? '#F28B00' : 'transparent' ?>; transition: all 0.3s;">
                    <i class="fas fa-box me-3" style="width: 20px;"></i> Quản Lý Danh Mục
                </a>
            </li>
            <li>
                <a href="ProductAdmin.php" 
                   class="nav-link fw-bold d-flex align-items-center w-100 text-start border-0 <?= isActive('ProductAdmin.php', $current_page) ? 'text-white' : 'text-muted' ?>"
                   style="background-color: <?= isActive('ProductAdmin.php', $current_page) ? '#F28B00' : 'transparent' ?>; transition: all 0.3s;">
                    <i class="fas fa-box me-3" style="width: 20px;"></i> Quản Lý Sản Phẩm
                </a>
            </li>
            <li>
                <a href="BrandManagementPage.php" 
                   class="nav-link fw-bold d-flex align-items-center w-100 text-start border-0 py-2 px-3 <?= isActive('BrandManagementPage.php', $current_page) ? 'text-white' : 'text-muted' ?>"
                   style="background-color: <?= isActive('BrandManagementPage.php', $current_page) ? '#F28B00' : 'transparent' ?>; border-radius: 8px; transition: all 0.3s;">
                    <i class="fas fa-tag me-3" style="width: 20px; font-size: 18px;"></i> Quản Lý Thương Hiệu
                </a>
            </li>
            <li>
                <a href="OrderAdmin.php" 
                   class="nav-link fw-bold d-flex align-items-center w-100 text-start border-0 <?= isActive('OrderAdmin.php', $current_page) ? 'text-white' : 'text-muted' ?>"
                   style="background-color: <?= isActive('OrderAdmin.php', $current_page) ? '#F28B00' : 'transparent' ?>; transition: all 0.3s;">
                    <i class="fas fa-clipboard-list me-3" style="width: 20px;"></i> Quản Lý Đơn Hàng
                </a>
            </li>
            <li>
                <a href="CustomerAdmin.php" 
                   class="nav-link fw-bold d-flex align-items-center w-100 text-start border-0 <?= isActive('CustomerAdmin.php', $current_page) ? 'text-white' : 'text-muted' ?>"
                   style="background-color: <?= isActive('CustomerAdmin.php', $current_page) ? '#F28B00' : 'transparent' ?>; transition: all 0.3s;">
                    <i class="fas fa-users me-3" style="width: 20px;"></i> Quản Lý Khách Hàng
                </a>
            </li>
            <li>
                <a href="VoucherAdmin.php" 
                   class="nav-link fw-bold d-flex align-items-center w-100 text-start border-0 <?= isActive('VoucherAdmin.php', $current_page) ? 'text-white' : 'text-muted' ?>"
                   style="background-color: <?= isActive('VoucherAdmin.php', $current_page) ? '#F28B00' : 'transparent' ?>; transition: all 0.3s;">
                    <i class="fas fa-ticket-alt me-3" style="width: 20px;"></i> Quản Lý Voucher
                </a>
            </li>
        </ul>

        <hr class="text-secondary" />
        <a href="../../../controllers/DangXuatController.php" 
           onclick="return confirm('Thoát khỏi trang Quản Trị?');" 
           class="btn btn-outline-danger fw-bold w-100 d-flex align-items-center justify-content-center text-decoration-none">
            <i class="fas fa-sign-out-alt me-2"></i> Đăng Xuất
        </a>
    </div>

    <div class="flex-grow-1 d-flex flex-column" style="overflow-y: auto; max-height: 100vh;">

        <div class="p-4 border-bottom border-secondary d-flex justify-content-between align-items-center" style="background-color: #111;">
            <h4 class="text-white fw-bold mb-0">Hệ Thống Quản Trị LuLoShop</h4>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-dark border-secondary position-relative">
                    <i class="fas fa-bell text-warning"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                </button>
                <div class="d-flex align-items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($adminName) ?>&background=F28B00&color=fff" alt="Admin" class="rounded-circle" width="40" height="40" />
                    <span class="text-white fw-bold"><?= htmlspecialchars($adminName) ?></span>
                </div>
            </div>
        </div>

        <div class="p-4 flex-grow-1" style="background-color: #111;">
            
            <?php 
            // Nếu biến $PAGE_CONTENT có dữ liệu từ các trang con truyền vào thì in ra đây
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