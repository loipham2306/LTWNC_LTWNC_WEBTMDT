<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$BASE_URL = '/LTWNC_LTWNC_WEBTMDT/';

// Lấy chính xác tên file hiện tại đang chạy (VD: index.php, Shop.php...)
$currentPage = basename($_SERVER['PHP_SELF']);

// Xử lý logic làm sáng Menu - Phân biệt rõ chữ hoa/chữ thường
$isHomeActive     = ($currentPage == 'index.php' || $currentPage == '') ? 'active' : '';
$isShopActive     = ($currentPage == 'Shop.php' || $currentPage == 'ProductDetail.php') ? 'active' : '';
$isCartActive     = ($currentPage == 'Cart.php') ? 'active' : '';
$isCheckoutActive = ($currentPage == 'Checkout.php') ? 'active' : '';
$isContactActive  = ($currentPage == 'Contact.php') ? 'active' : '';
?>

<style>
    /* CSS tùy chỉnh cho Header */
    .top-bar-bg { background-color: #111; border-bottom: 1px solid #333; }
    
    /* Thanh tìm kiếm */
    .search-input { background-color: #222 !important; border: 1px solid #444 !important; color: #fff !important; }
    .search-input:focus { border-color: #F28B00 !important; box-shadow: none !important; }
    
    /* Nút và Icon màu Cam */
    .btn-orange { background-color: #F28B00 !important; color: #fff !important; border: none; }
    .btn-orange:hover { background-color: #d67a00 !important; }
    .icon-circle { border: 1px solid #444 !important; color: #F28B00 !important; transition: 0.3s; }
    .icon-circle:hover { background-color: #F28B00 !important; color: #fff !important; border-color: #F28B00 !important; }
    
    /* Thanh Menu dưới (Màu Cam chủ đạo) */
    .nav-bar-bg { background-color: #F28B00 !important; }
    
    /* Trạng thái bình thường: Chữ trắng hơi mờ đi (độ mờ 65%) */
    .nav-bar-bg .nav-link { 
        color: rgba(255, 255, 255, 0.65) !important; 
        font-weight: 600; 
        padding: 20px 15px; 
        transition: 0.3s; 
    }
    
    /* Trạng thái Active hoặc Hover: Chữ sáng trắng 100%, không có nền */
    .nav-bar-bg .nav-link:hover, .nav-bar-bg .nav-link.active { 
        color: #ffffff !important; 
        background-color: transparent !important; 
    }
</style>

<div class="container-fluid px-5 py-4 d-none d-lg-block top-bar-bg">
    <div class="row gx-0 align-items-center text-center">
        <div class="col-md-4 col-lg-3 text-center text-lg-start">
            <div class="d-inline-flex align-items-center">
                <a href="<?= $BASE_URL ?>index.php" class="navbar-brand p-0 text-decoration-none">
                    <h1 class="display-5 m-0" style="color: #F28B00;">
                        <img src="<?= $BASE_URL ?>assets/images/img/th.png" alt="Logo" class="h-100px" style="height: 60px; object-fit: contain;" onerror="this.style.display='none';"> 
                    </h1>
                </a>
            </div>
        </div>
        
        <div class="col-md-4 col-lg-6 text-center">
            <div class="position-relative ps-4">
                <div class="d-flex rounded-pill">
                    <input class="form-control rounded-pill w-100 py-3 search-input" type="text" placeholder="Bạn đang tìm sản phẩm gì?">
                    <button type="button" class="btn btn-orange rounded-pill py-3 px-5 ms-n5" style="margin-left: -60px; z-index: 10;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-lg-3 text-center text-lg-end">
            <div class="d-inline-flex align-items-center gap-3 justify-content-end w-100">
                
                <a href="#" class="text-decoration-none">
                    <span class="rounded-circle btn-md-square d-flex align-items-center justify-content-center icon-circle" style="width: 45px; height: 45px;">
                        <i class="fas fa-heart"></i>
                    </span>
                </a>

                <a href="<?= $BASE_URL ?>views/pages/Cart.php" class="text-decoration-none position-relative">
                    <span class="rounded-circle btn-md-square d-flex align-items-center justify-content-center icon-circle" style="width: 45px; height: 45px;">
                        <i class="fas fa-shopping-cart"></i>
                    </span>
                    <span id="headerCartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-dark" style="font-size: 0.65rem; display: none;">0</span>
                </a>

                <a href="<?= $BASE_URL ?>views/pages/UserProfile.php" class="btn btn-orange rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                    <i class="fas fa-user text-white"></i>
                </a>

            </div>
        </div>
    </div>
</div>

<div class="container-fluid nav-bar p-0 mb-0">
    <div class="row gx-0 nav-bar-bg px-5 align-items-center mb-0">
        <div class="col-12 col-lg-12">
            <nav class="navbar navbar-expand-lg navbar-dark bg-transparent py-0">
                <button class="navbar-toggler ms-auto my-2 border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars fa-1x text-white"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav py-0">
                        <a href="<?= $BASE_URL ?>index.php" class="nav-item nav-link <?= $isHomeActive ?>">Trang Chủ</a>
                        <a href="<?= $BASE_URL ?>views/pages/Shop.php" class="nav-item nav-link <?= $isShopActive ?>">Cửa hàng</a>
                        <a href="<?= $BASE_URL ?>views/pages/Cart.php" class="nav-item nav-link <?= $isCartActive ?>">Giỏ Hàng</a>
                        <a href="<?= $BASE_URL ?>views/pages/Checkout.php" class="nav-item nav-link <?= $isCheckoutActive ?>">Thanh Toán</a>
                        <a href="<?= $BASE_URL ?>views/pages/Contact.php" class="nav-item nav-link me-2 <?= $isContactActive ?>">Liên Hệ</a>
                    </div>

                    <div class="ms-auto d-none d-lg-block">
                        <a href="tel:0123456789" class="btn rounded-pill py-2 px-4 fw-bold shadow-sm" style="background-color: #111; color: #F28B00;">
                            <i class="fa fa-mobile-alt me-2"></i> 0123 456 789
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>

<script>
    // Hàm này sẽ tự động đọc dữ liệu từ LocalStorage và cập nhật lên icon giỏ hàng
    function updateCartBadge() {
        let currentCart = JSON.parse(localStorage.getItem('cart')) || [];
        let totalItems = currentCart.reduce((sum, item) => sum + item.quantity, 0);
        let badge = document.getElementById('headerCartBadge');
        
        if (badge) {
            badge.innerText = totalItems;
            // Nếu giỏ hàng trống thì ẩn cục màu đỏ đi cho đẹp, có hàng mới hiện lên
            if (totalItems > 0) {
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    // Chạy hàm ngay khi load xong Header để hiển thị số lượng mới nhất
    document.addEventListener('DOMContentLoaded', updateCartBadge);
</script>