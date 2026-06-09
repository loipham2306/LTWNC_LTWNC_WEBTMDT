<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - LuLoShop</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">

    <style>
        /* Reset nền tảng web sang màu Đen eSports */
        body { background-color: #111; color: #fff; margin: 0; padding: 0; overflow-x: hidden; }

        /* CSS của trang Giỏ Hàng */
        .cart-wrapper { background-color: #1a1a1a; min-height: 50vh; }
        .table-dark-custom { color: #fff; border-color: #333; }
        .table-dark-custom thead th { background-color: #222; border-bottom: 2px solid #F28B00; color: #F28B00; text-transform: uppercase; }
        .table-dark-custom tbody td { background-color: #1a1a1a; border-bottom: 1px solid #333; vertical-align: middle; }
        
        .btn-qty { background: #333; border: 1px solid #444; color: #fff; transition: 0.3s; }
        .btn-qty:hover { background: #F28B00; color: #fff; border-color: #F28B00; }
        .input-qty { background: transparent; color: #fff; border: none; font-weight: bold; text-align: center; width: 40px; }
        
        .custom-checkbox { width: 22px; height: 22px; cursor: pointer; accent-color: #F28B00; }
        .btn-orange { background-color: #F28B00 !important; color: #fff !important; border: none; }
        .btn-orange:hover { background-color: #d67a00 !important; }
        .control-bar { background-color: #222; border: 1px solid #333; }
    </style>
</head>
<body>

    <?php
    // Cấu hình thông tin cho PageHeader
    $pageTitle = "Giỏ Hàng Của Bạn";
    $pageBreadcrumb = "Giỏ Hàng";

    // Gọi Component Header & PageHeader
    include '../components/Header.php';
    include '../components/PageHeader.php';
    ?>

    <div class="container-fluid py-5 cart-wrapper">
        <div class="container py-5">
            
            <div id="empty-state" class="text-center py-5" style="display: none;">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Empty Cart" style="width: 120px; opacity: 0.5;" class="mb-4"/>
                <h4 class="text-white-50 mb-4">Giỏ hàng của bạn đang trống!</h4>
                <a href="/LTWNC_BAN_HANG/views/pages/shop.php" class="btn btn-orange rounded-pill px-5 py-3 fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> Quay lại Cửa Hàng
                </a>
            </div>

            <div id="cart-content" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-dark-custom text-center align-middle wow fadeInUp" data-wow-delay="0.1s">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 5%;">Chọn</th>
                                <th scope="col" style="width: 15%;">Hình Ảnh</th>
                                <th scope="col" class="text-start" style="width: 25%;">Tên Sản Phẩm</th>
                                <th scope="col" style="width: 15%;">Đơn Giá</th>
                                <th scope="col" style="width: 15%;">Số Lượng</th>
                                <th scope="col" style="width: 15%;">Thành Tiền</th>
                                <th scope="col" style="width: 10%;">Xóa</th>
                            </tr>
                        </thead>
                        <tbody id="cart-tbody">
                            </tbody>
                    </table>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 p-4 rounded control-bar wow fadeInUp" data-wow-delay="0.3s">
                    <div class="form-check d-flex align-items-center mb-3 mb-md-0">
                        <input class="form-check-input custom-checkbox me-3" type="checkbox" id="selectAllBottom" onchange="toggleSelectAll(this)">
                        <label class="form-check-label fw-bold text-white fs-5" for="selectAllBottom" style="cursor: pointer;" id="selectCountLabel">
                            Chọn tất cả (0)
                        </label>
                    </div>
                    
                    <div class="d-flex align-items-center gap-3">
                        <button id="deleteBtn" class="btn btn-outline-danger px-4 py-3 fw-bold rounded-pill" onclick="handleRemoveSelected()">
                            <i class="fa fa-trash me-2"></i> Xóa Đã Chọn
                        </button>
                        <button id="checkoutBtn" class="btn btn-orange px-5 py-3 text-uppercase fw-bold rounded-pill" onclick="handleCheckout()">
                            Thanh Toán
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include '../components/Footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        // Kích hoạt hiệu ứng WOW
        new WOW().init();

        // LOGIC GIỎ HÀNG
        let cartItems = JSON.parse(localStorage.getItem('cart')) || [];

        function saveCart() {
            localStorage.setItem('cart', JSON.stringify(cartItems));
            renderCart();
            if (typeof updateCartBadge === 'function') updateCartBadge();
        }

        function renderCart() {
            const tbody = document.getElementById('cart-tbody');
            const emptyState = document.getElementById('empty-state');
            const cartContent = document.getElementById('cart-content');

            if (cartItems.length === 0) {
                emptyState.style.display = 'block';
                cartContent.style.display = 'none';
                return;
            }

            emptyState.style.display = 'none';
            cartContent.style.display = 'block';

            let html = '';
            let selectedCount = 0;
            let total = 0;

            cartItems.forEach(item => {
                if (item.selected) {
                    selectedCount++;
                    total += (item.price * item.quantity);
                }

                const rowBg = item.selected ? 'background-color: #2a2a2a;' : '';

                html += `
                    <tr style="${rowBg}">
                        <td>
                            <input class="form-check-input custom-checkbox" type="checkbox" 
                                ${item.selected ? 'checked' : ''} 
                                onchange="toggleSelection(${item.id})">
                        </td>
                        <td>
                            <img src="${item.img || '/LTWNC_BAN_HANG/assets/images/img/default.png'}" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;" alt="${item.name}">
                        </td>
                        <td class="text-start">
                            <p class="mb-0 fw-bold text-white">${item.name}</p>
                        </td>
                        <td>
                            <p class="mb-0 text-white-50">${item.price.toLocaleString('vi-VN')} đ</p>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <button onclick="handleQuantity(${item.id}, 'minus')" class="btn btn-sm rounded-circle btn-qty"><i class="fa fa-minus"></i></button>
                                <input type="text" class="input-qty mx-2" value="${item.quantity}" readonly>
                                <button onclick="handleQuantity(${item.id}, 'plus')" class="btn btn-sm rounded-circle btn-qty"><i class="fa fa-plus"></i></button>
                            </div>
                        </td>
                        <td>
                            <p class="mb-0 fw-bold" style="color: #F28B00;">${(item.price * item.quantity).toLocaleString('vi-VN')} đ</p>
                        </td>
                        <td>
                            <button onclick="handleRemove(${item.id})" class="btn btn-sm text-danger bg-transparent border-0 fs-5">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;

            document.getElementById('selectCountLabel').innerText = `Chọn tất cả (${cartItems.length})`;
            document.getElementById('selectAllBottom').checked = (selectedCount === cartItems.length);

            const checkoutBtn = document.getElementById('checkoutBtn');
            const deleteBtn = document.getElementById('deleteBtn');

            if (selectedCount > 0) {
                checkoutBtn.className = 'btn btn-orange px-5 py-3 text-uppercase fw-bold rounded-pill';
                checkoutBtn.innerHTML = `Thanh Toán (${total.toLocaleString('vi-VN')} đ)`;
                deleteBtn.disabled = false;
            } else {
                checkoutBtn.className = 'btn btn-secondary px-5 py-3 text-uppercase fw-bold rounded-pill disabled';
                checkoutBtn.innerHTML = `Thanh Toán`;
                deleteBtn.disabled = true;
            }
        }

        function toggleSelection(id) {
            cartItems = cartItems.map(item => item.id === id ? { ...item, selected: !item.selected } : item);
            saveCart();
        }

        function toggleSelectAll(el) {
            const isChecked = el.checked;
            cartItems = cartItems.map(item => ({ ...item, selected: isChecked }));
            saveCart();
        }

        function handleRemoveSelected() {
            if (confirm('Bạn có chắc muốn xóa các sản phẩm đã chọn?')) {
                cartItems = cartItems.filter(item => !item.selected);
                saveCart();
            }
        }

        function handleRemove(id) {
            cartItems = cartItems.filter(item => item.id !== id);
            saveCart();
        }

        function handleQuantity(id, type) {
            cartItems = cartItems.map(item => {
                if (item.id === id) {
                    let newQuantity = item.quantity;
                    if (type === 'plus') newQuantity += 1;
                    if (type === 'minus' && newQuantity > 1) newQuantity -= 1;
                    return { ...item, quantity: newQuantity };
                }
                return item;
            });
            saveCart();
        }

        function handleCheckout() {
            const selectedItems = cartItems.filter(item => item.selected);
            if (selectedItems.length === 0) return;
            sessionStorage.setItem('checkoutItems', JSON.stringify(selectedItems));
            window.location.href = '/LTWNC_BAN_HANG/views/pages/checkout.php';
        }

        document.addEventListener('DOMContentLoaded', renderCart);
    </script>
</body>
</html>