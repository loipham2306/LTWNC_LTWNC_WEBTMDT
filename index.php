<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - LuLoShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #111; color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Navbar */
        .navbar-custom { background-color: #1a1a1a !important; border-bottom: 2px solid #F28B00; }
        .search-bar { background-color: #222; border: 1px solid #444; color: #fff; }
        .search-bar:focus { background-color: #333; border-color: #F28B00; color: #fff; box-shadow: none; }
        
        /* Banner */
        .hero-banner {
            background: linear-gradient(45deg, #111 0%, #2a1800 100%);
            border: 1px solid #333;
            border-radius: 15px;
            overflow: hidden;
        }

        /* Product Card */
        .product-card {
            background-color: #1a1a1a;
            border: 1px solid #333;
            border-radius: 10px;
            transition: transform 0.3s, border-color 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            border-color: #F28B00;
        }
        .product-img-wrapper {
            position: relative;
            padding-top: 100%; /* Tạo khung vuông cho ảnh */
            overflow: hidden;
            border-radius: 10px 10px 0 0;
            background-color: #222;
        }
        .product-img {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover;
        }
        .price-text { color: #F28B00; font-weight: bold; font-size: 1.2rem; }
        
        /* Buttons */
        .btn-custom { background-color: #F28B00; color: #fff; font-weight: bold; }
        .btn-custom:hover { background-color: #d67a00; color: #fff; }
        .btn-outline-custom { border-color: #F28B00; color: #F28B00; }
        .btn-outline-custom:hover { background-color: #F28B00; color: #fff; }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #F28B00; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="index.php" style="color: #F28B00;">
            <i class="fas fa-shopping-bag me-2"></i>LuLoShop
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="d-flex mx-auto my-2 my-lg-0" style="max-width: 500px; width: 100%;">
                <div class="input-group">
                    <input type="text" class="form-control search-bar" placeholder="Tìm kiếm sản phẩm, phụ kiện...">
                    <button class="btn btn-custom" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>

            <ul class="navbar-nav ms-auto align-items-center gap-3">
                <li class="nav-item">
                    <a class="nav-link text-white position-relative" href="#">
                        <i class="fas fa-shopping-cart fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                            3
                        </span>
                    </a>
                </li>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-bold d-flex align-items-center gap-2" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['ten']) ?>&background=F28B00&color=fff" class="rounded-circle" width="30" height="30">
                            <?= $_SESSION['user']['ten'] ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow" style="background-color: #1a1a1a; border: 1px solid #333;">
                            <?php if ($_SESSION['user']['vai_tro'] === 'admin'): ?>
                                <li><a class="dropdown-item" href="views/admin/dashboard.php"><i class="fas fa-tachometer-alt me-2 text-warning"></i>Vào trang Admin</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2 text-info"></i>Hồ sơ cá nhân</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-box me-2 text-success"></i>Đơn hàng của tôi</a></li>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li><a class="dropdown-item text-danger fw-bold" href="#"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-custom rounded-pill px-4" href="views/login.php">Đăng Nhập</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="hero-banner p-5 text-center text-md-start d-flex align-items-center" style="min-height: 300px;">
        <div class="row w-100 align-items-center">
            <div class="col-md-7 px-4">
                <span class="badge bg-warning text-dark mb-2 px-3 py-2 fs-6 rounded-pill">🔥 SIÊU SALE MÙA HÈ</span>
                <h1 class="fw-bold text-white mb-3" style="font-size: 3rem;">NÂNG CẤP TRANG BỊ <br><span style="color: #F28B00;">CHINH PHỤC ĐỈNH CAO</span></h1>
                <p class="text-muted fs-5 mb-4">Giảm giá lên đến 50% cho tất cả các thiết bị điện tử, laptop và phụ kiện công nghệ. Nhập mã <strong>LULO50</strong> để nhận ưu đãi.</p>
                <button class="btn btn-custom btn-lg rounded-pill px-5">MUA NGAY</button>
            </div>
            <div class="col-md-5 d-none d-md-block text-center">
                <i class="fas fa-gamepad" style="font-size: 10rem; color: rgba(242, 139, 0, 0.2);"></i>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-end border-bottom border-secondary pb-2 mb-4">
        <h3 class="fw-bold m-0 text-white border-start border-4 ps-3" style="border-color: #F28B00 !important;">SẢN PHẨM NỔI BẬT</h3>
        <a href="#" class="text-decoration-none" style="color: #F28B00;">Xem tất cả <i class="fas fa-chevron-right ms-1"></i></a>
    </div>

    <div class="row g-4">
        <?php 
        // Dữ liệu sản phẩm giả lập
        $products = [
            ['id' => 1, 'name' => 'Bàn phím cơ Razer BlackWidow V3', 'price' => '3.350.000 đ', 'old_price' => '4.000.000 đ', 'img' => 'https://placehold.co/400x400/1a1a1a/F28B00?text=Keyboard', 'badge' => '-15%'],
            ['id' => 2, 'name' => 'Chuột Gaming Logitech G502 Hero', 'price' => '1.250.000 đ', 'old_price' => '', 'img' => 'https://placehold.co/400x400/1a1a1a/F28B00?text=Mouse', 'badge' => 'New'],
            ['id' => 3, 'name' => 'Laptop Gaming ASUS ROG Strix G15', 'price' => '25.990.000 đ', 'old_price' => '28.000.000 đ', 'img' => 'https://placehold.co/400x400/1a1a1a/F28B00?text=Laptop', 'badge' => 'Hot'],
            ['id' => 4, 'name' => 'Tai nghe HyperX Cloud II Red', 'price' => '1.890.000 đ', 'old_price' => '', 'img' => 'https://placehold.co/400x400/1a1a1a/F28B00?text=Headset', 'badge' => ''],
            ['id' => 5, 'name' => 'Màn hình LG UltraGear 27inch 144Hz', 'price' => '6.500.000 đ', 'old_price' => '7.200.000 đ', 'img' => 'https://placehold.co/400x400/1a1a1a/F28B00?text=Monitor', 'badge' => ''],
            ['id' => 6, 'name' => 'Áo thun eSports Team Flash', 'price' => '350.000 đ', 'old_price' => '', 'img' => 'https://placehold.co/400x400/1a1a1a/F28B00?text=T-Shirt', 'badge' => 'Limited'],
            ['id' => 7, 'name' => 'Lót chuột RGB Corsair MM800', 'price' => '1.100.000 đ', 'old_price' => '', 'img' => 'https://placehold.co/400x400/1a1a1a/F28B00?text=Mousepad', 'badge' => ''],
            ['id' => 8, 'name' => 'Microphone HyperX QuadCast', 'price' => '3.490.000 đ', 'old_price' => '3.990.000 đ', 'img' => 'https://placehold.co/400x400/1a1a1a/F28B00?text=Microphone', 'badge' => 'Sale'],
        ];

        foreach ($products as $item): 
        ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="product-card h-100 d-flex flex-column">
                <div class="product-img-wrapper">
                    <?php if ($item['badge']): ?>
                        <span class="position-absolute top-0 start-0 m-2 badge bg-danger z-1"><?= $item['badge'] ?></span>
                    <?php endif; ?>
                    <img src="<?= $item['img'] ?>" class="product-img" alt="<?= $item['name'] ?>">
                </div>
                <div class="p-3 d-flex flex-column flex-grow-1">
                    <h6 class="text-white mb-2 text-truncate" style="font-size: 0.95rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; white-space: normal;"><?= $item['name'] ?></h6>
                    
                    <div class="mt-auto">
                        <?php if ($item['old_price']): ?>
                            <small class="text-muted text-decoration-line-through d-block"><?= $item['old_price'] ?></small>
                        <?php endif; ?>
                        <div class="price-text mb-3"><?= $item['price'] ?></div>
                        <button class="btn btn-outline-custom w-100 rounded-pill py-1 fw-bold">
                            <i class="fas fa-cart-plus me-1"></i> Thêm Vào Giỏ
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<footer class="mt-5 border-top border-secondary pt-5 pb-4" style="background-color: #1a1a1a;">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h4 class="fw-bold mb-3" style="color: #F28B00;"><i class="fas fa-shopping-bag me-2"></i>LuLoShop</h4>
                <p class="text-muted small">Hệ thống phân phối thiết bị điện tử, phụ kiện công nghệ và gaming gear hàng đầu. Chất lượng tạo nên thương hiệu.</p>
            </div>
            <div class="col-md-4">
                <h5 class="text-white fw-bold mb-3">Liên Hệ</h5>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Quận Ninh Kiều, TP. Cần Thơ</li>
                    <li class="mb-2"><i class="fas fa-phone me-2"></i> 0123 456 789</li>
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i> support@luloshop.vn</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="text-white fw-bold mb-3">Kết Nối Với Chúng Tôi</h5>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-dark border-secondary rounded-circle"><i class="fab fa-facebook-f text-white"></i></a>
                    <a href="#" class="btn btn-dark border-secondary rounded-circle"><i class="fab fa-youtube text-white"></i></a>
                    <a href="#" class="btn btn-dark border-secondary rounded-circle"><i class="fab fa-instagram text-white"></i></a>
                </div>
            </div>
        </div>
        <div class="text-center text-muted small mt-4 pt-3 border-top border-secondary">
            &copy; 2026 LuLoShop. All rights reserved.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>