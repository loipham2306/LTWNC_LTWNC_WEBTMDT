
<?php
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

                <form id="formState" style="display: <?= $hasItems ? 'block' : 'none' ?>;">
                    <div id="qr-payment-area" class="mt-4 p-3 border border-warning rounded text-center" style="display: none;">
                        <h5 class="text-white">Quét mã để thanh toán</h5>
                        <img id="qr-code-img" src="" alt="QR Code" class="img-fluid" style="max-width: 250px;">
                        <p class="mt-2">Đơn hàng: <span id="order-id-label" class="text-orange fw-bold"></span></p>
                        <p class="text-white-50 small">Vui lòng ghi nội dung chuyển khoản chính xác.</p>
                    </div>
                    <div class="row g-5">
                    
                    <div class="col-md-12 col-lg-7">
                        <h3 class="mb-4 fw-bold text-uppercase text-orange">Thông Tin Nhận Hàng</h3>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Họ và tên người nhận <span class="text-danger">*</span></label>
                                <input type="text"
                                    name="ten_nguoi_nhan"
                                    id="fullName"
                                    class="form-control py-3 rounded"
                                    placeholder="Nhập đầy đủ họ và tên"
                                    value="<?= htmlspecialchars(trim(($_SESSION['user']['ho_ten_dem'] ?? '') . ' ' . ($_SESSION['user']['ten'] ?? ''))) ?>"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel"
                                    name="sdt_nguoi_nhan"
                                    id="phone"
                                    class="form-control py-3 rounded"
                                    placeholder="Số điện thoại nhận hàng"
                                    value="<?= htmlspecialchars($_SESSION['user']['so_dien_thoai'] ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Địa chỉ Email</label>
                                <input type="email"
                                    name="email"
                                    id="email"
                                    class="form-control py-3 rounded"
                                    placeholder="example@gmail.com"
                                    value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Địa chỉ giao hàng chính xác <span class="text-danger">*</span></label>
                                <input type="text"
                                    name="dia_chi_giao_hang"
                                    id="address"
                                    class="form-control py-3 rounded"
                                    placeholder="Số nhà, tên đường, phường/xã..."
                                    value="<?= htmlspecialchars($_SESSION['user']['dia_chi'] ?? '') ?>"
                                    required>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label fw-bold">Ghi chú đơn hàng (Tùy chọn)</label>
                                <textarea name="ghi_chu"
                                        id="note"
                                        class="form-control p-3 rounded"
                                        rows="4"
                                        placeholder="Ghi chú thêm về đơn hàng..."></textarea>
                            </div>
                            
                            <input type="hidden" name="id_voucher" id="id_voucher_hidden" value="">
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
                                            <td class="text-end pe-0 fs-4 fw-bold text-orange" id="displayTotal">
                                                <?= number_format($total, 0, ',', '.') ?> đ
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div id="payment-method-container">
                                <h4 class="mb-3 fw-bold text-uppercase mt-4 text-white">Phương Thức Thanh Toán</h4>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="phuong_thuc_thanh_toan" id="paymentCOD" value="cod" checked>
                                    <label class="form-check-label" for="paymentCOD">Thanh toán khi nhận hàng (COD)</label>
                                </div>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="radio" name="phuong_thuc_thanh_toan" id="paymentBank" value="bank">
                                    <label class="form-check-label" for="paymentBank">Chuyển khoản qua ngân hàng (Mã QR)</label>
                                </div>
                            </div>             
                            <button type="submit" class="btn btn-orange w-100 py-3 text-uppercase fw-bold rounded-pill fs-5 shadow-sm mt-2">
                                Xác Nhận Đặt Hàng
                            </button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
        <div class="modal fade" id="voucherModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: #1a1a1a; color: #fff; border: 1px solid #333;">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title text-orange">Chọn Voucher của bạn</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                        <?php 
                        $danhSachVoucher = $danhSachVoucher ?? [];
                        if (empty($danhSachVoucher)) : ?>
                            <p class="text-white-50">Bạn chưa có voucher nào.</p>
                        <?php else : 
                            foreach ($danhSachVoucher as $vc) : 
                                $isUsed = ($vc['da_su_dung'] == 1);
                        ?>
                            <div class="p-3 mb-2 rounded <?= $isUsed ? 'bg-dark opacity-50' : 'bg-dark' ?>" 
                                style="border: 1px solid #444; cursor: <?= $isUsed ? 'not-allowed' : 'pointer' ?>"
                                onclick="<?= $isUsed ? '' : "selectVoucher(" . $vc['id_voucher'] . ", '" . $vc['ma_voucher'] . "')" ?>">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold text-warning"><?= htmlspecialchars($vc['ma_voucher']) ?></span>
                                    <small><?= $isUsed ? 'Đã dùng' : 'Có thể dùng' ?></small>
                                </div>
                                <div class="small">Giảm: <?= number_format($vc['gia_tri_giam']) ?><?= $vc['loai_giam_gia'] == 'percent' ? '%' : 'đ' ?></div>
                                <div class="small text-white-50">Đơn tối thiểu: <?= number_format($vc['don_toi_thieu']) ?>đ</div>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../components/Footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    
    <script>
        new WOW().init();
        const formState = document.getElementById('formState');
        let baseTotal = <?= $total ?>;
        let discount = 0;
        const vouchersData = <?= json_encode($danhSachVoucher ?? []) ?>;        
        const voucherInput = document.getElementById('voucher');
        const voucherMsg = document.getElementById('voucherMsg');
        const displayTotal = document.getElementById('displayTotal');
        // 1. Mở modal khi bấm nút Áp dụng
        document.getElementById('applyVoucher').addEventListener('click', function() {
            var myModal = new bootstrap.Modal(document.getElementById('voucherModal'));
            myModal.show();
        });

       function selectVoucher(idVoucher, maVoucher) {
                if (typeof vouchersData === 'undefined') {
            alert("Voucher chưa load");
            return;
        }

        if (typeof baseTotal === 'undefined') {
            alert("Không thể áp dụng voucher ở trang này");
            return;
        }                  
            // 1. Check data tồn tại trước
            if (!vouchersData || vouchersData.length === 0) {
                alert("Không có voucher khả dụng");
                return;
            }

            // 2. Find voucher
            const voucher = vouchersData.find(v => v.id_voucher == idVoucher);

            if (!voucher) {
                alert("Voucher không tồn tại");
                return;
            }

            // 3. Reset discount trước khi tính
            discount = 0;

            const minOrder = parseFloat(voucher.don_toi_thieu || 0);
            const value = parseFloat(voucher.gia_tri_giam || 0);

            // 4. Check điều kiện đơn tối thiểu
            if (baseTotal < minOrder) {
                alert('Đơn hàng chưa đủ điều kiện áp dụng mã này!');
                return;
            }

            // 5. Tính giảm giá
            if (voucher.loai_giam_gia === 'percent') {
                discount = (baseTotal * value) / 100;
            } else {
                discount = value;
            }

            if (isNaN(discount) || discount < 0) {
                discount = 0;
            }

            // 6. Update UI + sync backend
            document.getElementById('id_voucher_hidden').value = idVoucher;
            document.getElementById('voucher').value = maVoucher;

            voucherMsg.innerText =
                "Đã áp dụng: -" + Math.round(discount).toLocaleString('vi-VN') + " đ";

            voucherMsg.className = "text-success fw-bold";

            updateTotal();

            // 7. Close modal
            const modalElement = document.getElementById('voucherModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
        }
        function updateTotal() {
            let finalTotal = baseTotal - discount;
            if (finalTotal < 0) finalTotal = 0;

            displayTotal.innerHTML = finalTotal.toLocaleString('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).replace('₫', 'đ');

            // 🔥 FIX QUAN TRỌNG: cập nhật QR nếu đang hiển thị
            const qrArea = document.getElementById('qr-payment-area');
            if (qrArea.style.display !== 'none') {
                const orderId = document.getElementById('order-id-label').innerText;
                if (orderId) {
                    generateQRLink(orderId, finalTotal);
                }
            }
        }                                

        // 2. Hàm tạo link VietQR
        // Sửa lại hàm tạo link
        function generateQRLink(orderId, finalAmount) { // Thêm tham số finalAmount
            let bank = "MB";
            let account = "0388046213";
            let name = "NGUYEN MINH LUAN";
            let content = "DH" + orderId;
            
            document.getElementById('order-id-label').innerText = orderId;
            
            // Sử dụng finalAmount thay vì biến PHP cứng
            let url = `https://img.vietqr.io/image/${bank}-${account}-compact.png?amount=${finalAmount}&addInfo=${encodeURIComponent(content)}&accountName=${encodeURIComponent(name)}`;
            document.getElementById('qr-code-img').src = url;
        }

        // 3. Sửa lại phần xử lý Submit Form
        formState.addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = document.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = "Đang xử lý...";

            try {
                const formData = new FormData(this);
                const paymentMethod = document.querySelector('input[name="phuong_thuc_thanh_toan"]:checked').value;

                const res = await fetch('index.php?act=XuLyThanhToan', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (data.status !== 'success') {
                    alert(data.message || "Có lỗi xảy ra");
                    btn.disabled = false;
                    btn.innerHTML = "Xác Nhận Đặt Hàng";
                    return;
                }

                // ===== BANK =====
                if (paymentMethod === 'bank') {

                    document.getElementById('qr-payment-area').style.display = 'block';

                    const finalAmount = baseTotal - discount;

                    generateQRLink(data.id_don_hang, finalAmount);

                    document.querySelector('button[type="submit"]').style.display = 'none';
                    document.getElementById('payment-method-container').style.display = 'none';

                    // UX tốt hơn alert
                    voucherMsg.innerText = "Đơn hàng đã tạo. Vui lòng quét QR để thanh toán.";
                    voucherMsg.className = "text-success fw-bold";

                } else {
                    window.location.href = data.redirect;
                }

            } catch (err) {
                console.error(err);
                alert("Lỗi hệ thống, vui lòng thử lại");

                btn.disabled = false;
                btn.innerHTML = "Xác Nhận Đặt Hàng";
            }
        });
    </script>
</body>
</html>