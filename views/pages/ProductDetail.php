<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sản Phẩm - LuLoShop</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">

    <style>
        /* Tông nền tối eSports vững chãi */
        body { background-color: #111; color: #fff; font-family: 'Segoe UI', sans-serif; }
        .shop-wrapper { background-color: #1a1a1a; }
        
        /* Sidebar & Thùng chứa */
        .sidebar-box { background-color: #222; border: 1px solid #333; padding: 20px; border-radius: 8px; }
        .category-link { color: #ccc; text-decoration: none; transition: 0.3s; }
        .category-link:hover { color: #F28B00; padding-left: 5px; }
        
        /* Inputs & Thanh tìm kiếm */
        .form-control { background-color: #111 !important; border: 1px solid #444 !important; color: #fff !important; }
        .form-control:focus { border-color: #F28B00 !important; box-shadow: 0 0 0 0.25rem rgba(242, 139, 0, 0.25) !important; }
        .input-group-text { background-color: #333; border: 1px solid #444; color: #F28B00; }

        /* Khu vực trưng bày ảnh */
        .main-img-container { background-color: #222 !important; border: 1px solid #333; height: 400px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .thumb-box { width: 22%; cursor: pointer; background-color: #222 !important; border: 1px solid #333; transition: 0.2s; }
        .thumb-box.active { border: 2px solid #F28B00 !important; }

        /* Số lượng & Nút bấm màu Cam thương hiệu */
        .btn-qty { background: #333; border: 1px solid #444; color: #fff; transition: 0.3s; }
        .btn-qty:hover { background: #F28B00; border-color: #F28B00; }
        .btn-orange { background-color: #F28B00 !important; color: #fff !important; border: none; }
        .btn-orange:hover { background-color: #d67a00 !important; }
        .text-orange { color: #F28B00 !important; }

        /* Cấu trúc thanh điều hướng Tabs điều khiển thông tin */
        .nav-tabs { border-bottom: 2px solid #333; }
        .nav-tabs .nav-link { color: #ccc; border: none !important; background: transparent !important; font-weight: bold; padding: 12px 24px; }
        .nav-tabs .nav-link.active { color: #F28B00 !important; border-bottom: 3px solid #F28B00 !important; }
        .tab-content { background-color: #222; border: 1px solid #333; border-top: none; padding: 25px; border-radius: 0 0 8px 8px; color: #eee; }
    </style>
</head>
<body>

    <?php
    // Gán dữ liệu cho cấu trúc Banner tiêu đề trang cha
    $pageTitle = "Chi Tiết Sản Phẩm";
    $pageBreadcrumb = "Sản Phẩm";

    // Kéo các thành phần linh hồn giao diện
    include '../components/Header.php';
    include '../components/PageHeader.php';

    // Giả lập lấy thông tin sản phẩm (Sau này bạn kết nối database lấy theo $_GET['id'] nhé)
    $product = [
        'id' => 1,
        'name' => 'Smart Camera Pro Max',
        'category' => 'Điện Tử',
        'price' => 3350000,
        'oldPrice' => 4110000,
        'code' => 'CAM-2026',
        'stock' => 20,
        'img' => '/LTWNC_BAN_HANG/assets/images/img/product-4.png'
    ];

    // Danh sách ảnh nhỏ đi kèm dải sản phẩm
    $thumbnails = [
        '/LTWNC_BAN_HANG/assets/images/img/product-4.png',
        '/LTWNC_BAN_HANG/assets/images/img/product-5.png',
        '/LTWNC_BAN_HANG/assets/images/img/product-6.png',
        '/LTWNC_BAN_HANG/assets/images/img/product-7.png'
    ];
    ?>

    <div class="container-fluid shop-wrapper py-5">
        <div class="container py-5">
            <div class="row g-4">
                
                <div class="col-lg-5 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="input-group w-100 mx-auto d-flex mb-4">
                        <input type="search" class="form-control p-3" placeholder="Tìm kiếm...">
                        <span class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                    
                    <div class="sidebar-box mb-4">
                        <h4 class="fw-bold text-orange text-uppercase mb-3" style="font-size: 1.1rem; letter-spacing: 1px;">Danh Mục</h4>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2 d-flex justify-content-between align-items-center">
                                <a href="#" class="category-link"><i class="fas fa-laptop text-orange me-2"></i> Điện Tử & Máy Tính</a>
                                <span class="text-white-50 small">(5)</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <a href="#" class="category-link"><i class="fas fa-mobile-alt text-orange me-2"></i> Điện Thoại & Tablet</a>
                                <span class="text-white-50 small">(8)</span>
                            </li>
                        </ul>
                    </div>

                    <a href="#" class="text-decoration-none">
                        <div class="position-relative overflow-hidden rounded shadow-sm">
                            <img src="/LTWNC_BAN_HANG/assets/images/img/product-banner-2.jpg" class="img-fluid w-100" alt="Khuyến mãi" />
                            <div class="text-center position-absolute d-flex flex-column align-items-center justify-content-center p-4"
                                style="width: 100%; height: 100%; top: 0; left: 0; background: rgba(0, 0, 0, 0.75);">
                                <h5 class="display-6 fw-bold text-orange m-0">SALE</h5>
                                <h4 class="text-white my-2 fw-bold">Giảm Đến 50%</h4>
                                <button class="btn btn-orange rounded-pill px-4 mt-2 fw-bold">Mua Ngay</button>
                            </div>
                        </div>
                    </a>
                </div>


                <div class="col-lg-7 col-xl-9 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="row g-4 single-product">
                        
                        <div class="col-xl-6">
                            <div class="bg-light rounded mb-3 p-4 main-img-container">
                                <img id="productMainImg" src="<?= $product['img'] ?>" class="img-fluid rounded" style="max-height: 100%; object-fit: contain;" alt="Sản phẩm chính">
                            </div>
                            <div class="d-flex justify-content-between">
                                <?php foreach ($thumbnails as $index => $imgSrc): ?>
                                    <div class="bg-light rounded p-2 thumb-box <?= $index === 0 ? 'active' : '' ?>" onclick="changeMainImage(this, '<?= $imgSrc ?>')">
                                        <img src="<?= $imgSrc ?>" class="img-fluid" alt="Ảnh thu nhỏ <?= $index ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="col-xl-6 ps-xl-4">
                            <h3 class="fw-bold mb-2 text-white"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="mb-3 text-white-50">Danh mục: <span class="text-orange fw-bold"><?= htmlspecialchars($product['category']) ?></span></p>
                            <h4 class="fw-bold mb-3 text-orange fs-2"><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</h4>
                            
                            <div class="d-flex mb-4 align-items-center">
                                <div class="text-orange me-2">
                                    <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-half-alt"></i>
                                </div>
                                <span class="text-white-50 small">(15 đánh giá)</span>
                            </div>

                            <div class="d-flex flex-column mb-4 border-start border-3 border-warning ps-3 py-1 bg-dark-subtle">
                                <small class="text-white-50">Mã SP: <strong class="text-white"><?= $product['code'] ?></strong></small>
                                <small class="text-white-50">Tình trạng: <strong class="text-orange">Còn <?= $product['stock'] ?> sản phẩm</strong></small>
                            </div>
                            
                            <p class="mb-4 text-white-50" style="line-height: 1.6;">
                                Camera thông minh tích hợp AI nhận diện khuôn mặt, đàm thoại 2 chiều rõ nét. Phù hợp cho an ninh gia đình và cửa hàng giám sát chặt chẽ.
                            </p>

                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="input-group quantity" style="width: 130px;">
                                    <button class="btn btn-sm btn-minus rounded-circle btn-qty" onclick="handleQty('minus')">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <input type="text" id="qtyInput" class="form-control form-control-sm text-center border-0 fw-bold bg-transparent text-white" value="1" readonly style="width: 40px;">
                                    <button class="btn btn-sm btn-plus rounded-circle btn-qty" onclick="handleQty('plus')">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <?php $productJson = htmlspecialchars(json_encode($product), ENT_QUOTES, 'UTF-8'); ?>
                            <button onclick='addToCartFromDetail(<?= $productJson ?>)' class="btn btn-orange rounded-pill px-5 py-3 fw-bold text-uppercase shadow-sm">
                                <i class="fa fa-shopping-bag me-2"></i> Thêm Vào Giỏ Hàng
                            </button>
                        </div>

                        <div class="col-lg-12 mt-5">
                            <nav>
                                <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about" type="button" role="tab">Mô Tả Sản Phẩm</button>
                                    <button class="nav-link" id="nav-review-tab" data-bs-toggle="tab" data-bs-target="#nav-review" type="button" role="tab">Đánh Giá (2)</button>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-about" role="tabpanel">
                                    <p>Sản phẩm chính hãng, bảo hành 12 tháng hệ thống toàn quốc. Camera hỗ trợ góc xoay rộng 360 độ, kết nối tín hiệu mạng Wifi ổn định băng tần kép cực cao, dễ dàng quản lý thông số qua ứng dụng di động thông minh.</p>
                                    <h6 class="fw-bold text-orange mb-2">Tính năng nổi bật:</h6>
                                    <ul class="mb-0 ps-3 text-white-50">
                                        <li class="mb-1">Độ phân giải siêu căng chuẩn 2K nét đứt.</li>
                                        <li class="mb-1">Thuật toán AI phát hiện chuyển động chuyển dịch tức thời.</li>
                                        <li>Hỗ trợ lưu trữ thẻ nhớ mở rộng lên đến 256GB.</li>
                                    </ul>
                                </div>
                                <div class="tab-pane fade" id="nav-review" role="tabpanel">
                                    <p class="mb-0 text-white-50">Hiện chưa có đánh giá nào từ cộng đồng game thủ. Hãy là người đầu tiên để lại ý kiến đánh giá sản phẩm này của bạn!</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include '../components/Footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    
    <script>
        new WOW().init();

        // 1. XỬ LÝ CHUYỂN ĐỔI ẢNH CHÍNH (Thay thế useState mainImg)
        function changeMainImage(element, newSrc) {
            document.getElementById('productMainImg').src = newSrc;
            
            // Xóa viền active ở các ảnh cũ và thêm vào ảnh vừa click
            document.querySelectorAll('.thumb-box').forEach(box => box.classList.remove('active'));
            element.classList.add('active');
        }

        // 2. XỬ LÝ TĂNG GIẢM SỐ LƯỢNG (Thay thế useState quantity)
        function handleQty(type) {
            const qtyInput = document.getElementById('qtyInput');
            let currentQty = parseInt(qtyInput.value);

            if (type === 'minus' && currentQty > 1) {
                qtyInput.value = currentQty - 1;
            } else if (type === 'plus') {
                qtyInput.value = currentQty + 1;
            }
        }

        // 3. XỬ LÝ THÊM VÀO GIỎ HÀNG ĐỒNG BỘ VỚI LOCALSTORAGE
        function addToCartFromDetail(product) {
            const qtyInput = document.getElementById('qtyInput');
            let quantityToAdd = parseInt(qtyInput.value);

            let currentCart = JSON.parse(localStorage.getItem('cart')) || [];
            let existingItemIndex = currentCart.findIndex(item => item.id === product.id);

            if (existingItemIndex !== -1) {
                // Cộng dồn số lượng khách chọn trong trang chi tiết
                currentCart[existingItemIndex].quantity += quantityToAdd;
            } else {
                // Thêm mới hoàn toàn
                product.quantity = quantityToAdd;
                product.selected = true;
                currentCart.push(product);
            }

            localStorage.setItem('cart', JSON.stringify(currentCart));
            
            // Cập nhật số lượng trên icon Header (nếu có)
            if (typeof updateCartBadge === 'function') updateCartBadge();

            alert(`Thành công! Đã thêm ${quantityToAdd} sản phẩm ${product.name} vào giỏ hàng!`);
        }
    </script>
</body>
</html>