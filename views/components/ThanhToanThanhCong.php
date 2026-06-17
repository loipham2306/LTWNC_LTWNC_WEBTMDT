<?php
/** @var array $donHang */ 
?>
<style>
body {
    background: radial-gradient(circle at top, #1a1a1a, #0d0d0d);
    color: #fff;
    margin: 0;
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
}

/* CENTER WRAPPER */
.thank-you-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

/* CARD */
.success-card {
    background: linear-gradient(145deg, #1c1c1c, #151515);
    border: 1px solid #2a2a2a;
    border-radius: 18px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.6);
    padding: 28px;
    width: 100%;
    max-width: 520px;
    text-align: center;
    animation: fadeUp 0.4s ease;
}

/* ICON */
.success-card i {
    font-size: 56px;
    color: #28d17c;
    filter: drop-shadow(0 0 12px rgba(40,209,124,0.4));
}

/* TITLE */
.success-card h2 {
    font-size: 24px;
    font-weight: 700;
    margin: 12px 0 8px;
}

/* ORDER ID */
.success-card strong {
    color: #F28B00;
}

/* QR SECTION */
#qr-section {
    margin-top: 18px;
    padding: 16px;
    background: #0f0f0f;
    border: 1px solid #2c2c2c;
    border-radius: 14px;
}

#qr-section img {
    max-width: 220px;
    border-radius: 12px;
    border: 2px solid #333;
    transition: transform 0.25s ease;
}

#qr-section img:hover {
    transform: scale(1.05);
}

/* BANK INFO */
.bank-info-box {
    margin-top: 12px;
    padding: 12px;
    background: #0b0b0b;
    border-left: 4px solid #F28B00;
    border-radius: 10px;
    font-size: 14px;
    color: #ccc;
    text-align: left;
}
.mt-4{
    margin-top: 10px;
}
.mt-4 a {
    text-decoration: none;
    display: inline-block;
    color: white;
}
/* BUTTON GROUP FIX (QUAN TRỌNG - FIX LỖI ĐÈ NHAU) */
.success-card .mt-4 {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

/* BUTTONS */
.btn-primary {
    background: #F28B00;
    border: none;
    font-weight: 600;
    padding: 10px 18px;
    border-radius: 10px;
    transition: 0.2s;
}

.btn-primary:hover {
    background: #d97700;
    transform: translateY(-1px);
}

.btn-outline-secondary {
    border: 1px solid #444;
    color: #ccc;
    padding: 10px 18px;
    border-radius: 10px;
    background: transparent;
    transition: 0.2s;
}

.btn-outline-secondary:hover {
    background: #222;
    color: #fff;
}

/* COD ALERT */
.alert-success {
    background: rgba(40, 209, 124, 0.1);
    border: 1px solid rgba(40, 209, 124, 0.25);
    color: #28d17c;
    border-radius: 10px;
    margin-top: 15px;
}

/* ANIMATION */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(16px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
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
                    <a href="index.php?act=UserProfile" class="btn btn-outline-secondary px-4">Xem đơn hàng</a>
                </div>
            </div>
        </div>
    </div>
</div>