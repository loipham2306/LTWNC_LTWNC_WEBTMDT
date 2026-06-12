<?php


?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuLoShop - Trạm Hiệu Streetwear & Sneaker</title>
    
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
    
    // 1. KẾT NỐI DATABASE VÀ LẤY TỐI ĐA 4 SẢN PHẨM MỚI NHẤT
    require_once 'config/database.php';
    $db = new Database();
    $conn = $db->getConnection();

    $sql = "SELECT sp.*, dm.ten_danh_muc 
            FROM san_pham sp 
            LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
            WHERE sp.trang_thai = 1 
            ORDER BY sp.id_san_pham DESC 
            LIMIT 4";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    // ĐỔI TÊN BIẾN THÀNH $homeFeaturedProducts ĐỂ CHỐNG BỊ CÁC FILE KHÁC ĐÈ MẤT DỮ LIỆU
    $homeFeaturedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. NHÚNG COMPONENT HEADER (THANH MENU)
    include 'views/components/Header.php';

    // 3. NHÚNG BANNER SLIDER CHÍNH
    include 'views/components/HeroSlider.php';

    // 4. NHÚNG DẢI TÍNH NĂNG DỊCH VỤ (FREE SHIP, ĐỔI TRẢ...)
    include 'views/components/ServiceFeatures.php';

    // 5. NHÚNG KHỐI DANH MỤC SẢN PHẨM DUYỆT THEO TAB (NẾU CÓ)
    include 'views/components/FeaturedProducts.php';
    ?>

    <div class="container-fluid home-section py-5">
        <div class="container py-5">
            
            <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                <h1 class="fw-bold text-uppercase section-title">Sản Phẩm Nổi Bật</h1>
                <p class="text-white-50 mt-3">Khám phá ngay những xu hướng thời trang Streetwear và Sneaker hot nhất với mức giá cực ưu đãi.</p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <?php 
                // Sử dụng đúng tên biến mới đã được bảo vệ an toàn
                if (!empty($homeFeaturedProducts)):
                    foreach ($homeFeaturedProducts as $product): 
                        // Nhúng ProductCard, dữ liệu $product sẽ được truyền an toàn vào đây
                        include 'views/components/ProductCard.php';
                    endforeach; 
                else:
                ?>
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">Chưa có sản phẩm nào trong hệ thống 😅</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <?php 
    // 7. NHÚNG COMPONENT FOOTER
    include 'views/components/Footer.php'; 
    ?>

    <script src="https://cdnjs.cloudflare.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    
    <script>
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