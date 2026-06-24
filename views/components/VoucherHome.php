
<style>
    .voucher-status {
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    color: white;
}
.bg-success { background-color: #28a745; }
.bg-danger { background-color: #dc3545; }
.btn-disabled { 
    background-color: #6c757d !important; 
    cursor: not-allowed; 
}
</style>
<div class="container-fluid py-3 bg-dark border-top border-bottom border-secondary">
    <div class="container">

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="text-white fw-bold mb-0">
                🎁 Săn Voucher Hot
            </h5>
            <a href="index.php?act=VoucherHome" class="text-warning fw-bold text-decoration-none">
                Xem tất cả
            </a>
        </div>

        <div class="voucher-scroll">

            <?php if (!empty($danhSachVoucher)) : ?>
                
                <?php foreach ($danhSachVoucher as $vc) : ?>
                    
                    <div class="col-12">
                        <div class="voucher-card">

                            <div class="voucher-top">
                                <span class="voucher-code">
                                    🎟 <?= htmlspecialchars($vc['ma_voucher']) ?>
                                </span>

                               <span class="voucher-status <?= $vc['is_expired'] ? 'bg-danger' : 'bg-success' ?>">
                                    <?= $vc['is_expired'] ? 'Hết hạn' : 'Hoạt động' ?>
                                </span>
                            </div>

                            <div class="voucher-body">

                                <div class="voucher-discount">
                                    Giảm 
                                    <span>
                                        <?= $vc['loai_giam_gia'] == 'percent'
                                            ? $vc['gia_tri_giam'] . '%'
                                            : number_format($vc['gia_tri_giam']) . 'đ'
                                        ?>
                                    </span>
                                </div>

                                <div class="voucher-condition">
                                    🛒 Đơn tối thiểu: <?= number_format($vc['don_toi_thieu']) ?>đ
                                </div>

                                <div class="voucher-exp">
                                    ⏳ HSD: <?= $vc['ngay_het_han'] ?>
                                </div>

                            </div>

                           <button class="btn-copy <?= ($vc['da_luu'] || $vc['is_expired']) ? 'btn-disabled' : '' ?>" 
                                    onclick="layVoucher(this, <?= $vc['id_voucher'] ?>)"
                                    <?= ($vc['da_luu'] || $vc['is_expired']) ? 'disabled' : '' ?>>
                                <?= $vc['is_expired'] ? 'Đã hết hạn' : ($vc['da_luu'] ? 'Đã lưu' : 'Lấy mã') ?>
                            </button>

                        </div>
                    </div>

                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12">
                    <p class="text-white mb-0">Hiện chưa có voucher nào.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>