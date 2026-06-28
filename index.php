<?php
$brands = $brands ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trạm Hiệu - Streetwear & Sneaker</title>
    
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
            overflow-x: auto;          /* cho cuộn ngang */
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
    // NHÚNG COMPONENT HEADER (THANH MENU)
    include 'views/components/Header.php';

    // NHÚNG BANNER SLIDER CHÍNH
    include 'views/components/HeroSlider.php';
    
    include 'views/components/VoucherHome.php';

    // NHÚNG DẢI TÍNH NĂNG DỊCH VỤ (FREE SHIP, ĐỔI TRẢ...)
    include 'views/components/ServiceFeatures.php';
    ?>

    <div class="d-flex justify-content-center flex-wrap mb-4 gap-2">
        <button type="button" class="btn btn-warning rounded-pill px-3 fw-bold text-dark"
                onclick="filterBrand('all', this)">
            Tất cả
        </button>

        <?php foreach ($brands as $b): ?>
            <button type="button" class="btn btn-outline-warning rounded-pill px-3"
                    onclick="filterBrand('<?= addslashes(trim($b['ten_thuong_hieu'])) ?>', this)">
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
                if (!empty($homeFeaturedProducts)):
                    foreach ($homeFeaturedProducts as $product): 
                        include 'views/components/ProductCard.php';
                    endforeach; 
                endif; 
                ?>

                <div id="no-product-alert" class="col-12 text-center py-5 d-none">
                    <p class="text-muted fs-5">Thương hiệu này hiện chưa có sản phẩm nổi bật nào 😅</p>
                </div>
            </div>

        </div>
    </div>

    <?php 
    // NHÚNG COMPONENT FOOTER
    include 'views/components/Footer.php'; 
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        function filterBrand(brandName, btnElement) {
            // 1. Điều khiển trạng thái active của các nút bấm
            let container = btnElement.parentElement;
            let buttons = container.querySelectorAll('button');
            buttons.forEach(btn => {
                btn.classList.remove('btn-warning', 'text-dark', 'fw-bold');
                btn.classList.add('btn-outline-warning');
            });
            
            btnElement.classList.remove('btn-outline-warning');
            btnElement.classList.add('btn-warning', 'text-dark', 'fw-bold');

            // 2. Tiến hành lọc ẩn/hiện sản phẩm
            let products = document.querySelectorAll('.home-product-item');
            let count = 0;         // Đếm giới hạn 8 sản phẩm cho mục "Tất cả"
            let visibleCount = 0;  // Đếm xem có sản phẩm nào được hiển thị không

            products.forEach(item => {
                let itemBrand = item.getAttribute('data-brand') ? item.getAttribute('data-brand').trim() : '';
                
                if (brandName === 'all') {
                    if (count < 8) {
                        item.classList.remove('d-none');
                        count++;
                        visibleCount++;
                    } else {
                        item.classList.add('d-none');
                    }
                } else {
                    // Chuyển cả 2 chuỗi về chữ thường (.toLowerCase()) để so khớp chính xác tuyệt đối
                    if (itemBrand.toLowerCase() === brandName.toLowerCase()) {
                        item.classList.remove('d-none');
                        visibleCount++;
                    } else {
                        item.classList.add('d-none');
                    }
                }
            });

            // 3. Xử lý hiển thị thông báo "Không có sản phẩm"
            let alertBox = document.getElementById('no-product-alert');
            if (alertBox) {
                if (visibleCount === 0) {
                    alertBox.classList.remove('d-none');
                } else {
                    alertBox.classList.add('d-none');
                }
            }
        }

        // TỰ ĐỘNG CHẠY: Gọi bộ lọc "Tất cả" ngay khi tải xong trang
        document.addEventListener("DOMContentLoaded", function() {
            let defaultBtn = document.querySelector('button[onclick="filterBrand(\'all\', this)"]');
            if(defaultBtn) {
                filterBrand('all', defaultBtn);
            }
            
            // Kích hoạt WOW hiệu ứng chuyển động
            setTimeout(function() {
                if (window.WOW) {
                    new window.WOW({
                        boxClass: 'wow',
                        animateClass: 'animated',
                        offset: 0,
                        mobile: true,
                        live: false
                    }).init();
                }
            }, 500);
        });

        function layVoucher(buttonElement, idVoucher) {
            fetch(`index.php?act=LuuVoucher&id_voucher=${idVoucher}`)
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
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