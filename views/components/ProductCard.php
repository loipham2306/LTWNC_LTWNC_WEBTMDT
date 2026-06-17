<?php
/** @var array $product */

// Ánh xạ (Map) dữ liệu từ DB sang biến hiển thị
$name = $product['ten_san_pham'] ?? 'Sản phẩm không tên';
$category = $product['ten_danh_muc'] ?? 'Chưa phân loại';
$id = $product['id_san_pham'] ?? '#';

// Xử lý giá (DB trả về gia_co_ban)
$price = $product['gia_co_ban'] ?? 0;
// Vì bạn chưa có bảng khuyến mãi, tạm thời để oldPrice là 0
$oldPrice = '&nbsp;'; 
$currentPrice = number_format($price, 0, ',', '.') . ' đ';

// Xử lý ảnh (DB trả về hinh_anh)
$imgName = !empty($product['hinh_anh']) ? basename($product['hinh_anh']) : 'default.png';
$productImg = '/LTWNC_LTWNC_WEBTMDT/assets/images/products/' . $imgName;
$stock = (int)($product['tong_ton_kho'] ?? 0);

$tags = [];

// 🔴 Hết hàng
if ($stock <= 0) {
    $tags[] = ['HẾT HÀNG', 'bg-danger'];
}

// 🟡 Sắp hết
if ($stock > 0 && $stock < 5) {
    $tags[] = ['SẮP HẾT', 'bg-warning text-dark'];
}


// 🆕 NEW (7 ngày gần đây)
if (!empty($product['ngay_tao']) 
    && strtotime($product['ngay_tao']) > strtotime('-7 days')) {
    $tags[] = ['MỚI', 'bg-primary'];
}
?>

<style>
    /* Tổng thể Card */
    .product-card-anim {
        background-color: #1a1a1a;
        border: 1px solid #333;
        transition: all 0.3s ease;
        overflow: hidden;
        border-radius: 12px;
    }
    
    /* Hiệu ứng Nảy lên khi Hover Card */
    .product-card-anim:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(242, 139, 0, 0.15);
        border-color: #F28B00;
    }

    /* 1. Overlay (Lớp phủ tối lên ảnh) */
    .img-overlay {
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .product-card-anim:hover .img-overlay { opacity: 1; }

    /* Nút con mắt xem chi tiết */
    .eye-btn {
        transform: scale(0.5);
        transition: all 0.3s ease;
        background-color: #222;
        color: #F28B00;
        border: 1px solid #F28B00;
    }
    .product-card-anim:hover .eye-btn { transform: scale(1); }
    .eye-btn:hover { background-color: #F28B00; color: #fff; }

    /* 2. Khối ẩn: Trượt xuống khi Hover */
    .card-expand-area {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: all 0.4s ease;
        margin-top: 0;
    }
    .product-card-anim:hover .card-expand-area {
        max-height: 150px;
        opacity: 1;
        margin-top: 15px;
    }

    /* Tiện ích màu sắc */
    .text-orange { color: #F28B00 !important; }
    .btn-orange-solid { background-color: #F28B00; color: #fff; border: none; }
    .btn-orange-solid:hover { background-color: #d67a00; color: #fff; }
</style>

<div class="col-md-6 col-lg-4 col-xl-3 mb-4 wow fadeInUp" data-wow-delay="0.1s">
    <div class="card h-100 product-card-anim">
        
        <div class="position-relative overflow-hidden" style="height: 240px; background-color: #222;">
            <img src="<?= $productImg ?>" class="img-fluid w-100 h-100" style="object-fit: cover;" alt="<?= htmlspecialchars($product['name']) ?>">
            
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center img-overlay">
                <a href="index.php?act=ProductDetail&id=<?= htmlspecialchars($id) ?>" 
                    class="btn rounded-circle d-flex align-items-center justify-content-center" 
                    style="width: 50px; height: 50px; background-color: #1a1a1a; color: #F28B00; border: 1px solid #F28B00;">
                        <i class="fa fa-eye fs-5"></i>
                </a>
            </div>
        </div>

        <div class="card-body text-center d-flex flex-column p-4">
            <?php foreach ($tags as $t): ?>
                <span class="position-absolute top-0 start-0 m-2 badge <?= $t[1] ?> mb-1 d-block">
                    <?= $t[0] ?>
                </span>
            <?php endforeach; ?>
            <p class="text-white-50 small mb-1"><?= htmlspecialchars($category) ?></p>
            
            <a href="/LTWNC_LTWNC_WEBTMDT/views/pages/ProductDetail.php?id=<?= htmlspecialchars($id) ?>" 
                class="card-title h6 fw-bold mb-2 text-white text-decoration-none d-block">
                    <?= htmlspecialchars($name) ?>
            </a>
                            
            <div class="d-flex justify-content-center align-items-center">
                <del class="me-2 text-white-50 small"><?= $oldPrice ?></del>
                <span class="text-orange fs-5 fw-bold"><?= $currentPrice ?></span>
            </div>

            <div class="card-expand-area">
                <div class="d-flex justify-content-center mb-3">
                    <i class="fas fa-star text-orange"></i>
                    <i class="fas fa-star text-orange"></i>
                    <i class="fas fa-star text-orange"></i>
                    <i class="fas fa-star text-orange"></i>
                    <i class="fas fa-star text-white-50"></i>
                </div>
                
                <?php 
                    // Mã hóa array sản phẩm sang định dạng JSON chuẩn an toàn dữ liệu
                    $productJson = htmlspecialchars(json_encode($product), ENT_QUOTES, 'UTF-8'); 
                ?>
                <a href="index.php?act=ProductDetail&id=<?= htmlspecialchars($id) ?>" 
                class="btn btn-orange-solid w-100 rounded-pill py-2 fw-bold shadow-sm text-decoration-none">
                    <i class="fas fa-cart-plus me-2"></i> Thêm Giỏ Hàng
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    // Kiểm tra chống trùng lặp hàm khi tệp tin card được gọi lặp lại trong vòng lặp foreach
    if (typeof handleAddToCart !== 'function') {
        function handleAddToCart(product) {
            let currentCart = JSON.parse(localStorage.getItem('cart')) || [];
            let existingItemIndex = currentCart.findIndex(item => item.id === product.id);

            if (existingItemIndex !== -1) {
                currentCart[existingItemIndex].quantity += 1;
            } else {
                product.quantity = 1;
                product.selected = true;
                currentCart.push(product);
            }

            localStorage.setItem('cart', JSON.stringify(currentCart));
            
            // Đồng bộ cập nhật số lượng lên icon Header tức thì
            if(typeof updateCartBadge === 'function') updateCartBadge();

            alert(`Thành công! Đã thêm sản phẩm ${product.name} vào giỏ hàng.`);
        }
    }
</script>