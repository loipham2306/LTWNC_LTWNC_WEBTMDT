<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start(); 
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$startItem = $startItem ?? 0;
$endItem = $endItem ?? 0;
$totalCustomers = $totalCustomers ?? 0;
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
                <select id="statusFilter" onchange="filterCustomers()" class="form-select bg-dark border-secondary text-white" style="max-width: 160px;">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1">Hoạt động</option> 
                    <option value="0">Bị khóa</option>
                </select>
                <select id="hangFilter" onchange="filterCustomers()" class="form-select bg-dark border-secondary text-white">
                    <option value="all">Tất cả hạng</option>
                    <option value="Silver">Silver</option>
                    <option value="Diamond">Diamond</option>
                    <option value="Gold">Gold</option>
                </select>
                
            </div>
            <button class="btn fw-bold text-white shadow-sm" style="background-color: #F28B00;">
                <i class="fas fa-user-plus me-2"></i> Thêm Khách Hàng
            </button>
        </div>

        <div class="table-responsive">
            <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
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
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $customer): ?>
                           <tr
                                class="customer-row"
                                data-status="<?= $customer['trang_thai'] ?>"
                                data-hang="<?= htmlspecialchars($customer['hang_thanh_vien'] ?? 'Silver') ?>">
                                <td class="py-3 px-3">
                                    <div class="fw-bold text-white customer-name">
                                        <?= htmlspecialchars($customer['ho_ten_dem'] . ' ' . $customer['ten']) ?>
                                    </div>
                                </td>
                                
                                <td class="py-3">
                                    <div class="text-muted small customer-phone"><?= htmlspecialchars($customer['so_dien_thoai']) ?></div>
                                </td>

                                <td class="py-3 text-center text-white">
                                    <?= isset($customer['ngay_tao']) ? date('d/m/Y', strtotime($customer['ngay_tao'])) : 'N/A' ?>
                                </td>

                                <td class="py-3 text-center text-white">
                                    <?= number_format($customer['so_don'] ?? 0) ?>
                                </td>

                                <td class="py-3 text-end text-warning">
                                    <?= number_format($customer['tong_chi_tieu'] ?? 0, 0, ',', '.') ?>đ
                                </td>
                               
                                <td class="py-3 text-center">
                                    <span class="badge <?= ($customer['trang_thai'] == 1) ? 'bg-success' : 'bg-danger' ?>">
                                        <?= ($customer['trang_thai'] == 1) ? 'Hoạt động' : 'Bị khóa' ?>
                                    </span>
                                </td>
                                
                                <td class="py-3 text-center">
                                    <button onclick='viewCustomerDetail(<?= json_encode($customer) ?>)' class="btn btn-sm btn-outline-info">
                                        Xem
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">Không có dữ liệu khách hàng.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-4 border-top border-secondary pt-4">
            <span class="text-muted small">
                Hiển thị <?= $startItem ?> - <?= $endItem ?> trong tổng số <?= $totalCustomers ?> khách hàng
            </span>
            <nav>
                <ul class="pagination pagination-sm mb-0 d-flex flex-row gap-2">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link bg-dark border-secondary text-white rounded px-3 py-2" 
                        href="?act=QuanLyKhachHang&page=<?= $page - 1 ?>">Trước</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link <?= ($i == $page) ? 'border-0 text-white' : 'bg-dark border-secondary text-white' ?> rounded px-3 py-2" 
                            style="<?= ($i == $page) ? 'background-color: #F28B00;' : '' ?>"
                            href="?act=QuanLyKhachHang&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link bg-dark border-secondary text-white rounded px-3 py-2" 
                        href="?act=QuanLyKhachHang&page=<?= $page + 1 ?>">Sau</a>
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

<script src="/LTWNC_LTWNC_WEBTMDT/views/js/QuanLyKhachHang.js"></script>

<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>