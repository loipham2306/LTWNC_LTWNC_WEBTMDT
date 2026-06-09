<?php
// Kiểm tra dữ liệu đầu vào để tránh lỗi Undefined index
$item = $item ?? []; 
?>
<div class="col-6 col-md-4 col-lg-3">
    <div class="product-card h-100 d-flex flex-column">
        <div class="product-img-wrapper">
            <?php if (!empty($item['badge'])): ?>
                <span class="product-badge"><?= htmlspecialchars($item['badge']) ?></span>
            <?php endif; ?>
            <img src="/LTWNC_LTWNC_WEBTMDT/assets/images/products/<?= ltrim($item['hinh_anh'] ?? '', '/') ?>"
                 class="product-img"
                 alt="<?= htmlspecialchars($item['ten_san_pham'] ?? 'Sản phẩm') ?>"
                 onerror="this.src='/LTWNC_LTWNC_WEBTMDT/assets/images/products/no-image.png';">
        </div>
        <div class="p-3 d-flex flex-column flex-grow-1">
            <h6 class="product-title"><?= htmlspecialchars($item['ten_san_pham'] ?? 'N/A') ?></h6>
            <div class="mt-auto">
                <div class="price-text"><?= number_format($item['gia_co_ban'] ?? 0, 0, ',', '.') ?> đ</div>
                <button class="btn btn-cart w-100">
                    <i class="fas fa-cart-plus me-2"></i> Thêm Vào Giỏ
                </button>
            </div>
        </div>
    </div>
</div>