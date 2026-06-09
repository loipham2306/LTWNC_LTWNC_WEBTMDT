<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuLoShop - Cửa Hàng Gaming & Công Nghệ</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">

    <style>
        /* Tông nền tối eSports cho toàn bộ Trang Chủ */
        body { background-color: #111; color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        .home-section { background-color: #111; }
        .text-orange { color: #F28B00 !important; }
        
        /* Hiệu ứng gạch chân tiêu đề màu cam độc quyền */
        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 3px;
            background-color: #F28B00;
            margin: 15px auto 0;
        }
    </style>
</head>
<body>

    <?php
    // 1. MẢNG DỮ LIỆU SẢN PHẨM (Thay thế cho state products trong React)
    // Đã thêm /images/ trước /img/ theo đúng cấu trúc thư mục mới của bạn
    $products = [
        [ 'id' => 1, 'name' => 'Smart Camera Pro Max', 'category' => 'Điện Tử', 'price' => 3350000, 'oldPrice' => 4110000, 'img' => '/LTWNC_BAN_HANG/assets/images/img/product-4.png' ],
        [ 'id' => 2, 'name' => 'Apple iPad Mini G2356', 'category' => 'Tablet', 'price' => 10500000, 'oldPrice' => 12500000, 'img' => '/LTWNC_BAN_HANG/assets/images/img/product-3.png' ],
        [ 'id' => 3, 'name' => 'Microphone Đa Hướng', 'category' => 'Phụ Kiện', 'price' => 850000, 'oldPrice' => 1000000, 'img' => '/LTWNC_BAN_HANG/assets/images/img/product-5.png' ],
        [ 'id' => 4, 'name' => 'Tai nghe Bluetooth 5.0', 'category' => 'Âm Thanh', 'price' => 1250000, 'oldPrice' => 1500000, 'img' => '/LTWNC_BAN_HANG/assets/images/img/product-6.png' ]
    ];

    // 2. NHÚNG COMPONENT HEADER (THANH MENU)
    include 'views/components/Header.php';

    // 3. NHÚNG BANNER SLIDER CHÍNH
    include 'views/components/HeroSlider.php';

    // 4. NHÚNG DẢI TÍNH NĂNG DỊCH VỤ (FREE SHIP, ĐỔI TRẢ...)
    include 'views/components/ServiceFeatures.php';

    // 5. NHÚNG KHỐI DANH MỤC SẢN PHẨM DUYỆT THEO TAB TAB-TABS (NẾU CÓ)
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
                // Vòng lặp foreach thay cho hàm .map() của React
                // Chỉ lấy tối đa 4 sản phẩm đầu tiên giống products.slice(0, 4)
                $limitedProducts = array_slice($products, 0, 4);
                foreach ($limitedProducts as $product): 
                    // Nhúng ProductCard, mỗi lần chạy vòng lặp biến $product sẽ được truyền vào card đó
                    include 'views/components/ProductCard.php';
                endforeach; 
                ?>
            </div>

        </div>
    </div>

    <?php 
    // 7. NHÚNG COMPONENT FOOTER
    include 'views/components/Footer.php'; 
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    
    <script>
        // CHUYỂN ĐỔI HOÀN HẢO TỪ USEEFFECT KHỞI TẠO WOW.JS CỦA REACT
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                if (window.WOW) {
                    console.log("🟢 Đã tìm thấy thư viện WOW.js, đang kích hoạt hiệu ứng tại Trang Chủ!");
                    new window.WOW({
                        boxClass: 'wow',
                        animateClass: 'animated',
                        offset: 0,
                        mobile: true,
                        live: false
                    }).init();
                } else {
                    console.error("🔴 Không tìm thấy WOW.js. Trình duyệt chưa đọc được file script!");
                }
            }, 500);
        });
    </script>
</body>
</html>