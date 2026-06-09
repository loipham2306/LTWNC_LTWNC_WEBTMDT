<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start(); 

// --- DỮ LIỆU ĐƠN HÀNG GIẢ LẬP ---
$orders = [
    ['id' => '#ORD-001', 'customer' => 'Nguyễn Văn A', 'phone' => '0901234567', 'address' => 'Quận 1, TP. HCM', 'date' => '25/05/2026', 'total' => '3.350.000 đ', 'payment' => 'COD', 'status' => 'Chờ Duyệt'],
    ['id' => '#ORD-002', 'customer' => 'Trần Thị B', 'phone' => '0912345678', 'address' => 'Cầu Giấy, Hà Nội', 'date' => '24/05/2026', 'total' => '10.500.000 đ', 'payment' => 'Chuyển Khoản', 'status' => 'Đang Giao'],
    ['id' => '#ORD-003', 'customer' => 'Lê Hoàng C', 'phone' => '0987654321', 'address' => 'Hải Châu, Đà Nẵng', 'date' => '23/05/2026', 'total' => '850.000 đ', 'payment' => 'Momo', 'status' => 'Hoàn Thành'],
    ['id' => '#ORD-004', 'customer' => 'Phạm D', 'phone' => '0922334455', 'address' => 'Ninh Kiều, Cần Thơ', 'date' => '22/05/2026', 'total' => '1.250.000 đ', 'payment' => 'COD', 'status' => 'Đã Hủy'],
    ['id' => '#ORD-005', 'customer' => 'Vũ Đức E', 'phone' => '0933445566', 'address' => 'Quận 7, TP. HCM', 'date' => '21/05/2026', 'total' => '2.500.000 đ', 'payment' => 'Chuyển Khoản', 'status' => 'Chờ Duyệt']
];

// Hàm render màu sắc badge trạng thái trong PHP
function getStatusBadge($status) {
    switch($status) {
        case 'Chờ Duyệt': return 'bg-info text-dark';
        case 'Đang Giao': return 'bg-warning text-dark';
        case 'Hoàn Thành': return 'bg-success';
        case 'Đã Hủy': return 'bg-danger';
        default: return 'bg-secondary';
    }
}
?>

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
                    <?php foreach ($orders as $order): ?>
                        <tr class="order-row" style="border-bottom: 1px solid #333;">
                            <td class="fw-bold text-primary py-3 px-3 order-id"><?= $order['id'] ?></td>
                            <td class="text-white py-3 text-start">
                                <div class="fw-bold order-customer"><?= $order['customer'] ?></div>
                                <div class="small text-muted order-phone"><?= $order['phone'] ?></div>
                            </td>
                            <td class="text-muted py-3"><?= $order['date'] ?></td>
                            <td class="text-white fw-bold py-3 text-end"><?= $order['total'] ?></td>
                            <td class="py-3">
                                <span class="badge bg-dark border border-secondary text-info"><?= $order['payment'] ?></span>
                            </td>
                            <td class="py-3">
                                <span class="badge rounded-pill px-3 py-2 order-status <?= getStatusBadge($order['status']) ?>" data-status="<?= $order['status'] ?>">
                                    <?= $order['status'] ?>
                                </span>
                            </td>
                            <td class="py-3">
                                <button onclick='viewOrderDetail(<?= json_encode($order) ?>)' class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                    Chi tiết
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4 border-top border-secondary pt-4">
            <span class="text-muted small">Hiển thị 1 - 5 trong tổng số 45 đơn hàng</span>
            <nav>
                <ul class="pagination pagination-sm mb-0 d-flex flex-row gap-2">
                    <li class="page-item disabled">
                        <button class="page-link bg-dark border-secondary text-muted rounded px-3 py-2" style="cursor: not-allowed;">
                            <i class="fas fa-chevron-left me-1"></i> Trước
                        </button>
                    </li>
                    <li class="page-item active">
                        <button class="page-link border-0 text-white rounded px-3 py-2 shadow-sm" style="background-color: #F28B00;">1</button>
                    </li>
                    <li class="page-item">
                        <button class="page-link bg-dark border-secondary text-white rounded px-3 py-2">2</button>
                    </li>
                    <li class="page-item">
                        <button class="page-link bg-dark border-secondary text-white rounded px-3 py-2">3</button>
                    </li>
                    <li class="page-item">
                        <button class="page-link bg-dark border-secondary text-white rounded px-3 py-2">
                            Sau <i class="fas fa-chevron-right ms-1"></i>
                        </button>
                    </li>
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
                            <tbody>
                                <tr>
                                    <td class="text-start text-white">Bàn phím cơ Razer BlackWidow</td>
                                    <td class="text-muted">3.350.000 đ</td>
                                    <td>1</td>
                                    <td class="text-primary fw-bold">3.350.000 đ</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mt-3">
                            <h5 class="text-white fw-bold">Tổng thanh toán: <span class="text-primary fs-4 ms-2" id="modalTotal"></span></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-dark d-flex justify-content-between border-top border-secondary">
                <button class="btn btn-outline-danger fw-bold rounded-pill"><i class="fas fa-times me-2"></i>Hủy Đơn</button>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-info fw-bold rounded-pill"><i class="fas fa-check me-2"></i>Duyệt Đơn</button>
                    <button class="btn btn-outline-warning fw-bold rounded-pill"><i class="fas fa-truck me-2"></i>Giao Hàng</button>
                    <button class="btn fw-bold text-white rounded-pill" style="background-color: #28a745;"><i class="fas fa-check-double me-2"></i>Hoàn Thành</button>
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
            case 'Đang Giao': return 'bg-warning text-dark';
            case 'Hoàn Thành': return 'bg-success';
            case 'Đã Hủy': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }

    // Hiển thị dữ liệu vào Popup
    function viewOrderDetail(order) {
        document.getElementById('modalOrderId').innerText = order.id;
        document.getElementById('modalCustomer').innerText = order.customer;
        document.getElementById('modalPhone').innerText = order.phone;
        document.getElementById('modalAddress').innerText = order.address;
        document.getElementById('modalDate').innerText = order.date;
        document.getElementById('modalPayment').innerText = order.payment;
        document.getElementById('modalTotal').innerText = order.total;

        let statusEl = document.getElementById('modalStatus');
        statusEl.className = `badge rounded-pill px-2 py-1 ms-2 ${getJsStatusBadge(order.status)}`;
        statusEl.innerText = order.status;

        // Bật Modal
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