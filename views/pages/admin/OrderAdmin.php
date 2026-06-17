
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

        case 'Chờ duyệt':
            return 'bg-info text-dark';

        case 'Đã xác nhận':
            return 'bg-primary';

        case 'Đang giao':
            return 'bg-warning text-dark';

        case 'Đã giao':
            return 'bg-success';

        case 'Đã hủy':
            return 'bg-danger';

        default:
            return 'bg-secondary';
    }
}
?>
<style>
    .pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    border-top: 1px solid #444;
    padding-top: 15px;
}

.pagination {
    margin-bottom: 0;
    flex-wrap: wrap;
    gap: 5px;
}

.pagination .page-item {
    margin: 2px;
}

.pagination .page-link {
    background: #1a1a1a;
    border: 1px solid #444;
    color: #fff;
    border-radius: 8px !important;
    padding: 6px 12px;
}

.pagination .page-item.active .page-link {
    background: #F28B00;
    border-color: #F28B00;
    color: #000;
}

.pagination .page-link:hover {
    background: #333;
    color: #fff;
}
</style>
<div class="container-fluid p-4" style="background-color: #111; min-height: 100vh; color: #fff;">
    <h3 class="text-white fw-bold mb-4">Quản Lý Đơn Hàng</h3>

    <div class="card border-0 rounded p-4" style="background-color: #1a1a1a;">
        
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex gap-2 flex-grow-1" style="max-width: 600px;">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" onkeyup="filterOrders()" class="form-control bg-dark border-secondary text-white" placeholder="Tìm mã đơn, tên khách, số điện thoại...">
                </div>
                <select id="statusFilter" onchange="filterOrders()" class="form-select bg-dark border-secondary text-white" style="max-width: 180px;">
                    <option value="">Tất cả trạng thái</option>
                    <option value="Chờ Duyệt">Chờ Duyệt</option>
                    <option value="Đang Giao">Đang Giao</option>
                    <option value="Hoàn Thành">Hoàn Thành</option>
                    <option value="Đã Hủy">Đã Hủy</option>
                </select>
            </div>
            <button class="btn btn-outline-success fw-bold shadow-sm">
                <i class="fas fa-file-excel me-2"></i> Xuất Excel
            </button>
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
                        <th scope="col" class="py-3">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                   <?php if(!empty($orders)): ?>

                        <?php foreach ($orders as $order): ?>

                            <tr class="order-row">

                                <td class="fw-bold text-primary order-id">
                                    #ORD-<?= $order['id_don_hang'] ?>
                                </td>

                                <td class="text-start">
                                    <div class="fw-bold order-customer">
                                        <?= htmlspecialchars($order['ten_nguoi_nhan']) ?>
                                    </div>

                                    <div class="small text-muted order-phone">
                                        <?= htmlspecialchars($order['sdt_nguoi_nhan']) ?>
                                    </div>
                                </td>

                                <td>
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
                                    <span
                                        class="badge rounded-pill px-3 py-2 order-status <?= getStatusBadge($order['trang_thai_don_hang']) ?>"
                                        data-status="<?= $order['trang_thai_don_hang'] ?>"
                                    >
                                        <?= $order['trang_thai_don_hang'] ?>
                                    </span>
                                </td>

                                <td>

                                    <button
                                        class="btn btn-sm btn-outline-warning rounded-pill"
                                        onclick='viewOrderDetail(<?= htmlspecialchars(json_encode($order, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE), ENT_QUOTES, "UTF-8") ?>)'
                                    >
                                        Chi tiết
                                    </button>

                                    <?php if(
                                        $order['trang_thai_don_hang'] != 'Đã hủy'
                                        &&
                                        $order['trang_thai_don_hang'] != 'Đã giao'
                                    ): ?>

                                    <a id="cancelLink"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                        <i class="fas fa-times me-2"></i>Hủy Đơn Hàng
                                        </a>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <br>
                            Chưa có đơn hàng nào
                        </td>
                    </tr>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-4 border-top border-secondary pt-4">

            <span class="text-muted small">
                Tổng đơn hàng: <?= $totalOrders ?>
            </span>

            <nav class="ms-auto">
                <ul class="pagination pagination-sm mb-0 d-flex flex-row flex-nowrap">
                    <?php for($i=1;$i<=$totalPages;$i++): ?>
                        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                            <a class="page-link"
                            href="index.php?act=QuanLyDonHang&page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>

        </div>

    </div>

    <div id="orderDetailModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.85); z-index: 9999; display: none; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
        <div class="card border-secondary shadow-lg" style="width: 100%; max-width: 800px; background-color: #1a1a1a; border-radius: 15px; overflow: hidden;">
            
            <div class="p-3 d-flex justify-content-between align-items-center" style="background-color: #222; border-bottom: 2px solid #F28B00;">
                <h5 class="text-white fw-bold mb-0">
                    <i class="fas fa-file-invoice me-2 text-warning"></i> 
                    CHI TIẾT ĐƠN HÀNG: <span class="text-primary" id="modalOrderId"></span>
                </h5>
                <button onclick="closeDetailModal()" class="btn-close btn-close-white"></button>
            </div>

            <div class="p-4" style="max-height: 75vh; overflow-y: auto;">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-dark border border-secondary h-100">
                            <h6 class="text-warning fw-bold border-bottom border-secondary pb-2 mb-3">Thông Tin Người Nhận</h6>
                            <p class="text-white mb-2"><i class="fas fa-user me-2 text-muted"></i><span id="modalCustomer"></span></p>
                            <p class="text-white mb-2"><i class="fas fa-phone me-2 text-muted"></i><span id="modalPhone"></span></p>
                            <p class="text-white mb-0"><i class="fas fa-map-marker-alt me-2 text-muted"></i><span id="modalAddress"></span></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 rounded bg-dark border border-secondary h-100">
                            <h6 class="text-warning fw-bold border-bottom border-secondary pb-2 mb-3">Thông Tin Giao Dịch</h6>
                            <p class="text-white mb-2"><i class="fas fa-calendar-alt me-2 text-muted"></i>Ngày đặt: <span id="modalDate"></span></p>
                            <p class="text-white mb-2"><i class="fas fa-credit-card me-2 text-muted"></i>Thanh toán: <strong class="text-info" id="modalPayment"></strong></p>
                            <p class="text-white mb-0">
                                <i class="fas fa-info-circle me-2 text-muted"></i>Trạng thái: 
                                <span id="modalStatus" class="badge rounded-pill px-2 py-1 ms-2"></span>
                            </p>
                        </div>
                    </div>

                    <div class="col-12">
                        <h6 class="text-white fw-bold mb-3 mt-2">Sản Phẩm Đã Đặt</h6>
                        <table class="table table-dark table-bordered border-secondary text-center mb-0">
                            <thead style="background-color: #222;">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>SL</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                           <tbody id="modalProducts">
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-3">
                            <h5 class="text-white fw-bold">Tổng thanh toán: <span class="text-primary fs-4 ms-2" id="modalTotal"></span></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-dark d-flex justify-content-between border-top border-secondary">

                <form action="index.php?act=CapNhatTrangThaiDonHang" method="POST">
                    <input type="hidden" name="id_don_hang" id="modalOrderIdInput">

                    <input type="hidden" name="trang_thai" value="Đã hủy">
                    <a href="index.php?act=HuyDonHang&id=<?= $order['id_don_hang'] ?>"
                        class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                        <i class="fas fa-times me-2"></i>Hủy Đơn Hàng
                    </a>
                    
                </form>

               <div class="d-flex gap-2">

                    <form id="btnApproveForm" action="index.php?act=CapNhatTrangThaiDonHang" method="POST">
                        <input type="hidden" name="id_don_hang" class="order-id-hidden">
                        <input type="hidden" name="trang_thai" value="Đã xác nhận">
                        <button type="submit" class="btn btn-outline-info fw-bold rounded-pill">
                            Duyệt Đơn
                        </button>
                    </form>

                    <form id="btnShipForm" action="index.php?act=CapNhatTrangThaiDonHang" method="POST">
                        <input type="hidden" name="id_don_hang" class="order-id-hidden">
                        <input type="hidden" name="trang_thai" value="Đang giao">
                        <button type="submit" class="btn btn-outline-warning fw-bold rounded-pill">
                            Giao hàng
                        </button>
                    </form>

                    <form id="btnDoneForm" action="index.php?act=CapNhatTrangThaiDonHang" method="POST">
                        <input type="hidden" name="id_don_hang" class="order-id-hidden">
                        <input type="hidden" name="trang_thai" value="Đã giao">
                        <button type="submit" class="btn btn-success fw-bold rounded-pill">
                            Hoàn thành
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // JS tương đương getStatusBadge()
    function getJsStatusBadge(status) {
        switch(status) {
            case 'Chờ Duyệt': return 'bg-info text-dark';
            case 'Đã xác nhận': return 'bg-primary';
            case 'Đang giao': return 'bg-warning text-dark';
            case 'Đã giao': return 'bg-success';
            case 'Đã hủy': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }

    function viewOrderDetail(order)
{
    console.log(order);

    // ======================
    // ORDER INFO
    // ======================
    document.getElementById('modalOrderId').innerText = order.id_don_hang;
    document.getElementById('modalOrderIdInput').value = order.id_don_hang;

    document.querySelectorAll('.order-id-hidden').forEach(input => {
        input.value = order.id_don_hang;
    });

    document.getElementById('modalCustomer').innerText = order.ten_nguoi_nhan;
    document.getElementById('modalPhone').innerText = order.sdt_nguoi_nhan;
    document.getElementById('modalAddress').innerText = order.dia_chi_giao_hang;
    document.getElementById('modalDate').innerText = order.ngay_dat;
    document.getElementById('modalPayment').innerText = order.phuong_thuc_thanh_toan;

    document.getElementById('modalTotal').innerText =
        Number(order.tong_tien).toLocaleString('vi-VN') + ' đ';

    // ======================
    // STATUS BADGE
    // ======================
    let statusEl = document.getElementById('modalStatus');

    statusEl.className =
        `badge rounded-pill px-2 py-1 ms-2 ${getJsStatusBadge(order.trang_thai_don_hang)}`;

    statusEl.innerText = order.trang_thai_don_hang;

    // ======================
    // CANCEL BUTTON
    // ======================
    const cancelBtn = document.getElementById('cancelLink');

    if (cancelBtn) {
        cancelBtn.href =
            "index.php?act=HuyDonHang&id=" + order.id_don_hang;

        if (
            order.trang_thai_don_hang === 'Đang giao' ||
            order.trang_thai_don_hang === 'Đã giao' ||
            order.trang_thai_don_hang === 'Đã hủy'
        ) {
            cancelBtn.style.display = 'none';
        } else {
            cancelBtn.style.display = 'inline-block';
        }
    }

    // ======================
    // HANDLE ACTION BUTTONS
    // ======================
    const status = order.trang_thai_don_hang.trim();

const btnApprove = document.getElementById('btnApproveForm');
const btnShip = document.getElementById('btnShipForm');
const btnDone = document.getElementById('btnDoneForm');

// hide all
btnApprove.classList.add('d-none');
btnShip.classList.add('d-none');
btnDone.classList.add('d-none');

switch (status) {
    case 'Chờ duyệt':
        btnApprove.classList.remove('d-none');
        break;

    case 'Đã xác nhận':
        btnShip.classList.remove('d-none');
        break;

    case 'Đang giao':
        btnDone.classList.remove('d-none');
        break;
}

    // ======================
    // RENDER PRODUCTS
    // ======================
    let productHTML = '';

    if (order.chi_tiet && order.chi_tiet.length > 0)
    {
        order.chi_tiet.forEach(item => {

            let thanhTien =
                Number(item.gia_luc_mua) * Number(item.so_luong);

            productHTML += `
                <tr>
                    <td class="text-start">
                        <div class="d-flex align-items-center gap-2">

                            <img
                                src="/LTWNC_LTWNC_WEBTMDT/assets/images/products/Bien_The_Products/${item.hinh_anh_bien_the}"
                                width="60"
                                height="60"
                                class="rounded"
                                style="object-fit:cover"
                            >

                            <div>
                                <div class="fw-bold text-white">
                                    ${item.ten_san_pham}
                                </div>

                                <small class="text-muted">
                                    Size: ${item.kich_co}
                                </small>

                                <br>

                                <span style="
                                    display:inline-block;
                                    width:15px;
                                    height:15px;
                                    border-radius:50%;
                                    background:${item.mau_sac};
                                    border:1px solid #ccc;">
                                </span>
                            </div>

                        </div>
                    </td>

                    <td>
                        ${Number(item.gia_luc_mua).toLocaleString('vi-VN')} đ
                    </td>

                    <td>
                        ${item.so_luong}
                    </td>

                    <td class="text-warning fw-bold">
                        ${thanhTien.toLocaleString('vi-VN')} đ
                    </td>
                </tr>
            `;
        });
    }
    else
    {
        productHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted py-3">
                    Không có sản phẩm
                </td>
            </tr>
        `;
    }

    document.getElementById('modalProducts').innerHTML = productHTML;

    // ======================
    // SHOW MODAL
    // ======================
    document.getElementById('orderDetailModal').style.display = 'flex';
}
    // Đóng Popup
    function closeDetailModal() {
        document.getElementById('orderDetailModal').style.display = 'none';
    }

    // Tính năng bộ lọc tìm kiếm và dropdown trạng thái
    function filterOrders() {
        let textInput = document.getElementById('searchInput').value.toLowerCase();
        let statusSelect = document.getElementById('statusFilter').value; // lấy giá trị chính xác
        let rows = document.getElementsByClassName('order-row');
        
        for (let i = 0; i < rows.length; i++) {
            let id = rows[i].querySelector('.order-id').innerText.toLowerCase();
            let customer = rows[i].querySelector('.order-customer').innerText.toLowerCase();
            let phone = rows[i].querySelector('.order-phone').innerText.toLowerCase();
            let status = rows[i].querySelector('.order-status').getAttribute('data-status'); // Lấy trạng thái thực tế
            
            // Kiểm tra khớp từ khóa
            let matchText = id.includes(textInput) || customer.includes(textInput) || phone.includes(textInput);
            // Kiểm tra khớp select box
            let matchStatus = (statusSelect === "") || (status === statusSelect);
            
            if (matchText && matchStatus) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
</script>

<?php
// Gửi giao diện vào Layout Admin chung
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>