<?php
session_start();
ob_start(); 

// --- DỮ LIỆU KHÁCH HÀNG GIẢ LẬP (Giống hệt state của React) ---
$customers = [
    ['id' => 'KH-001', 'name' => 'Nguyễn Văn A', 'email' => 'nguyenvana@gmail.com', 'phone' => '0901234567', 'totalSpent' => '15.450.000 đ', 'ordersCount' => 5, 'joinDate' => '10/01/2026', 'status' => 'Hoạt động'],
    ['id' => 'KH-002', 'name' => 'Trần Thị B', 'email' => 'tranthib_99@yahoo.com', 'phone' => '0912345678', 'totalSpent' => '2.100.000 đ', 'ordersCount' => 1, 'joinDate' => '15/02/2026', 'status' => 'Hoạt động'],
    ['id' => 'KH-003', 'name' => 'Lê Hoàng C', 'email' => 'hoangle_dev@gmail.com', 'phone' => '0987654321', 'totalSpent' => '850.000 đ', 'ordersCount' => 1, 'joinDate' => '20/03/2026', 'status' => 'Bị khóa'],
    ['id' => 'KH-004', 'name' => 'Phạm Hữu Lợi', 'email' => 'loipham@gmail.com', 'phone' => '0123456789', 'totalSpent' => '35.000.000 đ', 'ordersCount' => 12, 'joinDate' => '01/11/2025', 'status' => 'VIP'],
    ['id' => 'KH-005', 'name' => 'Như Ý', 'email' => 'nhuy_oxford@gmail.com', 'phone' => '0933445566', 'totalSpent' => '0 đ', 'ordersCount' => 0, 'joinDate' => '05/05/2026', 'status' => 'Hoạt động']
];

// Hàm lấy class màu sắc cho trạng thái (Dùng cho bảng HTML)
function getStatusBadge($status) {
    switch($status) {
        case 'Hoạt động': return 'bg-success';
        case 'Bị khóa': return 'bg-danger';
        case 'VIP': return 'bg-warning text-dark fw-bold';
        default: return 'bg-secondary';
    }
}
?>

