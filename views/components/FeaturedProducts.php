<?php
// Mảng sản phẩm giả lập (Sau này bạn có thể thay bằng câu lệnh truy vấn từ Database)
$products = [
        [ 'id' => 1, 'name' => 'Smart Camera Pro Max', 'category' => 'Điện Tử', 'price' => 3350000, 'oldPrice' => 4110000, 'img' => '/LTWNC_BAN_HANG/assets/images/img/product-4.png' ],
        [ 'id' => 2, 'name' => 'Apple iPad Mini G2356', 'category' => 'Tablet', 'price' => 10500000, 'oldPrice' => 12500000, 'img' => '/LTWNC_BAN_HANG/assets/images/img/product-3.png' ],
        [ 'id' => 3, 'name' => 'Microphone Đa Hướng', 'category' => 'Phụ Kiện', 'price' => 850000, 'oldPrice' => 1000000, 'img' => '/LTWNC_BAN_HANG/assets/images/img/product-5.png' ],
        [ 'id' => 4, 'name' => 'Tai nghe Bluetooth 5.0', 'category' => 'Âm Thanh', 'price' => 1250000, 'oldPrice' => 1500000, 'img' => '/LTWNC_BAN_HANG/assets/images/img/product-6.png' ]
    ];

$tabs = ['Tất Cả', 'Quần Áo', 'Giày Dép', 'Phụ Kiện'];
?>

<div class="container-fluid py-5" style="background-color: #1a1a1a;">
    <div class="container py-5">
        
        <div class="d-flex justify-content-center flex-wrap mb-5 gap-3 wow fadeInDown" data-wow-delay="0.1s" id="product-tabs">
            <?php foreach ($tabs as $index => $tab): ?>
                <button
                    class="btn rounded-pill px-4 py-2 fw-bold tab-btn <?= $index === 0 ? 'text-white' : 'text-warning' ?>"
                    style="border: 1px solid #F28B00; background-color: <?= $index === 0 ? '#F28B00' : 'transparent' ?>; transition: all 0.3s ease;"
                    onclick="filterProducts('<?= $tab ?>', this)"
                >
                    <?= $tab ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="row g-4 justify-content-center" id="product-grid">
            <?php foreach ($products as $index => $product): ?>
                
                <?php 
                // Tính toán độ trễ: thẻ 1 (0s), thẻ 2 (0.1s), thẻ 3 (0.2s)...
                $delay = ($index * 0.1) . 's'; 
                ?>

                <div class="product-item-wrapper" data-category="<?= $product['category'] ?>" style="display: contents;">
                    <?php include 'FeaturedProductCard.php'; ?>
                </div>

            <?php endforeach; ?>
        </div>

        <div id="empty-state" class="text-center text-white mt-4" style="display: none;">
            <h5><i class="fas fa-box-open mb-3 fa-2x" style="color: #F28B00;"></i><br>Đang cập nhật sản phẩm...</h5>
        </div>

    </div>
</div>

<script>
    function filterProducts(category, btnElement) {
        // 1. Reset màu tất cả các nút Tabs
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => {
            btn.classList.remove('text-white');
            btn.classList.add('text-warning');
            btn.style.backgroundColor = 'transparent';
        });

        // Đổi màu cho nút vừa được bấm
        btnElement.classList.remove('text-warning');
        btnElement.classList.add('text-white');
        btnElement.style.backgroundColor = '#F28B00';

        // 2. Lọc sản phẩm
        const items = document.querySelectorAll('.product-item-wrapper');
        let hasVisibleItems = false; // Biến kiểm tra xem có SP nào thỏa mãn điều kiện lọc không

        items.forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            
            if (category === 'Tất Cả' || itemCategory === category) {
                // Hiển thị lại đúng cấu trúc ban đầu
                item.style.display = 'contents'; 
                hasVisibleItems = true;
            } else {
                // Ẩn đi
                item.style.display = 'none';
            }
        });

        // 3. Hiển thị dòng chữ "Đang cập nhật sản phẩm..." nếu mục đó trống
        const emptyState = document.getElementById('empty-state');
        if (hasVisibleItems) {
            emptyState.style.display = 'none';
        } else {
            emptyState.style.display = 'block';
        }
    }
</script>