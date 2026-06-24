<?php


$BASE_URL_IMAGE = '/LTWNC_LTWNC_WEBTMDT/';
$BASE_URL = '/LTWNC_LTWNC_WEBTMDT/controllers/';
// Lấy chính xác tên file hiện tại đang chạy (VD: index.php, Shop.php...)
$act = $_GET['act'] ?? '';
$currentPage = basename($_SERVER['PHP_SELF']);

// Xử lý logic làm sáng Menu - Phân biệt rõ chữ hoa/chữ thường
$isHomeActive     = ($currentPage == 'index.php' && $act == '') ? 'active' : '';
$isShopActive     = ($act == 'Shop' || $currentPage == 'ProductDetail.php') ? 'active' : '';
$isCartActive     = ($act == 'GioHang' || $currentPage == 'Cart.php') ? 'active' : '';
$isCheckoutActive = ($act == 'ThanhToan' ||$currentPage == 'Checkout.php') ? 'active' : '';
$isContactActive  = ($act == 'LienHe' ||$currentPage == 'Contact.php') ? 'active' : '';
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
                <a href="index.php" class="navbar-brand p-0 text-decoration-none">
                    <h1 class="display-5 m-0" style="color: #F28B00;">
                        <img src="<?= $BASE_URL_IMAGE ?>assets/images/img/th.png" alt="Logo" class="h-100px" style="height: 60px; object-fit: contain;" onerror="this.style.display='none';"> 
                    </h1>
                </a>
            </div>
        </div>
        
        <div class="col-md-4 col-lg-6 text-center">
            <div class="position-relative ps-4">
                <form action="index.php" method="GET" class="d-flex rounded-pill m-0">
                    <input type="hidden" name="act" value="Shop">
                    
                    <input class="form-control rounded-pill w-100 py-3 search-input" type="text" name="keyword" 
                           value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>" 
                           placeholder="Bạn đang tìm sản phẩm gì?">
                           
                    <button type="submit" class="btn btn-orange rounded-pill py-3 px-5 ms-n5" style="margin-left: -60px; z-index: 10;">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="col-md-4 col-lg-3 text-center text-lg-end">
            <div class="d-inline-flex align-items-center gap-3 justify-content-end w-100">
                
                <a href="#" class="text-decoration-none">
                    <span class="rounded-circle btn-md-square d-flex align-items-center justify-content-center icon-circle" style="width: 45px; height: 45px;">
                        <i class="fas fa-heart"></i>
                    </span>
                </a>

                <a href="index.php?act=GioHang" class="text-decoration-none position-relative">
                    <span class="rounded-circle btn-md-square d-flex align-items-center justify-content-center icon-circle" style="width: 45px; height: 45px;">
                        <i class="fas fa-shopping-cart"></i>
                    </span>
                    
                    <?php
                    // Tính tổng số lượng từ session cart
                    $totalQty = 0;
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $item) {
                            $totalQty += (int)($item['so_luong'] ?? 1);
                        }
                    }
                    ?>
                    <?php if ($totalQty > 0): ?>
                        <span id="cart-badge"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-dark"
                            style="font-size: 0.65rem;">
                            <?= $totalQty ?>
                        </span>
                    <?php endif; ?>
                </a>
                <?php
                    // Kiểm tra trạng thái đăng nhập (đảm bảo session_start() đã được gọi ở đầu file)
                    if (isset($_SESSION['user']) && !empty($_SESSION['user'])): ?>
                        <a href="index.php?act=UserProfile" class="btn btn-orange rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;" title="Trang cá nhân">
                            <i class="fas fa-user text-white"></i>
                        </a>
                    <?php else: ?>
                        <a href="index.php?act=Login" class="btn btn-warning rounded-pill px-3 py-2 fw-bold text-white shadow-sm" style="height: 45px; line-height: 30px;">
                            Đăng nhập
                        </a>
                <?php endif; ?>

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
                        <a href="index.php?act=Shop" class="nav-item nav-link <?= $isShopActive ?>">Cửa hàng</a>
                        <a href="index.php?act=GioHang" class="nav-item nav-link <?= $isCartActive ?>">Giỏ Hàng</a>
                        <a href="index.php?act=ThanhToan" class="nav-item nav-link <?= $isCheckoutActive ?>">Thanh Toán</a>
                        <a href="index.php?act=LienHe" class="nav-item nav-link me-2 <?= $isContactActive ?>">Liên Hệ</a>
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
<div id="mini-cart-drawer" style="position: fixed; top: 0; right: -400px; width: 350px; height: 100%; background: #1a1a1a; z-index: 9999; transition: 0.4s; padding: 20px; box-shadow: -5px 0 15px rgba(0,0,0,0.5); border-left: 2px solid #F28B00;">
    <div class="d-flex justify-content-between align-items-center mb-4 text-white">
        <h5>Giỏ Hàng Của Bạn</h5>
        <button onclick="closeMiniCart()" class="btn btn-link text-white"><i class="fas fa-times"></i></button>
    </div>
    <div id="mini-cart-items" style="max-height: 70vh; overflow-y: auto;">
        </div>
    <div class="mt-3">
        <a href="index.php?act=GioHang" class="btn btn-orange w-100">Xem Giỏ Hàng Chi Tiết</a>
    </div>
</div>
<script>
function openMiniCart() {
    document.getElementById('mini-cart-drawer').style.right = '0';
}

function closeMiniCart() {
    document.getElementById('mini-cart-drawer').style.right = '-400px';
}

function updateMiniCartUI() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let container = document.getElementById('mini-cart-items');
    container.innerHTML = ''; // Xóa cũ

    cart.forEach(item => {
        container.innerHTML += `
            <div class="d-flex align-items-center mb-3 text-white border-bottom border-secondary pb-2">
                <img src="${item.img}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                <div>
                    <div class="fw-bold" style="font-size: 0.9rem;">${item.name}</div>
                    <small>Size: ${item.size} | Màu: ${item.color}</small>
                    <div class="text-orange">${item.quantity} x ${item.price.toLocaleString()}đ</div>
                </div>
            </div>
        `;
    });
}

</script>