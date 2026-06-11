<?php
// --- (Giữ nguyên phần xử lý dữ liệu PHP ở đầu file) ---
$id_sp = isset($product['id_san_pham']) ? $product['id_san_pham'] : (isset($product['id']) ? $product['id'] : 0);
$ten_sp = isset($product['ten_san_pham']) ? $product['ten_san_pham'] : (isset($product['ten_sp']) ? $product['ten_sp'] : (isset($product['name']) ? $product['name'] : 'Sản phẩm'));
$danh_muc = isset($product['ten_danh_muc']) ? $product['ten_danh_muc'] : (isset($product['category']) ? $product['category'] : 'Khác');
$gia_hien_tai = isset($product['gia_ban']) ? $product['gia_ban'] : (isset($product['gia_co_ban']) ? $product['gia_co_ban'] : (isset($product['gia']) ? $product['gia'] : (isset($product['price']) ? $product['price'] : 0)));
$gia_cu = isset($product['giam_gia']) ? $product['giam_gia'] : (isset($product['oldPrice']) ? $product['oldPrice'] : 0);
$formattedPrice = number_format($gia_hien_tai, 0, ',', '.') . ' đ';
$formattedOldPrice = $gia_cu > 0 ? number_format($gia_cu, 0, ',', '.') . ' đ' : '&nbsp;';
$tenAnh = isset($product['hinh_anh']) ? $product['hinh_anh'] : (isset($product['anh_sp']) ? $product['anh_sp'] : (isset($product['img']) ? $product['img'] : ''));

if (strpos($tenAnh, '/') !== false) {
    $imgPath = $tenAnh;
} else {
    $imgPath = !empty($tenAnh) ? '/LTWNC_LTWNC_WEBTMDT/assets/images/products/' . $tenAnh : '/LTWNC_LTWNC_WEBTMDT/assets/images/products/default.png';
}

$productJson = json_encode(['id' => $id_sp, 'name' => $ten_sp, 'price' => $gia_hien_tai, 'img' => $imgPath, 'category' => $danh_muc]);
$productJsonHtml = htmlspecialchars($productJson, ENT_QUOTES, 'UTF-8');
?>

<div class="col-md-6 col-lg-3 mb-4 wow fadeInUp product-card-wrapper" data-wow-delay="0.1s">
    <div class="card h-100 product-card-anim" style="background-color: #1a1a1a; border: 1px solid #2a2a2a; padding: 20px; transition: all 0.3s ease; border-radius: 12px; overflow: hidden; position: relative;">
        
        <div class="position-relative overflow-hidden product-image-wrapper" style="height: 220px; background-color: #111; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
            <img src="<?= $imgPath ?>" class="img-fluid product-image" style="max-height: 100%; object-fit: contain; padding: 15px; transition: transform 0.3s ease;" alt="<?= htmlspecialchars($ten_sp) ?>">
            
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center overlay-eye product-action-icons" style="background: rgba(0,0,0,0.6); opacity: 0; visibility: hidden; transition: all 0.3s ease;">
                <a href="/LTWNC_LTWNC_WEBTMDT/views/pages/ProductDetail.php?id=<?= $id_sp ?>" class="btn rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #222; color: #F28B00; border: 1px solid #F28B00;">
                    <i class="fa fa-eye fs-5"></i>
                </a>
            </div>
        </div>

        <div class="card-body text-center d-flex flex-column p-0 mt-3 product-details">
            <span class="text-white-50 small mb-1 product-category" style="font-size: 0.85rem;"><?= htmlspecialchars($danh_muc) ?></span>
            
            <a href="/LTWNC_LTWNC_WEBTMDT/views/pages/ProductDetail.php?id=<?= $id_sp ?>" class="card-title h6 fw-bold mb-2 text-white text-decoration-none d-block text-truncate product-title" title="<?= htmlspecialchars($ten_sp) ?>">
                <?= htmlspecialchars($ten_sp) ?>
            </a>
            
            <div class="d-flex justify-content-center align-items-center mb-0 mt-auto product-price-wrapper">
                <?php if ($gia_cu > 0): ?>
                    <del class="me-2 text-white-50 small product-price-old" style="font-size: 0.85rem;"><?= $formattedOldPrice ?></del>
                <?php endif; ?>
                <span style="color: #F28B00;" class="fs-5 fw-bold product-price-new"><?= $formattedPrice ?></span>
            </div>
            
            <div class="product-add-to-cart-wrapper" style="opacity: 0; visibility: hidden; height: 0; margin-top: 0; transition: all 0.3s ease;">
                <button onclick="handleAddToCartFromHome(<?= $productJsonHtml ?>)" 
                        class="btn btn-orange btn-add-to-cart w-100 rounded-pill py-2 fw-bold shadow-sm mt-3" 
                        style="background-color: #F28B00; color: #fff; border: none; font-size: 0.9rem; transform: translateY(20px); transition: all 0.3s ease;">
                    <i class="fas fa-cart-plus me-2"></i> Thêm Giỏ Hàng
                </button>
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