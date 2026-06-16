<?php
/** @var array $donHang */ 
?>
<div class="container py-5 thank-you-container"> <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 text-center p-4 success-card"> <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                </div>
                
                <h2 class="mb-3">Đặt hàng thành công!</h2>
                <p class="text-muted">Cảm ơn bạn đã mua hàng. Mã đơn hàng của bạn là: <strong>DH<?= $donHang['id_don_hang'] ?></strong></p>

                <?php if ($donHang['phuong_thuc_thanh_toan'] == 'bank'): ?>
                <hr>
                <div id="qr-section">
                    <h5 class="mb-3">Thanh toán qua chuyển khoản ngân hàng</h5>
                    <p>Vui lòng quét mã QR dưới đây để hoàn tất thanh toán:</p>
                    
                    <img src="<?= DonHangModel::generateVietQR($donHang['id_don_hang'], $donHang['tong_tien'], "DH" . $donHang['id_don_hang']) ?>" 
                         alt="QR Code thanh toán" 
                         class="img-fluid mb-3" 
                         style="max-width: 250px;">
                    
                    <div class="bank-info-box">
                        <strong>Nội dung CK:</strong> <code class="user-select-all">DH<?= $donHang['id_don_hang'] ?></code><br>
                        <small>Số tiền: <?= number_format($donHang['tong_tien'], 0, ',', '.') ?> VNĐ</small>
                    </div>
                </div>
                <?php else: ?>
                    <div class="alert alert-success mt-3">Đơn hàng của bạn sẽ được gửi qua hình thức thanh toán khi nhận hàng (COD).</div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="index.php" class="btn btn-primary px-4">Tiếp tục mua sắm</a>
                    <a href="index.php?act=DonHang" class="btn btn-outline-secondary px-4">Xem đơn hàng</a>
                </div>
            </div>
        </div>
    </div>
</div>