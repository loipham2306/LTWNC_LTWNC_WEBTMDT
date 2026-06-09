<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán - LuLoShop</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel="stylesheet">

    <style>
        body { background-color: #111; color: #fff; }
        
        .checkout-wrapper { background-color: #1a1a1a; min-height: 50vh; }
        .checkout-box { background-color: #222; border: 1px solid #333; }
        
        /* Chỉnh form input tối màu */
        .form-control { background-color: #111 !important; border: 1px solid #444 !important; color: #fff !important; }
        .form-control:focus { border-color: #F28B00 !important; box-shadow: 0 0 0 0.25rem rgba(242, 139, 0, 0.25) !important; }
        .form-control::placeholder { color: #666 !important; }
        
        .form-label { color: #e0e0e0 !important; }
        
        /* Bảng tóm tắt */
        .summary-table td, .summary-table th { background-color: transparent; border-color: #333; color: #ccc; }
        .summary-table .text-dark { color: #fff !important; }
        
        /* Custom Radio */
        .form-check-input { background-color: #111; border-color: #444; }
        .form-check-input:checked { background-color: #F28B00; border-color: #F28B00; }
        
        .btn-orange { background-color: #F28B00 !important; color: #fff !important; border: none; }
        .btn-orange:hover { background-color: #d67a00 !important; }
        .text-orange { color: #F28B00 !important; }
    </style>
</head>
<body>

    <?php
    $pageTitle = "Thanh Toán Đơn Hàng";
    $pageBreadcrumb = "Thanh Toán";

    include '../components/Header.php';
    include '../components/PageHeader.php';
    ?>

    <div class="container-fluid py-5 checkout-wrapper">
        <div class="container py-5">
            
            <div id="empty-checkout" class="text-center py-5 checkout-box rounded wow fadeInUp" data-wow-delay="0.1s" style="display: none;">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Empty" style="width: 100px; opacity: 0.5;" class="mb-4" />
                <h4 class="text-white-50 mb-3">Không có mặt hàng nào đang chờ thanh toán!</h4>
                <p class="text-muted mb-4">Vui lòng quay lại Giỏ hàng để lựa chọn các mặt hàng bạn muốn mua.</p>
                <a href="/LTWNC_BAN_HANG/views/pages/Cart.php" class="btn btn-orange rounded-pill px-5 py-3 fw-bold">
                    <i class="fas fa-shopping-cart me-2"></i> Quay lại Giỏ Hàng
                </a>
            </div>

            <form id="checkout-form" class="wow fadeInUp" data-wow-delay="0.2s" style="display: none;">
                <div class="row g-5">
                    
                    <div class="col-md-12 col-lg-7">
                        <h3 class="mb-4 fw-bold text-uppercase text-orange">Thông Tin Nhận Hàng</h3>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Họ và tên người nhận <span class="text-danger">*</span></label>
                                <input type="text" id="fullName" class="form-control py-3 rounded" placeholder="Nhập đầy đủ họ và tên" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" id="phone" class="form-control py-3 rounded" placeholder="Số điện thoại nhận hàng" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Địa chỉ Email (Nếu có)</label>
                                <input type="email" id="email" class="form-control py-3 rounded" placeholder="example@gmail.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Địa chỉ giao hàng chính xác <span class="text-danger">*</span></label>
                                <input type="text" id="address" class="form-control py-3 rounded" placeholder="Số nhà, tên đường, phường/xã..." required>
                            </div>
                            <div class="col-12 mt-4">
                                <label class="form-label fw-bold">Ghi chú đơn hàng (Tùy chọn)</label>
                                <textarea id="note" class="form-control p-3 rounded" rows="4" placeholder="Ghi chú thêm về đơn hàng..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-5">
                        <div class="checkout-box rounded p-4 shadow-lg">
                            <h3 class="mb-4 fw-bold text-uppercase text-center text-white">Đơn Hàng Của Bạn</h3>
                            
                            <div class="table-responsive mb-4">
                                <table class="table summary-table align-middle">
                                    <thead>
                                        <tr class="border-bottom">
                                            <th class="ps-0 fw-bold text-white fs-5">Sản phẩm</th>
                                            <th class="text-end pe-0 fw-bold text-white fs-5">Tạm tính</th>
                                        </tr>
                                    </thead>
                                    <tbody id="checkout-items-list">
                                        </tbody>
                                </table>
                            </div>

                            <h4 class="mb-3 fw-bold text-uppercase mt-4 text-white">Phương Thức Thanh Toán</h4>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="paymentCOD" value="cod" checked style="cursor: pointer; width: 18px; height: 18px;">
                                <label class="form-check-label fw-bold text-white-50 ms-2" for="paymentCOD" style="cursor: pointer;">Thanh toán khi nhận hàng (COD)</label>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="paymentBank" value="bank" style="cursor: pointer; width: 18px; height: 18px;">
                                <label class="form-check-label fw-bold text-white-50 ms-2" for="paymentBank" style="cursor: pointer;">Chuyển khoản qua ngân hàng (Mã QR)</label>
                            </div>

                            <button type="submit" class="btn btn-orange w-100 py-3 text-uppercase fw-bold rounded-pill fs-5 shadow-sm mt-2">
                                Xác Nhận Đặt Hàng
                            </button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <?php include '../components/Footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    
    <script>
        new WOW().init();

        document.addEventListener('DOMContentLoaded', () => {
            // 1. Lấy dữ liệu sản phẩm được gắp từ Giỏ hàng sang
            const checkoutItems = JSON.parse(sessionStorage.getItem('checkoutItems')) || [];
            
            const emptyState = document.getElementById('empty-checkout');
            const formState = document.getElementById('checkout-form');
            const itemsList = document.getElementById('checkout-items-list');

            // 2. Xử lý hiển thị
            if (checkoutItems.length === 0) {
                emptyState.style.display = 'block';
                formState.style.display = 'none';
                return;
            } else {
                emptyState.style.display = 'none';
                formState.style.display = 'block';
            }

            // 3. Render các mặt hàng và tính tiền
            let html = '';
            let total = 0;

            checkoutItems.forEach(item => {
                const subTotal = item.price * item.quantity;
                total += subTotal;
                
                html += `
                    <tr>
                        <td class="ps-0 text-white-50">
                            ${item.name} <strong class="text-white">x ${item.quantity}</strong>
                        </td>
                        <td class="text-end pe-0 fw-bold text-white">
                            ${subTotal.toLocaleString('vi-VN')} đ
                        </td>
                    </tr>
                `;
            });

            html += `
                <tr class="border-bottom">
                    <td class="ps-0 text-white-50">Phí vận chuyển</td>
                    <td class="text-end pe-0 text-success fw-bold">Miễn phí</td>
                </tr>
                <tr>
                    <td class="ps-0 fs-5 fw-bold text-white">Tổng tiền thanh toán</td>
                    <td class="text-end pe-0 fs-4 fw-bold text-orange">
                        ${total.toLocaleString('vi-VN')} đ
                    </td>
                </tr>
            `;

            itemsList.innerHTML = html;

            // 4. Xử lý khi Submit form
            formState.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const fullName = document.getElementById('fullName').value;
                
                // Hiển thị thông báo thành công
                alert(`🎉 Đặt hàng thành công!\n👤 Khách hàng: ${fullName}\n💳 Tổng tiền: ${total.toLocaleString('vi-VN')} đ`);
                
                // (Tùy chọn) Xóa các món đã mua khỏi Giỏ hàng gốc trong localStorage
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                cart = cart.filter(cartItem => !checkoutItems.find(checkoutItem => checkoutItem.id === cartItem.id));
                localStorage.setItem('cart', JSON.stringify(cart));
                
                // Xóa bộ nhớ tạm
                sessionStorage.removeItem('checkoutItems');
                
                // Đẩy người dùng về trang chủ
                window.location.href = '/LTWNC_BAN_HANG/index.php';
            });
        });
    </script>
</body>
</html>