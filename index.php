<?php 
/** * @var array $products 
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($products)) { $products = []; }
if (!isset($brands)) { $brands = []; }

$IMAGE_BASE_URL = '/LTWNC_LTWNC_WEBTMDT/assets/images/products/';

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - LuLoShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/LTWNC_LTWNC_WEBTMDT/assets/style.css">
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
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-6px);
            border-color: #F28B00;
            box-shadow: 0 8px 20px rgba(242, 139, 0, 0.25);
        }

        /* Khung ảnh */
        .product-img-wrapper {
            position: relative;
            height: 280px;
            background: #fff;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Ảnh sản phẩm */
        .product-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transform: scale(1.0);
        }

        .product-card:hover .product-img {
            transform: scale(1.05);
        }

        /* Tên sản phẩm */
        .product-title {
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.5;

            min-height: 48px;

            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;

            overflow: hidden;
        }

        /* Giá */
        .price-text {
            color: #F28B00;
            font-size: 1.7rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        /* Nút */
        .btn-cart {
            border: 2px solid #F28B00;
            color: #F28B00;
            border-radius: 999px;
            font-weight: 600;
            transition: all .3s ease;
        }

        .btn-cart:hover {
            background: #F28B00;
            color: #fff;
        }

        /* Badge giảm giá */
        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            background: #dc3545;
            color: white;
            font-size: 0.75rem;
            padding: 6px 10px;
            border-radius: 20px;
        }
       .brand-section{
    margin: 40px 0;
}

.brand-slider{
    overflow: hidden;
    position: relative;
    height: 120px;
    background: linear-gradient(
        90deg,
        #111 0%,
        #1a1a1a 50%,
        #111 100%
    );
    border-top: 1px solid #333;
    border-bottom: 1px solid #333;

    display: flex;
    align-items: center;
}

.brand-track{
    display: flex;
    align-items: center;
    gap: 80px;
    width: max-content;

    animation: scrollBrand 25s linear infinite;
}

.brand-track img{
    height: 70px;
    width: auto;

    object-fit: contain;

    filter: brightness(0) invert(1) opacity(0.7);
    transition: all .35s ease;
}

.brand-track img:hover{
    filter: brightness(0) invert(1);
    opacity: 1;
    transform: scale(1.15);
}

.brand-slider:hover .brand-track{
    animation-play-state: paused;
}

@keyframes scrollBrand{
    from{
        transform: translateX(0);
    }
    to{
        transform: translateX(-50%);
    }
}

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
    <?php
    include "views/components/header.php"
    ?>

    <?php
    include 'views/components/HeroSlider.php';
    ?>

    <?php
        include 'views/components/ServiceFeatures.php';
    ?>
    <?php
        include 'views/components/FeaturedProducts.php';
    ?>


    <div class="container-fluid home-section py-5">
        <div class="container py-5">
            
            <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                <h1 class="fw-bold text-uppercase section-title">Sản Phẩm Nổi Bật</h1>
                <p class="text-white-50 mt-3">Khám phá ngay những thiết bị công nghệ và gaming gear đỉnh cao đang có giá cực hời.</p>
            </div>
            
            <div class="row g-4">
                <?php 
                    // Xử lý giới hạn 4 sản phẩm
                    $limitedProducts = array_slice($products, 0, 4);
                    
                    foreach ($limitedProducts as $product): 
                        // Nhúng ProductCard, mỗi lần chạy biến $product sẽ có sẵn trong file đó
                        include 'views/components/ProductCard.php';
                    endforeach; 
                ?>
            </div>

        </div>
    </div>
    <div class="brand-section">
        <div class="brand-slider">
            <div class="brand-track">

                <?php foreach($brands as $brand): ?>
                    <img
                        src="/LTWNC_LTWNC_WEBTMDT/assets/images/brands/<?= htmlspecialchars($brand['hinh_anh_logo']) ?>"
                        alt="<?= htmlspecialchars($brand['ten_thuong_hieu']) ?>"
                        title="<?= htmlspecialchars($brand['ten_thuong_hieu']) ?>"
                    >
                <?php endforeach; ?>

                <!-- lặp lại để chạy vô hạn -->
                <?php foreach($brands as $brand): ?>
                    <img
                        src="/LTWNC_LTWNC_WEBTMDT/assets/images/brands/<?= htmlspecialchars($brand['hinh_anh_logo']) ?>"
                        alt="<?= htmlspecialchars($brand['ten_thuong_hieu']) ?>"
                        title="<?= htmlspecialchars($brand['ten_thuong_hieu']) ?>"
                    >
                <?php endforeach; ?>

            </div>
        </div>
    </div>
    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-end border-bottom border-secondary pb-2 mb-4">
            <h3 class="fw-bold m-0 text-white border-start border-4 ps-3" style="border-color: #F28B00 !important;">SẢN PHẨM NỔI BẬT</h3>
            <a href="#" class="text-decoration-none" style="color: #F28B00;">Xem tất cả <i class="fas fa-chevron-right ms-1"></i></a>
        </div>

        <div class="row g-4">   
            <?php foreach ($products as $item): ?>
                <?php include 'views/components/product-card.php';?>
            <?php endforeach; ?>
        </div>
    </div>

<?php
    include 'views/components/footer.php'
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
