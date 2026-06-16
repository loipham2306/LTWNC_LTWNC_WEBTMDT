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
?>

<div class="col-md-6 col-lg-3 mb-4 wow fadeInUp product-card-wrapper" data-wow-delay="0.1s">
    <div class="card h-100 product-card-anim" style="background-color: #1a1a1a; border: 1px solid #2a2a2a; padding: 20px; transition: all 0.3s ease; border-radius: 12px; overflow: hidden; position: relative;">
        
        <div class="position-relative overflow-hidden product-image-wrapper" style="height: 220px; background-color: #111; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
            <img src="<?= $imgPath ?>" class="img-fluid product-image" style="max-height: 100%; object-fit: contain; padding: 15px; transition: transform 0.3s ease;" alt="<?= htmlspecialchars($ten_sp) ?>">
            
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center img-overlay">
                <a href="index.php?act=ProductDetail&id=<?= htmlspecialchars($id) ?>" 
                    class="btn rounded-circle d-flex align-items-center justify-content-center" 
                    style="width: 50px; height: 50px; background-color: #1a1a1a; color: #F28B00; border: 1px solid #F28B00;">
                        <i class="fa fa-eye fs-5"></i>
                </a>
            </div>
        </div>

        <div class="card-body text-center d-flex flex-column p-4">
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

<style>
    /* CSS TẠO HIỆU ỨNG: KHI HOVER VÀO CARD THÌ TO RA VÀ HIỆN NÚT */
    .product-card-anim:hover {
        transform: scale(1.05) translateY(-5px) !important; /* Card to ra và bay lên */
        border-color: #F28B00 !important;
        box-shadow: 0 10px 20px rgba(242, 139, 0, 0.2) !important;
        background-color: #1a1a1a !important; /* Giữ nền dark eSports tối */
    }

    /* Hiệu ứng phóng to nhẹ ảnh sản phẩm */
    .product-card-anim:hover .product-image {
        transform: scale(1.1) !important;
    }

    /* Hiệu ứng hiện Nút "Mắt" và các icon hành động trên ảnh */
    .product-card-anim:hover .product-action-icons {
        opacity: 1 !important;
        visibility: visible !important;
    }

    /* ===== CSS KÍCH HOẠT HIỆN NÚT GIỎ HÀNG KHI HOVER ===== */
    .product-card-anim:hover .product-add-to-cart-wrapper {
        opacity: 1 !important;
        visibility: visible !important;
        height: auto !important; /* Card to ra về chiều cao */
        margin-top: 15px !important;
    }

    /* Hiệu ứng nút bay từ dưới lên */
    .product-card-anim:hover .btn-add-to-cart {
        transform: translateY(0) !important; /* Bay lên vị trí chuẩn */
    }
    /* ==================================================== */
</style>

<script>
    // Hàm xử lý bỏ giỏ hàng dùng chung cho trang chủ (nếu chưa định nghĩa)
    if (typeof handleAddToCartFromHome !== 'function') {
        function handleAddToCartFromHome(product) {
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
            if (typeof updateCartBadge === 'function') updateCartBadge();
            alert(`Thành công! Đã thêm ${product.name} vào giỏ hàng.`);
        }
    }
</script>