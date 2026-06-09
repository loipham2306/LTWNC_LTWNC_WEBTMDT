<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start(); 

// --- DỮ LIỆU VOUCHER GIẢ LẬP ---
$vouchers = [
    ['id' => 1, 'code' => 'STORE100K', 'type' => 'Giảm tiền', 'value' => '100.000 đ', 'minSpend' => '1.000.000 đ', 'used' => 45, 'limit' => 100, 'expiry' => '30/12/2026', 'status' => 'Đang chạy'],
    ['id' => 2, 'code' => 'GAMING20', 'type' => 'Giảm %', 'value' => '20%', 'minSpend' => '500.000 đ', 'used' => 100, 'limit' => 100, 'expiry' => '01/05/2026', 'status' => 'Hết lượt'],
    ['id' => 3, 'code' => 'HELLOSUMMER', 'type' => 'Giảm %', 'value' => '10%', 'minSpend' => '0 đ', 'used' => 12, 'limit' => 200, 'expiry' => '15/08/2026', 'status' => 'Đang chạy'],
    ['id' => 4, 'code' => 'NEWUSER50', 'type' => 'Giảm tiền', 'value' => '50.000 đ', 'minSpend' => '200.000 đ', 'used' => 0, 'limit' => 50, 'expiry' => '01/01/2027', 'status' => 'Chưa diễn ra'],
];

function getStatusBadge($status) {
    switch($status) {
        case 'Đang chạy': return 'bg-success';
        case 'Hết lượt': return 'bg-danger';
        case 'Chưa diễn ra': return 'bg-info text-dark';
        default: return 'bg-secondary';
    }
}
?>

<div class="container-fluid p-4" style="background-color: #111; min-height: 100vh; color: #fff;">
    <h3 class="text-white fw-bold mb-4">Quản Lý Voucher & Khuyến Mãi</h3>

    <div class="card border-0 rounded p-4" style="background-color: #1a1a1a;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="input-group w-50">
                <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control bg-dark border-secondary text-white" placeholder="Tìm theo mã voucher...">
            </div>
            <button onclick="document.getElementById('voucherModal').style.display='flex'" class="btn fw-bold text-white px-4 rounded-pill" style="background-color: #F28B00;">
                <i class="fas fa-plus me-2"></i> Tạo Voucher
            </button>
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
                        $percent = ($v['used'] / $v['limit']) * 100;
                    ?>
                        <tr style="border-bottom: 1px solid #333;">
                            <td class="py-3 px-3"><span class="badge bg-dark border border-warning text-warning fs-6"><?= $v['code'] ?></span></td>
                            <td class="py-3">
                                <div class="text-white fw-bold"><?= $v['value'] ?></div>
                                <div class="small text-muted"><?= $v['type'] ?></div>
                            </td>
                            <td class="py-3 text-center text-primary fw-bold"><?= $v['minSpend'] ?></td>
                            <td class="py-3 text-center text-white" style="width: 150px;">
                                <div class="fw-bold"><?= $v['used'] ?> / <?= $v['limit'] ?></div>
                                <div class="progress mt-1" style="height: 5px; background-color: #333;">
                                    <div class="progress-bar bg-warning" style="width: <?= $percent ?>%"></div>
                                </div>
                            </td>
                            <td class="py-3 text-center text-muted"><?= $v['expiry'] ?></td>
                            <td class="py-3 text-center">
                                <span class="badge rounded-pill px-3 py-2 <?= getStatusBadge($v['status']) ?>"><?= $v['status'] ?></span>
                            </td>
                            <td class="py-3 text-center">
                                <button class="btn btn-sm btn-outline-info me-2"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
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
            <h5 class="text-white fw-bold mb-0"><i class="fas fa-ticket-alt me-2 text-warning"></i>TẠO MÃ GIẢM GIÁ MỚI</h5>
            <button onclick="document.getElementById('voucherModal').style.display='none'" class="btn-close btn-close-white"></button>
        </div>

        <div class="p-4">
            <form>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">MÃ VOUCHER</label>
                        <input type="text" class="form-control bg-dark border-secondary text-white fw-bold text-uppercase" placeholder="VD: GiamGia50" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">LOẠI GIẢM GIÁ</label>
                        <select class="form-select bg-dark border-secondary text-white">
                            <option>Giảm theo số tiền cố định</option>
                            <option>Giảm theo phần trăm (%)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">MỨC GIẢM</label>
                        <input type="number" class="form-control bg-dark border-secondary text-white" placeholder="VD: 100000 hoặc 20" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">ĐƠN HÀNG TỐI THIỂU</label>
                        <input type="number" class="form-control bg-dark border-secondary text-white" placeholder="VD: 500000" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">NGÀY BẮT ĐẦU</label>
                        <input type="date" class="form-control bg-dark border-secondary text-white" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-bold small">NGÀY KẾT THÚC</label>
                        <input type="date" class="form-control bg-dark border-secondary text-white" />
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-muted fw-bold small">GIỚI HẠN LƯỢT DÙNG</label>
                        <input type="number" class="form-control bg-dark border-secondary text-white" placeholder="VD: 100" />
                    </div>
                </div>
            </form>
        </div>

        <div class="p-3 bg-dark d-flex justify-content-end gap-2 border-top border-secondary">
            <button onclick="document.getElementById('voucherModal').style.display='none'" class="btn btn-outline-secondary px-4 fw-bold rounded-pill">HỦY</button>
            <button class="btn px-4 fw-bold text-white rounded-pill" style="background-color: #F28B00;">TẠO VOUCHER</button>
        </div>
    </div>
</div>

<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>