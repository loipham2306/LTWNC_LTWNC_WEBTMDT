<?php
// Đảm bảo không bị lỗi nếu sản phẩm không có giá cũ
$oldPrice = !empty($product['oldPrice']) ? number_format($product['oldPrice'], 0, ',', '.') . 'đ' : '&nbsp;';
// Format giá tiền sang chuẩn Việt Nam
$currentPrice = number_format($product['price'], 0, ',', '.') . 'đ';
?>

<style>
    /* Chuyển đổi logic isHovered của React sang CSS thuần */
    .product-card-custom {
        background-color: #1a1a1a;
        border: 1px solid #333;
        transition: all 0.3s ease;
        position: relative;
    }
    
    /* Khi rê chuột vào Card */
    .product-card-custom:hover {
        border-color: #F28B00;
        transform: translateY(-10px);
        box-shadow: 0 10px 25px rgba(242, 139, 0, 0.2);
    }

    /* Hiệu ứng phóng to Icon */
    .product-card-custom .product-icon {
        color: #F28B00;
        transition: transform 0.3s ease;
    }
    .product-card-custom:hover .product-icon {
        transform: scale(1.1);
    }

    /* Hiệu ứng đổi màu Nút bấm */
    .product-card-custom .btn-add-cart {
        background-color: #F28B00;
        transition: background-color 0.3s;
    }
    .product-card-custom:hover .btn-add-cart {
        background-color: #ff9d1a;
    }
</style>

<div class="col-md-6 col-lg-4 col-xl-3 mb-4 wow fadeInUp" data-wow-delay="<?= isset($delay) ? $delay : '0.1s' ?>">
    <div class="card h-100 rounded product-card-custom">
        
        <div class="position-absolute top-0 start-0 px-3 py-1 rounded-end mt-3 fw-bold shadow-sm" style="background-color: #F28B00; color: #fff; z-index: 2;">
            Hot
        </div>
        
        <div class="d-flex justify-content-center align-items-center" style="height: 220px; border-bottom: 1px solid #333;">
            <i class="fas <?= !empty($product['imgIcon']) ? $product['imgIcon'] : 'fa-box' ?> fa-6x product-icon"></i>
        </div>

        <div class="card-body text-center p-4 d-flex flex-column">
            <h6 class="text-white mb-3 flex-grow-1" style="min-height: 45px; font-size: 1.1rem;">
                <?= htmlspecialchars($product['name']) ?>
            </h6>
            
            <div class="d-flex justify-content-center mb-3">
                <i class="fas fa-star" style="color: #F28B00;"></i>
                <i class="fas fa-star" style="color: #F28B00;"></i>
                <i class="fas fa-star" style="color: #F28B00;"></i>
                <i class="fas fa-star" style="color: #F28B00;"></i>
                <i class="fas fa-star" style="color: #F28B00;"></i>
            </div>

            <h5 class="fw-bold mb-1" style="color: #e0e0e0;"><?= $currentPrice ?></h5>
            <del class="small mb-4 d-block" style="color: #666;"><?= $oldPrice ?></del>

            <?php 
                // Mã hóa mảng product thành chuỗi JSON để truyền vào Javascript
                $productJson = htmlspecialchars(json_encode($product), ENT_QUOTES, 'UTF-8');
            ?>
            <button type="button" onclick="handleAddToCart(<?= $productJson ?>)" class="btn w-100 rounded-pill fw-bold text-white mt-auto btn-add-cart">
                <i class="fas fa-shopping-bag me-2"></i> Thêm Vào Giỏ
            </button>
        </div>

    </div>
</div>

<script>
    if (typeof handleAddToCart !== 'function') {
        function handleAddToCart(product) {
            // Lấy giỏ hàng hiện tại từ localStorage
            let currentCart = JSON.parse(localStorage.getItem('cart')) || [];
            
            // Kiểm tra sản phẩm đã có trong giỏ chưa
            let existingItemIndex = currentCart.findIndex(item => item.id === product.id);

            if (existingItemIndex !== -1) {
                // Nếu có rồi thì tăng số lượng
                currentCart[existingItemIndex].quantity += 1;
            } else {
                // Nếu chưa có thì thêm mới
                product.quantity = 1;
                product.selected = true;
                currentCart.push(product);
            }

            // Lưu lại vào localStorage
            localStorage.setItem('cart', JSON.stringify(currentCart));
            
            // Cập nhật số lượng hiển thị trên icon giỏ hàng ở Header (nếu có)
            updateCartBadge();

            // Hiển thị thông báo (Dùng alert mặc định hoặc bạn có thể thay bằng thư viện Toast)
            alert(`Thành công! Đã thêm "${product.name}" vào giỏ hàng.`);
        }

        // Hàm cập nhật số lượng trên icon Header
        function updateCartBadge() {
            let currentCart = JSON.parse(localStorage.getItem('cart')) || [];
            let totalItems = currentCart.reduce((total, item) => total + item.quantity, 0);
            
            // Tìm tất cả các badge giỏ hàng và cập nhật số (vì Header có thể có icon mobile/desktop)
            let badges = document.querySelectorAll('.badge.bg-danger');
            badges.forEach(badge => {
                badge.innerText = totalItems;
            });
        }

        // Tự động cập nhật số lượng lúc trang vừa load xong
        document.addEventListener('DOMContentLoaded', updateCartBadge);
    }
</script>