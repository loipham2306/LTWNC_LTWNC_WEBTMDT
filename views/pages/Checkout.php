
<?php
     // Debug: Bỏ comment dòng này để xem dữ liệu có trong session không

    $hasItems = !empty($_SESSION['checkout_items']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán - TramHieu</title>
    
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

    include __DIR__. '/../components/Header.php';
    include __DIR__. '/../components/PageHeader.php';
    ?>

    <div class="container-fluid py-5 checkout-wrapper">
        <div class="container py-5">
            
            <div id="empty-checkout" class="text-center py-5 checkout-box rounded wow fadeInUp" data-wow-delay="0.1s" style="display:<?= $hasItems ? 'none' : 'block' ?>;">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Empty" style="width: 100px; opacity: 0.5;" class="mb-4" />
                <h4 class="text-white-50 mb-3">Không có mặt hàng nào đang chờ thanh toán!</h4>
                <p class="text-muted mb-4">Vui lòng quay lại Giỏ hàng để lựa chọn các mặt hàng bạn muốn mua.</p>
                <a href="index.php?act=GioHang" class="btn btn-orange rounded-pill px-5 py-3 fw-bold">
                    <i class="fas fa-shopping-cart me-2"></i> Quay lại Giỏ Hàng
                </a>
            </div>

                <form id="checkout-form" style="display: <?= $hasItems ? 'block' : 'none' ?>;">
                    <div class="row g-5">
                    
                    <div class="col-md-12 col-lg-7">
                        <h3 class="mb-4 fw-bold text-uppercase text-orange">Thông Tin Nhận Hàng</h3>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Họ và tên người nhận <span class="text-danger">*</span></label>
                                <input type="text"
                                    name="fullName"
                                    id="fullName"
                                    class="form-control py-3 rounded"
                                    placeholder="Nhập đầy đủ họ và tên"
                                    value="<?= $_SESSION['user']['ho_ten'] ?? '' ?>"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel"
                                    name="phone"
                                    id="phone"
                                    class="form-control py-3 rounded"
                                    placeholder="Số điện thoại nhận hàng"
                                    value="<?= $_SESSION['user']['so_dien_thoai'] ?? '' ?>"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Địa chỉ Email (Nếu có)</label>
                                <input type="email"
                                    name="email"
                                    id="email"
                                    class="form-control py-3 rounded"
                                    placeholder="example@gmail.com"
                                    value="<?= $_SESSION['user']['email'] ?? '' ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Địa chỉ giao hàng chính xác <span class="text-danger">*</span></label>
                                <input type="text"
                                    name="address"
                                    id="address"
                                    class="form-control py-3 rounded"
                                    placeholder="Số nhà, tên đường, phường/xã..."
                                    value="<?= $_SESSION['user']['dia_chi'] ?? '' ?>"
                                    required>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label fw-bold">Ghi chú đơn hàng (Tùy chọn)</label>
                                <textarea name="note"
                                        id="note"
                                        class="form-control p-3 rounded"
                                        rows="4"
                                        placeholder="Ghi chú thêm về đơn hàng..."><?= $_SESSION['user']['ghi_chu'] ?? '' ?></textarea>
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
                                        <?php 
                                        $total = 0;
                                        foreach ($_SESSION['checkout_items'] as $item): 
                                            $subTotal = (float)$item['gia'] * (int)$item['so_luong'];
                                            $total += $subTotal;
                                        ?>
                                            <tr>
                                                <td class="ps-0 text-white-50">
                                                    <?= htmlspecialchars($item['ten_san_pham']) ?> 
                                                    <strong class="text-white">x <?= $item['so_luong'] ?></strong>
                                                </td>
                                                <td class="text-end pe-0 fw-bold text-white">
                                                    <?= number_format($subTotal, 0, ',', '.') ?> đ
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        
                                        <tr class="border-bottom">
                                            <td class="ps-0 text-white-50">Phí vận chuyển</td>
                                            <td class="text-end pe-0 text-success fw-bold">Miễn phí</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="pt-3">
                                                <label class="form-label fw-bold text-white">Mã giảm giá</label>
                                                <div class="input-group">
                                                    <input type="text" name="voucher" id="voucher"
                                                        class="form-control"
                                                        placeholder="Nhập mã voucher">
                                                    <button type="button" class="btn btn-orange" id="applyVoucher">
                                                        Áp dụng
                                                    </button>
                                                </div>
                                                <small id="voucherMsg" class="text-warning"></small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0 fs-5 fw-bold text-white">Tổng tiền thanh toán</td>
                                            <td class="text-end pe-0 fs-4 fw-bold text-orange">
                                                <?= number_format($total, 0, ',', '.') ?> đ
                                            </td>
                                        </tr>
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

    <?php include __DIR__ . '/../components/Footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    
    <script>
        new WOW().init();
        let baseTotal = <?= $total ?>;
        let discount = 0;

        const voucherInput = document.getElementById('voucher');
        const voucherMsg = document.getElementById('voucherMsg');
        const totalBox = document.querySelector('.text-orange.fs-4');

        document.getElementById('applyVoucher').addEventListener('click', () => {
            const code = voucherInput.value.trim();

            // demo voucher (sau này đổi qua DB)
            if (code === 'SALE10') {
                discount = baseTotal * 0.1;
                voucherMsg.innerText = 'Áp dụng thành công -10%';
                voucherMsg.style.color = 'lightgreen';
            }
            else if (code === 'FREESHIP') {
                discount = 50000;
                voucherMsg.innerText = 'Giảm 50.000đ';
                voucherMsg.style.color = 'lightgreen';
            }
            else {
                discount = 0;
                voucherMsg.innerText = 'Mã không hợp lệ';
                voucherMsg.style.color = 'orange';
            }

            updateTotal();
        });

        function updateTotal() {
            let finalTotal = baseTotal - discount;
            if (finalTotal < 0) finalTotal = 0;

            totalBox.innerHTML = finalTotal.toLocaleString('vi-VN') + ' đ';
        }                                    
        document.addEventListener('DOMContentLoaded', () => {

            const formState = document.getElementById('checkout-form');

            // ❌ KHÔNG render lại giỏ hàng bằng JS nữa (đã có PHP render)

            // 1. Submit form
            formState.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('index.php?act=XuLyDatHang', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Đặt hàng thành công!');
                        window.location.href = 'index.php?act=LichSuDonHang';
                    } else {
                        alert('Có lỗi: ' + data.message);
                    }
                });
            });

        });
    </script>
</body>
</html>