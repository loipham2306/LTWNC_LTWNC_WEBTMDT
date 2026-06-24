<?php
    /**
     * @var array $product
     * @var string $imgPath
     * @var float|int $gia_hien_tai
     */

    // Đảm bảo các biến này luôn có giá trị mặc định để tránh lỗi runtime
    $product = $product ?? [];
    $imgPath = $imgPath ?? '';
    $gia_hien_tai = $gia_hien_tai ?? 0;
    
    ?>

    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $product ? htmlspecialchars($product['ten_san_pham']) : 'Sản phẩm không tồn tại' ?> - LuLoShop</title>
        
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">
        
        <style>
            body { background-color: #111; color: #fff; font-family: 'Segoe UI', sans-serif; }
            .detail-wrapper { background-color: #1a1a1a; min-height: 80vh; }
            
            /* Sidebar Styling */
            .sidebar-box { background-color: #222; border: 1px solid #333; padding: 20px; border-radius: 12px; margin-bottom: 24px; }
            .form-control { background-color: #111 !important; border: 1px solid #444 !important; color: #fff !important; }
            
            .btn-sidebar-cat { 
                display: block; width: 100%; text-align: left; padding: 10px 15px; 
                background: transparent; border: none; color: #ccc; 
                border-radius: 6px; transition: all 0.3s ease; text-decoration: none; font-size: 0.95rem; 
            }
            .btn-sidebar-cat:hover { background-color: #2a2a2a; color: #F28B00; padding-left: 20px; }
            .btn-sidebar-cat.active { background-color: #F28B00; color: #fff !important; font-weight: bold; }

            .sidebar-sale-banner { background-color: #222; border: 1px solid #333; border-radius: 12px; padding: 20px; text-align: center; position: relative; overflow: hidden; }
            
            /* Product Info Styling */
            .main-img-box { background-color: #222; border: 1px solid #333; border-radius: 12px; height: 400px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
            .thumb-box { background-color: #222; border: 1px solid #333; border-radius: 8px; width: 75px; height: 75px; display: flex; align-items: center; justify-content: center; cursor: pointer; overflow: hidden; transition: all 0.3s ease; }
            .thumb-box:hover { transform: translateY(-3px); }
            .thumb-box.active { border-color: #F28B00; }
            
            .text-orange { color: #F28B00 !important; }
            .info-gray-box { background-color: #222; border-left: 3px solid #444; padding: 12px 20px; border-radius: 4px; color: #aaa; font-size: 0.9rem; }
            
            /* Quantity & Button */
            .qty-container { display: flex; align-items: center; background-color: #222; border: 1px solid #444; border-radius: 20px; width: fit-content; overflow: hidden; }
            .qty-btn { background: transparent; border: none; color: #fff; width: 35px; height: 35px; font-size: 1.1rem; }
            .qty-btn:hover { background-color: #333; color: #F28B00; }
            .qty-input { background: transparent; border: none; color: #fff; text-align: center; width: 45px; font-weight: bold; }
            
            .btn-orange-lg { background-color: #F28B00; color: #fff; font-weight: bold; border: none; border-radius: 25px; padding: 12px 40px; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s; }
            .btn-orange-lg:hover { background-color: #d67a00; color: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(242, 139, 0, 0.4); }
            
            /* Mở rộng khung hiển thị chính */
            .main-content-container {
                max-width: 1000px;
                margin: 0 auto;
                background-color: #1a1a1a;
                padding: 30px;
                border-radius: 16px;
                border: 1px solid #333;
                position: relative;
            }

            /* Ảnh chính lớn hơn và nổi bật hơn */
            .main-img-box {
                background-color: #111;
                border: 1px solid #333;
                border-radius: 16px;
                height: 450px; 
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                padding: 20px;
            }

            /* Tăng kích thước font chữ tiêu đề */
            .product-title-lg {
                font-size: 2.2rem;
                font-weight: 800;
                color: #fff;
                margin-bottom: 0.5rem;
            }

            .price-tag {
                font-size: 1.8rem;
                color: #F28B00;
                font-weight: bold;
            }   
            .short-description {
                font-size: 1.1rem;
                font-style: italic;
                border-left: 3px solid #F28B00;
                padding-left: 15px;
            }

            .table-dark { background-color: #1a1a1a; }
            .table-dark th { color: #F28B00; width: 30%; }
            
            /* SỬA LẠI NÚT QUAY LẠI CHO ĐẸP VÀ CHUẨN VỊ TRÍ */
            .back-btn-wrapper {
                max-width: 1000px;
                margin: 0 auto 15px auto;
            }
            .back-link-btn {
                display: inline-flex;
                align-items: center;
                background-color: #222;
                color: #F28B00;
                border: 1px solid #333;
                padding: 8px 20px;
                border-radius: 30px;
                text-decoration: none;
                font-weight: bold;
                transition: all 0.3s ease;
            }
            .back-link-btn:hover {
                background-color: #F28B00;
                color: #fff;
                border-color: #F28B00;
                transform: translateX(-5px);
            }

            .color-option{ transition: all .3s ease; }
            .color-option:hover{ transform: scale(1.1); }
            .color-option.active{ border:3px solid #F28B00 !important; box-shadow: 0 0 10px #F28B00; }
            .table-custom th, .table-custom td { padding: 12px 15px !important; vertical-align: middle; }
            
            /* Tabs Styling */
            .nav-tabs { border-bottom: 1px solid #333; }
            .nav-tabs .nav-link { color: #aaa; border: none; font-weight: bold; padding: 12px 20px; }
            .nav-tabs .nav-link.active { background: transparent; color: #F28B00; border-bottom: 2px solid #F28B00; }
            
            /* Nút size khi hết hàng */
            .btn-size-disabled {
                position: relative;
                background-color: #333 !important;
                border-color: #444 !important;
                color: #555 !important;
                cursor: not-allowed !important;
                overflow: hidden; 
            }
            .btn-size-disabled::after {
                content: '✕';
                position: absolute;
                top: 0; left: 0; width: 100%; height: 100%;
                display: flex; align-items: center; justify-content: center;
                font-size: 1.5rem;
                color: rgba(255, 0, 0, 0.5); 
                background: rgba(0, 0, 0, 0.2); 
                pointer-events: none; 
            }
            .text-warning { color: #ffc107 !important; }
            .text-secondary { color: #444 !important; }
            .price-sale { font-size: 28px; font-weight: 800; color: #F28B00; }
            .price-old { font-size: 16px; color: #999; text-decoration: line-through; margin-top: 5px; }
            .discount { display: inline-block; background: red; color: white; font-size: 13px; padding: 2px 8px; border-radius: 6px; margin-left: 8px; }
        </style>
    </head>
    <body>

        <?php
        $pageTitle = "Chi Tiết Sản Phẩm";
        $pageBreadcrumb = "Chi Tiết";
        include __DIR__ . '/../components/Header.php';
        include __DIR__ . '/../components/PageHeader.php';
        ?>

        <div class="container-fluid detail-wrapper py-5">
            <div class="container py-4">
                
                <?php if (!$product): ?>
                    <div class="text-center py-5 wow fadeIn">
                        <i class="fas fa-box-open fs-1 text-secondary mb-3"></i>
                        <h2 class="text-white">Sản phẩm này không tồn tại hoặc đã bị xóa!</h2>
                        <a href="Shop.php" class="btn btn-orange-lg mt-3">Quay Lại Cửa Hàng</a>
                    </div>
                <?php else: ?>
                    
                    <div class="back-btn-wrapper wow fadeInDown" data-wow-delay="0.1s">
                        <a href="index.php?act=Shop" class="back-link-btn">
                            <i class="fas fa-arrow-left me-2"></i> Quay lại cửa hàng
                        </a>
                    </div>

                    <div class="main-content-container shadow-lg wow fadeInUp" data-wow-delay="0.2s">
                        <div class="row g-5">
                            <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.3s">
                                <div class="main-img-box shadow-lg">
                                    <img id="main-product-img" src="<?= $imgPath ?>" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                                </div>
                                <div class="d-flex gap-3 mt-4 justify-content-start" id="thumbnail-container">
                                    <?php
                                    // 1. Ảnh chính
                                    if (!empty($product['hinh_anh'])) {
                                        $mainImgPath = '/LTWNC_LTWNC_WEBTMDT/assets/images/products/' . trim($product['hinh_anh']);
                                        echo '<div class="thumb-box active" onclick="changeImage(\''.$mainImgPath.'\', this)">
                                                <img src="'.$mainImgPath.'" class="img-fluid">
                                              </div>';
                                    }

                                    $displayedImages = [];
                                    foreach ($product['bien_the'] as $bt) {
                                        $imgName = trim($bt['hinh_anh_bien_the']);
                                        if (!empty($imgName) && !in_array($imgName, $displayedImages)) {
                                            $displayedImages[] = $imgName; 
                                            $btPath = '/LTWNC_LTWNC_WEBTMDT/assets/images/products/Bien_The_Products/' . $imgName;
                                            
                                            echo '<div class="thumb-box" onclick="changeImage(\''.$btPath.'\', this)">
                                                    <img src="'.$btPath.'" class="img-fluid">
                                                  </div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.4s">
                                <h1 class="product-title-lg"><?= htmlspecialchars($product['ten_san_pham']) ?></h1>
                                <?php
                                   $variantDefault = $product['bien_the'][0] ?? null;
                                   $gia_sale = $variantDefault['gia_sau_giam'] ?? 0;
                                   $gia_goc = $variantDefault['gia_ban'] ?? 0;
                                   $giam = $variantDefault['phan_tram_giam'] ?? 0;
                                ?>

                                <div class="price-box mb-3 d-flex align-items-center gap-2">
                                    <div class="price-sale" id="price-display">
                                        <?= number_format($gia_sale, 0, ',', '.') ?> VNĐ
                                    </div>
                                    <div class="price-old" id="price-old" style="display: <?= $giam > 0 ? 'block' : 'none' ?>;">
                                        <?= number_format($gia_goc, 0, ',', '.') ?> VNĐ
                                    </div>
                                    <?php if ($giam > 0): ?>
                                        <div class="discount" id="discount-percent">-<?= $giam ?>%</div>
                                    <?php endif; ?>
                                </div>

                                <div class="short-description text-white-50 mb-4">
                                    <?php 
                                    $sentences = explode('.', $product['mo_ta']);
                                    echo htmlspecialchars($sentences[0] . '.'); 
                                    ?>
                                </div>

                                <table class="table table-dark table-striped mb-4 border-secondary table-custom">
                                    <tr>
                                        <th class="text-orange" style="width: 30%;">Thương hiệu</th>
                                        <td><?= htmlspecialchars($product['ten_thuong_hieu'] ?? 'Đang cập nhật') ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-orange">Danh mục</th>
                                        <td><?= htmlspecialchars($product['ten_danh_muc'] ?? 'Chưa phân loại') ?></td>
                                    </tr>
                                </table>

                                <div class="mb-4">
                                    <label class="text-white fw-bold mb-2">Chọn Size:</label>
                                    <div class="d-flex gap-2">
                                        <?php foreach ($product['bien_the'] as $bt): 
                                            $isOutOfStock = ($bt['so_luong_ton'] <= 0);
                                        ?>
                                            <button class="btn btn-outline-warning btn-sm px-3 <?= $isOutOfStock ? 'btn-size-disabled' : '' ?>" 
                                                <?= $isOutOfStock ? 'disabled' : '' ?>
                                                onclick="selectVariant(
                                                    <?= $bt['gia_sau_giam'] ?>, 
                                                    <?= $bt['so_luong_ton'] ?>, 
                                                    '<?= $bt['kich_co'] ?>', 
                                                    this, 
                                                    <?= $bt['gia_ban'] ?>, 
                                                    <?= $bt['phan_tram_giam'] ?? 0 ?>
                                                )">
                                                <?= htmlspecialchars($bt['kich_co']) ?>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                    <p id="stock-info" class="text-white-50 mt-2">Còn <?= $product['bien_the'][0]['so_luong_ton'] ?> sản phẩm</p>
                                </div>

                                <div class="mb-4">
                                    <label class="text-white fw-bold mb-2">Chọn Màu:</label>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <?php
                                        $displayedColors = [];
                                        foreach ($product['bien_the'] as $bt):
                                            $color = trim($bt['mau_sac']);
                                            if (!empty($color) && !in_array($color, $displayedColors)) {
                                                $displayedColors[] = $color;
                                        ?>
                                            <div class="color-option" data-color="<?= htmlspecialchars($color) ?>"
                                                onclick="selectColor('<?= $color ?>', this)"
                                                style="width:40px; height:40px; border-radius:50%; background:<?= $color ?>; border:2px solid #666; cursor:pointer;">
                                            </div>
                                        <?php
                                            }
                                        endforeach;
                                        ?>
                                    </div>
                                    <p id="selected-color-text" class="text-white-50 mt-2">Chưa chọn màu</p>
                                </div>          

                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="qty-container">
                                        <button class="qty-btn" onclick="updateQty(-1)">-</button>
                                        <input type="number" id="buyQty" class="qty-input" value="1" min="1" max="1" readonly>
                                        <button class="qty-btn" onclick="updateQty(1)">+</button>
                                    </div>
                                    <button onclick="addToCartDetail()" class="btn btn-orange-lg flex-grow-1">
                                        <i class="fas fa-shopping-cart me-2"></i> Thêm vào giỏ
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-top border-secondary wow fadeInUp" data-wow-delay="0.5s">
                            <ul class="nav nav-tabs" id="productTabs">
                                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc">Chi tiết</button></li>
                                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#review">Đánh giá</button></li>
                            </ul>
                            <div class="tab-content p-4">
                                <div class="tab-pane fade show active text-white-50" id="desc">
                                    <?= nl2br(htmlspecialchars($product['mo_ta'])) ?>
                                </div>
                                <div class="tab-pane fade" id="review">
                                    <?php if (empty($DanhSachBinhLuan)): ?>
                                        <p class="text-white-50">Chưa có đánh giá nào cho sản phẩm này.</p>
                                    <?php else: ?>
                                        <?php foreach ($DanhSachBinhLuan as $bl): ?>
                                            <div class="card bg-dark border-secondary mb-3">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <h6 class="text-orange fw-bold"><?= htmlspecialchars($bl['ho_ten']) ?></h6>
                                                        <small class="text-white-50 fw-bold"><?= date('d/m/Y H:i', strtotime($bl['ngay_binh_luan'])) ?></small>                                                </div>
                                                    <div class="text-warning mb-2">
                                                        <?php for($i=1; $i<=5; $i++): ?>
                                                            <i class="fas fa-star <?= $i <= $bl['so_sao'] ? 'text-warning' : 'text-secondary' ?>"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <p class="text-white mb-0"><?= htmlspecialchars($bl['noi_dung']) ?></p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php include __DIR__ . '/../components/Footer.php'; ?>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
        <script>
            // Khởi tạo hiệu ứng WOW
            new WOW().init();

            const variants = <?= json_encode($product['bien_the'] ?? []) ?>;
            let selectedVariant = null;
            window.currentSelectedSize = null;
            window.currentSelectedColor = null;
            window.currentStock = 0; 
            
            function getVariant() {
                return variants.find(v =>
                    v.kich_co == window.currentSelectedSize &&
                    v.mau_sac == window.currentSelectedColor
                );
            }   
            
            function addToCartDetail() {
                const variant = getVariant();

                if (!variant) {
                    alert("Vui lòng chọn Size và Màu!");
                    return;
                }

                const formData = new FormData();
                formData.append('id_bien_the', variant.id_bien_the);
                
                const priceToSubmit = variant.gia_sau_giam > 0 ? variant.gia_sau_giam : variant.gia_ban;
                formData.append('gia', priceToSubmit); 
                formData.append('so_luong', document.getElementById('buyQty').value);

                fetch('index.php?act=ThemGioHang', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.text())
                .then(data => {
                    if (data.trim() === 'success') {
                        alert('Đã thêm vào giỏ hàng');
                        location.href = 'index.php?act=GioHang';
                    } else {
                        alert(data);
                    }
                });
            }

            function changeImage(src, element) {
                const mainImg = document.getElementById('main-product-img');
                if (mainImg) {
                    // Thêm hiệu ứng fade in nhẹ khi đổi ảnh
                    mainImg.style.opacity = 0;
                    setTimeout(() => {
                        mainImg.src = src;
                        mainImg.style.opacity = 1;
                        mainImg.style.transition = "opacity 0.3s ease-in-out";
                    }, 150);
                }

                document.querySelectorAll('.thumb-box').forEach(el => el.classList.remove('active'));
                element.classList.add('active');
            }

            function updateProductInfo() {
                const variant = variants.find(v => {
                    let matchSize = !window.currentSelectedSize || v.kich_co == window.currentSelectedSize;
                    let matchColor = !window.currentSelectedColor || v.mau_sac == window.currentSelectedColor;
                    return matchSize && matchColor;
                });

                if (!variant) return;

                window.currentStock = parseInt(variant.so_luong_ton);

                document.getElementById('price-display').innerText =
                    new Intl.NumberFormat('vi-VN').format(variant.gia_sau_giam) + ' VNĐ';

                const oldPriceEl = document.getElementById('price-old');
                const discountEl = document.getElementById('discount-percent');

                if (variant.phan_tram_giam > 0) {
                    oldPriceEl.style.display = 'block';
                    discountEl.style.display = 'inline-block';
                    oldPriceEl.innerText = new Intl.NumberFormat('vi-VN').format(variant.gia_ban) + ' VNĐ';
                    discountEl.innerText = '-' + variant.phan_tram_giam + '%';
                } else {
                    oldPriceEl.style.display = 'none';
                    discountEl.style.display = 'none';
                }

                document.getElementById('stock-info').innerText = `Còn ${variant.so_luong_ton} sản phẩm`;
                document.getElementById('buyQty').value = 1;
                document.getElementById('buyQty').setAttribute('max', variant.so_luong_ton);
            }

            function selectVariant(price, stock, size, element) {
                window.currentSelectedSize = size;
                document.querySelectorAll('.btn-outline-warning').forEach(btn => btn.classList.remove('active'));
                element.classList.add('active');
                window.currentStock = stock;
                const variant = getVariant();
                if (variant) selectedVariant = variant;
                document.getElementById('stock-info').innerText = `Còn ${stock} sản phẩm`;
                document.getElementById('buyQty').value = 1;
                updateProductInfo();
            }

            function selectColor(color, element) {
                window.currentSelectedColor = color;
                document.querySelectorAll('.color-option').forEach(el => el.classList.remove('active'));
                element.classList.add('active');
                document.getElementById('selected-color-text').innerText = 'Màu đã chọn: ' + color;
                const variant = getVariant();
                if (variant) {
                    selectedVariant = variant;
                    updateProductInfo(); 
                }
            }

            function updateQty(change) {
                let qtyInput = document.getElementById('buyQty');
                let currentQty = parseInt(qtyInput.value);
                let newQty = currentQty + change;
                if (newQty < 1) newQty = 1;
                if (newQty > window.currentStock) {
                    alert('Chỉ còn ' + window.currentStock + ' sản phẩm trong kho!');
                    return;
                }
                qtyInput.value = newQty;
                document.getElementById('stock-info').innerText =
                    `Còn ${window.currentStock} sản phẩm (Size ${window.currentSelectedSize})`;
            }
        </script>
    </body>
    </html>