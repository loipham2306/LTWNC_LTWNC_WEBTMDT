<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start(); 
$orders = $orders ?? [];
$totalOrders = $totalOrders ?? 0;
$totalPages = $totalPages ?? 1;
$page = $page ?? 1;
$search = $search ?? '';
$status = $status ?? '';

// Hàm render màu sắc badge trạng thái trong PHP
function getStatusBadge($status)
{
    switch ($status) {
        case 'Chờ duyệt': return 'bg-info text-dark';
        case 'Đã xác nhận': return 'bg-primary';
        case 'Đang giao': return 'bg-warning text-dark';
        case 'Đã giao': return 'bg-success';
        case 'Đã hủy': return 'bg-danger';
        default: return 'bg-secondary';
    }
}
?>
<style>
    /* Làm gọn thanh cuộn cho bảng */
    .table-responsive::-webkit-scrollbar { height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
    
    /* Phân trang */
    .custom-pagination .page-item { margin: 0 2px; }
    .custom-pagination .page-link {
        background: #1a1a1a; border: 1px solid #444; color: #fff; border-radius: 8px !important; padding: 6px 12px;
    }
    .custom-pagination .page-item.active .page-link { background: #F28B00; border-color: #F28B00; color: #000; font-weight: bold; }
    .custom-pagination .page-link:hover { background: #333; color: #fff; }

    /* Hiệu ứng hover cho nút chi tiết */
    .hover-orange:hover { background-color: #F28B00 !important; color: #fff !important; border-color: #F28B00 !important; }
    
    /* Chống tràn chữ dài trong bảng */
    .table-dark th, .table-dark td { white-space: nowrap; vertical-align: middle; }
</style>

<div class="container-fluid px-0">
    <h4 class="text-white fw-bold mb-4">Quản Lý Đơn Hàng</h4>

    <div class="card border-0 rounded p-3 p-md-4 mb-4 shadow-sm" style="background-color: #1a1a1a;">
        
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-12 col-lg-8">
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <div class="input-group flex-grow-1">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchInput" onkeyup="filterOrders()" class="form-control bg-dark border-secondary text-white" placeholder="Tìm mã đơn, tên khách, SĐT...">
                    </div>
                    <select id="statusFilter" onchange="filterOrders()" class="form-select bg-dark border-secondary text-white" style="min-width: 160px; max-width: 100%;">
                        <option value="">Tất cả trạng thái</option>
                        <option value="Chờ duyệt">Chờ Duyệt</option>
                        <option value="Đã xác nhận">Đã xác nhận</option>
                        <option value="Đang giao">Đang Giao</option>
                        <option value="Đã giao">Đã giao</option>
                        <option value="Đã hủy">Đã Hủy</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-lg-4 text-start text-lg-end">
                <button class="btn btn-outline-success fw-bold w-100 w-lg-auto">
                    <i class="fas fa-file-excel me-2"></i> Xuất Excel
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0 text-center">
                <thead>
                    <tr style="border-bottom: 2px solid #F28B00;">
                        <th scope="col" class="py-3 px-3">Mã Đơn</th>
                        <th scope="col" class="py-3 text-start">Khách Hàng</th>
                        <th scope="col" class="py-3">Ngày Đặt</th>
                        <th scope="col" class="py-3 text-end">Tổng Tiền</th>
                        <th scope="col" class="py-3">Thanh Toán</th>
                        <th scope="col" class="py-3">Trạng Thái</th>
                        <th scope="col" class="py-3 text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr class="order-row" style="border-bottom: 1px solid #333;">
                                <td class="fw-bold text-orange order-id">
                                    #ORD-<?= $order['id_don_hang'] ?>
                                </td>
                                <td class="text-start">
                                    <div class="fw-bold text-white order-customer">
                                        <?= htmlspecialchars($order['ten_nguoi_nhan']) ?>
                                    </div>
                                    <div class="small text-muted order-phone">
                                        <?= htmlspecialchars($order['sdt_nguoi_nhan']) ?>
                                    </div>
                                </td>
                                <td class="text-muted">
                                    <?= date('d/m/Y H:i', strtotime($order['ngay_dat'])) ?>
                                </td>
                                <td class="text-end fw-bold text-warning">
                                    <?= number_format($order['tong_tien'],0,',','.') ?> đ
                                </td>
                                <td>
                                    <span class="badge bg-dark border border-secondary text-info">
                                        <?= strtoupper($order['phuong_thuc_thanh_toan']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2 order-status <?= getStatusBadge($order['trang_thai_don_hang']) ?>"
                                          data-status="<?= $order['trang_thai_don_hang'] ?>">
                                        <?= $order['trang_thai_don_hang'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-light rounded-pill px-3 hover-orange"
                                                onclick='viewOrderDetail(<?= htmlspecialchars(json_encode($order, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE), ENT_QUOTES, "UTF-8") ?>)'>
                                            <i class="fas fa-eye me-1"></i> Chi tiết
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x mb-3"></i><br>Chưa có đơn hàng nào
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4 border-top border-secondary pt-4">
            <span class="text-muted small fw-bold">Tổng số: <span class="text-orange"><?= $totalOrders ?></span> đơn hàng</span>
            <nav>
                <ul class="pagination custom-pagination mb-0 d-flex flex-row flex-wrap justify-content-center">
                    <?php for($i=1; $i<=$totalPages; $i++): ?>
                        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                            <a class="page-link" href="index.php?act=QuanLyDonHang&page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>

    </div>

    <div id="orderDetailModal" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.85); z-index: 9999; display: none; align-items: center; justify-content: center; backdrop-filter: blur(5px); padding: 15px;">
        <div class="card border-secondary shadow-lg d-flex flex-column" style="width: 100%; max-width: 800px; max-height: 90vh; background-color: #1a1a1a; border-radius: 12px; overflow: hidden;">
            
            <div class="p-3 d-flex justify-content-between align-items-center" style="background-color: #222; border-bottom: 2px solid #F28B00; flex-shrink: 0;">
                <h6 class="text-white fw-bold mb-0 text-truncate">
                    <i class="fas fa-file-invoice me-2 text-warning"></i> CHI TIẾT: <span class="text-primary" id="modalOrderId"></span>
                </h6>
                <button onclick="closeDetailModal()" class="btn-close btn-close-white"></button>
            </div>

            <div class="p-3 p-md-4 flex-grow-1" style="overflow-y: auto;">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded bg-dark border border-secondary h-100">
                            <h6 class="text-warning fw-bold border-bottom border-secondary pb-2 mb-3">Người Nhận</h6>
                            <p class="text-white mb-2"><i class="fas fa-user me-2 text-muted"></i><span id="modalCustomer"></span></p>
                            <p class="text-white mb-2"><i class="fas fa-phone me-2 text-muted"></i><span id="modalPhone"></span></p>
                            <p class="text-white mb-0"><i class="fas fa-map-marker-alt me-2 text-muted"></i><span id="modalAddress"></span></p>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="p-3 rounded bg-dark border border-secondary h-100">
                            <h6 class="text-warning fw-bold border-bottom border-secondary pb-2 mb-3">Giao Dịch</h6>
                            <p class="text-white mb-2"><i class="fas fa-calendar-alt me-2 text-muted"></i>Ngày: <span id="modalDate"></span></p>
                            <p class="text-white mb-2"><i class="fas fa-credit-card me-2 text-muted"></i>Thanh toán: <strong class="text-info" id="modalPayment"></strong></p>
                            <p class="text-white mb-0">
                                <i class="fas fa-info-circle me-2 text-muted"></i>Trạng thái: <span id="modalStatus" class="badge rounded-pill px-2 py-1 ms-1"></span>
                            </p>
                        </div>
                    </div>

                    <div class="col-12">
                        <h6 class="text-white fw-bold mb-2 mt-2">Sản Phẩm Đã Đặt</h6>
                        <div class="table-responsive rounded border border-secondary">
                            <table class="table table-dark table-hover text-center mb-0" style="min-width: 500px;">
                                <thead style="background-color: #222;">
                                    <tr>
                                        <th class="text-start">Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>SL</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody id="modalProducts"></tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <h5 class="text-white fw-bold">Tổng thanh toán: <span class="text-orange fs-4 ms-2" id="modalTotal"></span></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-dark d-flex flex-column flex-sm-row justify-content-between gap-2 border-top border-secondary flex-shrink-0">
                
                <form action="index.php?act=CapNhatTrangThaiDonHang" method="POST" id="btnCancelForm" class="w-100 w-sm-auto mb-2 mb-sm-0">
                    <input type="hidden" name="id_don_hang" class="order-id-hidden">
                    <input type="hidden" name="trang_thai" value="Đã hủy">
                        <a id="btnCancelLink"
                        href="#"
                        class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Bạn có muốn hủy đơn hàng này?')">
                        <i class="fas fa-times me-2"></i>Hủy Đơn Hàng
                    </a>
                </form>

                <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto justify-content-end">
                    <form id="btnApproveForm" action="index.php?act=CapNhatTrangThaiDonHang" method="POST" class="flex-grow-1 flex-sm-grow-0">
                        <input type="hidden" name="id_don_hang" class="order-id-hidden">
                        <input type="hidden" name="trang_thai" value="Đã xác nhận">
                        <button type="submit" class="btn btn-outline-info fw-bold w-100 px-4">Duyệt Đơn</button>
                    </form>

                    <form id="btnShipForm" action="index.php?act=CapNhatTrangThaiDonHang" method="POST" class="flex-grow-1 flex-sm-grow-0">
                        <input type="hidden" name="id_don_hang" class="order-id-hidden">
                        <input type="hidden" name="trang_thai" value="Đang giao">
                        <button type="submit" class="btn btn-outline-warning fw-bold w-100 px-4">Giao Hàng</button>
                    </form>

                    <form id="btnDoneForm" action="index.php?act=CapNhatTrangThaiDonHang" method="POST" class="flex-grow-1 flex-sm-grow-0">
                        <input type="hidden" name="id_don_hang" class="order-id-hidden">
                        <input type="hidden" name="trang_thai" value="Đã giao">
                        <button type="submit" class="btn btn-success fw-bold w-100 px-4">Hoàn Thành</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Xử lý màu sắc trạng thái trong JS
    function getJsStatusBadge(status) {
        switch(status) {
            case 'Chờ duyệt': return 'bg-info text-dark';
            case 'Đã xác nhận': return 'bg-primary';
            case 'Đang giao': return 'bg-warning text-dark';
            case 'Đã giao': return 'bg-success';
            case 'Đã hủy': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }

    function viewOrderDetail(order) {
        // Gắn ID cho tất cả form hidden input
        document.querySelectorAll('.order-id-hidden').forEach(input => {
            input.value = order.id_don_hang;
        });

        // Điền thông tin
        document.getElementById('modalOrderId').innerText = order.id_don_hang;
        document.getElementById('modalCustomer').innerText = order.ten_nguoi_nhan;
        document.getElementById('modalPhone').innerText = order.sdt_nguoi_nhan;
        document.getElementById('modalAddress').innerText = order.dia_chi_giao_hang;
        document.getElementById('modalDate').innerText = order.ngay_dat;
        document.getElementById('modalPayment').innerText = order.phuong_thuc_thanh_toan;
        document.getElementById('modalTotal').innerText = Number(order.tong_tien).toLocaleString('vi-VN') + ' đ';
        document.getElementById("btnCancelLink").href =
            "index.php?act=HuyDonHang&id=" + order.id_don_hang;
        // Trạng thái
        let statusEl = document.getElementById('modalStatus');
        statusEl.className = `badge rounded-pill px-2 py-1 ms-1 ${getJsStatusBadge(order.trang_thai_don_hang)}`;
        statusEl.innerText = order.trang_thai_don_hang;

        // Xử lý hiển thị các nút thao tác
        const status = order.trang_thai_don_hang.trim();
        const btnCancel = document.getElementById('btnCancelForm');
        const btnApprove = document.getElementById('btnApproveForm');
        const btnShip = document.getElementById('btnShipForm');
        const btnDone = document.getElementById('btnDoneForm');

        btnCancel.classList.add('d-none');
        btnApprove.classList.add('d-none');
        btnShip.classList.add('d-none');
        btnDone.classList.add('d-none');

        if (status === 'Chờ duyệt') {
            btnApprove.classList.remove('d-none');
            btnCancel.classList.remove('d-none');
        } else if (status === 'Đã xác nhận') {
            btnShip.classList.remove('d-none');
            btnCancel.classList.remove('d-none');
        } else if (status === 'Đang giao') {
            btnDone.classList.remove('d-none');
        }

        // Đổ dữ liệu bảng sản phẩm
        let productHTML = '';
        if (order.chi_tiet && order.chi_tiet.length > 0) {
            order.chi_tiet.forEach(item => {
                let thanhTien = Number(item.gia_luc_mua) * Number(item.so_luong);
                productHTML += `
                    <tr style="border-bottom: 1px solid #333;">
                        <td class="text-start">
                            <div class="d-flex align-items-center gap-2">
                                <img src="/LTWNC_LTWNC_WEBTMDT/assets/images/products/Bien_The_Products/${item.hinh_anh_bien_the}" 
                                     width="50" height="50" class="rounded" style="object-fit:cover">
                                <div>
                                    <div class="fw-bold text-white text-truncate" style="max-width: 150px;">${item.ten_san_pham}</div>
                                    <small class="text-muted">Size: ${item.kich_co}</small>
                                </div>
                            </div>
                        </td>
                        <td>${Number(item.gia_luc_mua).toLocaleString('vi-VN')} đ</td>
                        <td>${item.so_luong}</td>
                        <td class="text-warning fw-bold text-end">${thanhTien.toLocaleString('vi-VN')} đ</td>
                    </tr>
                `;
            });
        } else {
            productHTML = `<tr><td colspan="4" class="text-center text-muted py-3">Không có sản phẩm</td></tr>`;
        }

        document.getElementById('modalProducts').innerHTML = productHTML;
        document.getElementById('orderDetailModal').style.display = 'flex';
    }

    function closeDetailModal() {
        document.getElementById('orderDetailModal').style.display = 'none';
    }

    function filterOrders() {
        let textInput = document.getElementById('searchInput').value.toLowerCase();
        let statusSelect = document.getElementById('statusFilter').value;
        let rows = document.getElementsByClassName('order-row');
        
        for (let i = 0; i < rows.length; i++) {
            let id = rows[i].querySelector('.order-id').innerText.toLowerCase();
            let customer = rows[i].querySelector('.order-customer').innerText.toLowerCase();
            let phone = rows[i].querySelector('.order-phone').innerText.toLowerCase();
            let status = rows[i].querySelector('.order-status').getAttribute('data-status');
            
            let matchText = id.includes(textInput) || customer.includes(textInput) || phone.includes(textInput);
            let matchStatus = (statusSelect === "") || (status === statusSelect);
            
            rows[i].style.display = (matchText && matchStatus) ? '' : 'none';
        }
    }
</script>

<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>