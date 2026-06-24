<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start(); 

$vouchers = $danhSachVoucher ?? [];

?>

<div class="container-fluid p-4" style="background-color: #111; min-height: 100vh; color: #fff;">
    <h3 class="text-white fw-bold mb-4">Quản Lý Voucher & Khuyến Mãi</h3>

    <div class="card border-0 rounded p-4" style="background-color: #1a1a1a;">
         <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show mb-3">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
                <div class="input-group" style="max-width: 400px;">
                    <span class="input-group-text bg-dark border-secondary text-muted">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control bg-dark border-secondary text-white" 
                        placeholder="Tìm theo mã voucher..." onkeyup="filterTable()">
                </div>
                
                <button onclick="document.getElementById('voucherModal').style.display='flex'" 
                        class="btn fw-bold text-white px-4 rounded-pill text-nowrap" 
                        style="background-color: #F28B00;">
                    <i class="fas fa-plus me-2"></i> Tạo Voucher
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr style="border-bottom: 2px solid #F28B00;">
                        <th class="py-3 px-3">Mã Voucher</th>
                        <th class="py-3">Loại / Mức Giảm</th>
                        <th class="py-3 text-center">Đơn tối thiểu</th>
                        <th class="py-3 text-center">Đã dùng</th>
                        <th class="py-3 text-center">Hết hạn</th>
                        <th class="py-3 text-center">Trạng Thái</th>
                        <th class="py-3 text-center">Thao Tác</th>
                    </tr>
                </thead>
               <tbody>
                    <?php foreach ($vouchers as $v): 
                        // 1. Tính toán thời gian
                        $now = time();
                        $ngay_het_han = strtotime($v['ngay_het_han']);
                        $is_expired = ($now > $ngay_het_han);
                        
                        // 2. Logic trạng thái (Chỉ tính một lần duy nhất)
                        if ($is_expired) {
                            $status_text = 'Đã hết hạn';
                            $status_class = 'bg-danger';
                        } else {
                            $status_text = ($v['trang_thai'] == 1) ? 'Đang chạy' : 'Ngừng';
                            $status_class = ($v['trang_thai'] == 1) ? 'bg-success' : 'bg-secondary';
                        }

                        // 3. Định dạng dữ liệu khác
                        $ma = $v['ma_voucher'] ?? 'N/A';
                        $loai = ($v['loai_giam_gia'] == 'percent') ? 'Giảm (%)' : 'Giảm tiền';
                        $gia_tri = number_format($v['gia_tri_giam'], 0, ',', '.') . ($v['loai_giam_gia'] == 'percent' ? '%' : 'đ');
                        $min = number_format($v['don_toi_thieu'], 0, ',', '.') . 'đ';
                        $used = (int)$v['so_luong_da_dung'];
                        $limit = (int)$v['so_luong_ma'];
                        $percent = ($limit > 0) ? min(($used / $limit) * 100, 100) : 0;
                    ?>
                    <tr style="border-bottom: 1px solid #333;">
                        <td class="py-3 px-3"><span class="badge bg-dark border border-warning text-warning fs-6"><?= $ma ?></span></td>
                        <td class="py-3">
                            <div class="text-white fw-bold"><?= $gia_tri ?></div>
                            <div class="small text-muted"><?= $loai ?></div>
                        </td>
                        <td class="py-3 text-center text-primary fw-bold"><?= $min ?></td>
                        <td class="py-3 text-center text-white" style="width: 150px;">
                            <div class="fw-bold"><?= $used ?> / <?= $limit ?></div>
                            <div class="progress mt-1" style="height: 5px; background-color: #333;">
                                <div class="progress-bar bg-warning" style="width: <?= $percent ?>%"></div>
                            </div>
                        </td>
                        <td class="py-3 text-center text-muted"><?= date('d/m/Y', strtotime($v['ngay_het_han'])) ?></td>
                        <td class="py-3 text-center">
                            <span class="badge rounded-pill <?= $status_class ?>"><?= $status_text ?></span>
                        </td>
                        <td class="py-3 text-center">
                            <button type="button" class="btn btn-sm btn-outline-info" 
                                    onclick="openVoucherModal('edit', <?= htmlspecialchars(json_encode($v)) ?>)">
                                <i class="fas fa-edit"></i>
                            </button>                            
                            <a href="index.php?act=XoaVoucher&id_voucher=<?= $v['id_voucher'] ?>" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="voucherModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.85); z-index: 9999; display: none; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div class="card border-secondary shadow-lg" style="width: 100%; max-width: 650px; background-color: #1a1a1a; border-radius: 15px; overflow: hidden;">
        <div class="p-3 d-flex justify-content-between align-items-center" style="background-color: #222; border-bottom: 2px solid #F28B00;">
            <h5 class="text-white fw-bold mb-0 "id="modalTitle"><i class="fas fa-ticket-alt me-2 text-warning"></i>TẠO MÃ GIẢM GIÁ MỚI</h5>
            <button onclick="document.getElementById('voucherModal').style.display='none'" class="btn-close btn-close-white"></button>
        </div>

        <div class="p-4">
            <form action="index.php" method="POST" id="voucherForm">
                <input type="hidden" name="act" id="formAction" value="ThemVoucher">
                <input type="hidden" name="id_voucher" id="input_id">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">MÃ VOUCHER</label>
                        <input type="text" name="ma_voucher" id="input_ma" class="form-control bg-dark border-secondary text-white fw-bold text-uppercase" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">LOẠI GIẢM GIÁ</label>
                        <select name="loai_giam_gia" id="input_loai" class="form-select bg-dark border-secondary text-white">
                            <option value="fixed">Giảm theo số tiền</option>
                            <option value="percent">Giảm theo phần trăm (%)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">MỨC GIẢM</label>
                        <input type="number" name="gia_tri_giam" id="input_giatri" class="form-control bg-dark border-secondary text-white" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">ĐƠN HÀNG TỐI THIỂU</label>
                        <input type="number" name="don_toi_thieu" id="input_min" class="form-control bg-dark border-secondary text-white" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">NGÀY HẾT HẠN</label>
                        <input type="datetime-local" name="ngay_het_han" id="input_ngay" class="form-control bg-dark border-secondary text-white" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">GIỚI HẠN LƯỢT DÙNG</label>
                        <input type="number" name="so_luong_ma" id="input_limit" class="form-control bg-dark border-secondary text-white" required />
                    </div>
                </div>
                <div class="p-3 bg-dark d-flex justify-content-end gap-2 border-top border-secondary mt-4">
                    <button type="button" onclick="document.getElementById('voucherModal').style.display='none'" class="btn btn-outline-secondary px-4 fw-bold rounded-pill">HỦY</button>
                    <button type="submit" id="btnSubmit" class="btn px-4 fw-bold text-white rounded-pill" style="background-color: #F28B00;">TẠO VOUCHER</button>
                </div>
            </form>
        </div>

    </div>
</div>
<script src="/LTWNC_LTWNC_WEBTMDT/views/js/VouchersManagements.js"></script>
<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>