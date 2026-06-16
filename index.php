<?php
$brands = $brands??[];
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
        .voucher-card{
            background: linear-gradient(145deg,#0f0f0f,#1c1c1c);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 16px;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: 0.3s;
        }

        .voucher-card:hover{
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(255,193,7,0.15);
            border-color: #ffc107;
        }

        .voucher-top{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:10px;
        }

        .voucher-code{
            background:#ffc107;
            color:#000;
            padding:4px 10px;
            border-radius:20px;
            font-weight:700;
            font-size:13px;
        }

        .voucher-status{
            font-size:12px;
            color:#00e676;
            font-weight:600;
        }

        .voucher-discount{
            font-size:18px;
            font-weight:700;
            margin-bottom:8px;
        }

        .voucher-discount span{
            color:#ffc107;
        }

        .voucher-condition,
        .voucher-exp{
            font-size:13px;
            color:#ccc;
            margin-bottom:4px;
        }

        .voucher-footer{
            margin-top:12px;
            text-align:right;
        }

        .btn-copy{
            background:#ffc107;
            border:none;
            padding:6px 12px;
            border-radius:8px;
            font-weight:600;
            cursor:pointer;
            transition:0.2s;
        }

        .btn-copy:hover{
            background:#ffb300;
            transform:scale(1.05);
        }
        .btn-copy {
            background-color: #F28B00;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-copy:hover {
            background-color: #d67a00;
            color: white;
            transform: scale(1.05);
        }
        .voucher-scroll {
    display: flex;
    flex-wrap: nowrap;        /* KHÔNG xuống dòng */
    overflow-x: auto;         /* cho cuộn ngang */
    gap: 12px;
    padding-bottom: 10px;
    scroll-behavior: smooth;
}

/* ẩn scrollbar cho đẹp */
.voucher-scroll::-webkit-scrollbar {
    height: 6px;
}

.voucher-scroll::-webkit-scrollbar-thumb {
    background: #444;
    border-radius: 10px;
}

.voucher-scroll::-webkit-scrollbar-track {
    background: transparent;
}

/* card không co lại */
.voucher-scroll .col-12 {
    flex: 0 0 auto;
    width: 320px; /* chỉnh size voucher */
}
    </style>
</head>
<body>

    <?php

    // 2. NHÚNG COMPONENT HEADER (THANH MENU)
    include 'views/components/Header.php';

    // 3. NHÚNG BANNER SLIDER CHÍNH
    include 'views/components/HeroSlider.php';
    
    include 'views/components/VoucherHome.php';
    // 4. NHÚNG DẢI TÍNH NĂNG DỊCH VỤ (FREE SHIP, ĐỔI TRẢ...)
    include 'views/components/ServiceFeatures.php';

    // 5. NHÚNG KHỐI DANH MỤC SẢN PHẨM DUYỆT THEO TAB (NẾU CÓ)
    include 'views/components/FeaturedProducts.php';
    ?>
    <div class="d-flex justify-content-center flex-wrap mb-4 gap-2">

        <button class="btn btn-warning rounded-pill px-3"
                onclick="filterBrand('all', this)">
            Tất cả
        </button>

        <?php foreach ($brands as $b): ?>
            <button class="btn btn-outline-warning rounded-pill px-3"
                    onclick="filterBrand('<?= htmlspecialchars($b['ten_thuong_hieu']) ?>', this)">
                <?= htmlspecialchars($b['ten_thuong_hieu']) ?>
            </button>
        <?php endforeach; ?>

    </div>
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
        function layVoucher(buttonElement, idVoucher) {
            // buttonElement chính là cái nút bạn vừa bấm
            fetch(`index.php?act=LuuVoucher&id_voucher=${idVoucher}`)
                .then(response => response.text())
                .then(data => {
                    // Trim để loại bỏ khoảng trắng thừa
                    if (data.trim() === "success") {
                        // Chỉ đổi màu nút này
                        buttonElement.innerText = "Đã lưu";
                        buttonElement.style.backgroundColor = "#555";
                        buttonElement.disabled = true;
                        
                        alert("🎉 Đã lưu mã thành công!");
                    } else {
                        alert("⚠️ Có lỗi xảy ra!");
                    }
                });
        }
    </script>
</body>
</html>