<div class="container-fluid p-4" style="background-color: #111; min-height: 100vh; color: #fff;">
    <h3 class="text-white fw-bold mb-4">Quản Lý Khách Hàng</h3>

    <div class="card border-0 rounded p-4" style="background-color: #1a1a1a;">
        
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div class="d-flex gap-2 flex-grow-1" style="max-width: 500px;">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" onkeyup="filterCustomers()" class="form-control bg-dark border-secondary text-white" placeholder="Tìm theo tên, email, SĐT...">
                </div>
                <select class="form-select bg-dark border-secondary text-white" style="max-width: 160px;">
                    <option value="">Tất cả trạng thái</option>
                    <option value="hoatdong">Hoạt động</option>
                    <option value="vip">Khách VIP</option>
                    <option value="khoa">Bị khóa</option>
                </select>
            </div>
            <button class="btn fw-bold text-white shadow-sm" style="background-color: #F28B00;">
                <i class="fas fa-user-plus me-2"></i> Thêm Khách Hàng
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead>
                    <tr style="border-bottom: 2px solid #F28B00;">
                        <th scope="col" class="py-3 px-3">Khách Hàng</th>
                        <th scope="col" class="py-3">Liên Hệ</th>
                        <th scope="col" class="py-3 text-center">Ngày Tham Gia</th>
                        <th scope="col" class="py-3 text-center">Số Đơn</th>
                        <th scope="col" class="py-3 text-end">Chi Tiêu</th>
                        <th scope="col" class="py-3 text-center">Trạng Thái</th>
                        <th scope="col" class="py-3 text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr class="customer-row" style="border-bottom: 1px solid #333;">
                            <td class="py-3 px-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($customer['name']) ?>&background=random&color=fff" class="rounded-circle" width="40" height="40" alt="avatar"/>
                                    <div>
                                        <div class="fw-bold text-white customer-name"><?= $customer['name'] ?></div>
                                        <div class="small text-muted"><?= $customer['id'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="text-muted small customer-email"><i class="fas fa-envelope me-1"></i> <?= $customer['email'] ?></div>
                                <div class="text-muted small customer-phone"><i class="fas fa-phone me-1"></i> <?= $customer['phone'] ?></div>
                            </td>
                            <td class="text-muted py-3 text-center"><?= $customer['joinDate'] ?></td>
                            <td class="text-white fw-bold py-3 text-center"><?= $customer['ordersCount'] ?></td>
                            <td class="text-primary fw-bold py-3 text-end"><?= $customer['totalSpent'] ?></td>
                            <td class="py-3 text-center">
                                <span class="badge rounded-pill px-3 py-2 <?= getStatusBadge($customer['status']) ?>">
                                    <?= $customer['status'] ?>
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <button onclick='viewCustomerDetail(<?= json_encode($customer) ?>)' class="btn btn-sm btn-outline-info rounded-pill px-3">
                                    <i class="fas fa-eye me-1"></i> Xem
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4 border-top border-secondary pt-4">
            <span class="text-muted small">Hiển thị 1 - 5 trong tổng số 1.204 khách hàng</span>
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

    <div id="detailModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.85); z-index: 9999; display: none; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
        <div class="card border-secondary shadow-lg" style="width: 100%; max-width: 700px; background-color: #1a1a1a; border-radius: 15px; overflow: hidden;">
            
            <div class="p-3 d-flex justify-content-between align-items-center" style="background-color: #222; border-bottom: 2px solid #F28B00;">
                <h5 class="text-white fw-bold mb-0">
                    <i class="fas fa-id-card me-2 text-warning"></i> 
                    HỒ SƠ KHÁCH HÀNG
                </h5>
                <button onclick="closeDetailModal()" class="btn-close btn-close-white"></button>
            </div>

            <div class="p-4">
                <div class="d-flex align-items-center gap-4 border-bottom border-secondary pb-4 mb-4">
                    <img id="modalAvatar" src="" alt="Avatar" class="rounded-circle border border-2 border-secondary" style="width: 100px; height: 100px; object-fit: cover;"/>
                    <div>
                        <h4 id="modalName" class="text-white fw-bold mb-1"></h4>
                        <p class="text-muted mb-2"><i class="fas fa-envelope me-2"></i><span id="modalEmail"></span></p>
                        <span id="modalStatus" class="badge rounded-pill px-3 py-1"></span>
                    </div>
                </div>

                <div class="row g-4 text-center">
                    <div class="col-4">
                        <div class="p-3 bg-dark rounded border border-secondary h-100">
                            <p class="text-muted small mb-1 fw-bold">TỔNG ĐƠN HÀNG</p>
                            <h4 id="modalOrders" class="text-white fw-bold mb-0"></h4>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-dark rounded border border-secondary h-100">
                            <p class="text-muted small mb-1 fw-bold">TỔNG CHI TIÊU</p>
                            <h4 id="modalSpent" class="text-primary fw-bold mb-0"></h4>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-dark rounded border border-secondary h-100">
                            <p class="text-muted small mb-1 fw-bold">NGÀY THAM GIA</p>
                            <h5 id="modalDate" class="text-white fw-bold mb-0 mt-2"></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-dark d-flex justify-content-between border-top border-secondary">
                <div id="lockBtnContainer">
                    </div>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-info fw-bold rounded-pill"><i class="fas fa-history me-2"></i>Lịch Sử Mua</button>
                    <button onclick="closeDetailModal()" class="btn fw-bold text-white rounded-pill px-4" style="background-color: #333;">Đóng</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Hàm lấy class màu cho trạng thái trong Popup
    function getJsStatusBadge(status) {
        switch(status) {
            case 'Hoạt động': return 'bg-success';
            case 'Bị khóa': return 'bg-danger';
            case 'VIP': return 'bg-warning text-dark fw-bold';
            default: return 'bg-secondary';
        }
    }

    // Mở Popup và đổ dữ liệu
    function viewCustomerDetail(customer) {
        // Đổ dữ liệu text
        document.getElementById('modalAvatar').src = `https://ui-avatars.com/api/?name=${encodeURIComponent(customer.name)}&background=F28B00&color=fff&size=100`;
        document.getElementById('modalName').innerText = customer.name;
        document.getElementById('modalEmail').innerText = customer.email;
        
        // Đổ dữ liệu trạng thái
        let badgeClass = getJsStatusBadge(customer.status);
        let statusEl = document.getElementById('modalStatus');
        statusEl.className = `badge rounded-pill px-3 py-1 ${badgeClass}`;
        statusEl.innerText = customer.status;

        // Đổ thống kê
        document.getElementById('modalOrders').innerText = customer.ordersCount;
        document.getElementById('modalSpent').innerText = customer.totalSpent;
        document.getElementById('modalDate').innerText = customer.joinDate;

        // Đổi giao diện nút Khóa / Mở khóa dựa vào trạng thái
        let lockBtnContainer = document.getElementById('lockBtnContainer');
        if (customer.status === 'Bị khóa') {
            lockBtnContainer.innerHTML = `<button class="btn btn-outline-success fw-bold rounded-pill"><i class="fas fa-unlock me-2"></i>Mở Khóa Tài Khoản</button>`;
        } else {
            lockBtnContainer.innerHTML = `<button class="btn btn-outline-danger fw-bold rounded-pill"><i class="fas fa-ban me-2"></i>Khóa Tài Khoản</button>`;
        }

        // Hiện Popup
        document.getElementById('detailModal').style.display = 'flex';
    }

    // Đóng Popup
    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }

    // Lọc khách hàng trực tiếp bằng JS
    function filterCustomers() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let rows = document.getElementsByClassName('customer-row');
        
        for (let i = 0; i < rows.length; i++) {
            let name = rows[i].querySelector('.customer-name').innerText.toLowerCase();
            let email = rows[i].querySelector('.customer-email').innerText.toLowerCase();
            let phone = rows[i].querySelector('.customer-phone').innerText.toLowerCase();
            
            if (name.includes(input) || email.includes(input) || phone.includes(input)) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
</script>

<?php
// Trả nội dung vào Layout chung của Admin
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>