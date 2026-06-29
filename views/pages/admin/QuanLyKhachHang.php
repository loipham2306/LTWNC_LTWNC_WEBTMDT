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

<style>
    /* Chống tràn và làm gọn thanh cuộn cho bảng trên Mobile */
    .table-responsive::-webkit-scrollbar { height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
    
    .table-dark th, .table-dark td { vertical-align: middle; white-space: nowrap; }
    
    /* Hiệu ứng hover cho row */
    .customer-row:hover { background-color: #222 !important; }

    /* Phân trang custom */
    .custom-pagination .page-item { margin: 0 2px; }
    .custom-pagination .page-link { background: #1a1a1a; border: 1px solid #444; color: #fff; border-radius: 8px !important; padding: 6px 12px; transition: 0.3s; }
    .custom-pagination .page-item.active .page-link { background: #F28B00; border-color: #F28B00; color: #000; font-weight: bold; }
    .custom-pagination .page-link:hover:not(.disabled) { background: #333; color: #fff; }
    .custom-pagination .page-item.disabled .page-link { opacity: 0.5; cursor: not-allowed; }
</style>

<div class="container-fluid px-0">
    
    <h4 class="text-white fw-bold mb-4">
        <i class="fas fa-users text-warning me-2"></i>Quản Lý Khách Hàng
    </h4>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show fw-bold border-0 shadow-sm" style="background-color: #064e3b; color: #34d399;">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 rounded p-3 p-md-4 shadow-sm" style="background-color: #1a1a1a;">
        
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-12 col-xl-10">
                <div class="d-flex flex-column flex-md-row gap-2">
                    <div class="input-group flex-grow-1">
                        <span class="input-group-text bg-dark border-secondary text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchInput" onkeyup="filterCustomers()" class="form-control bg-dark border-secondary text-white" placeholder="Tìm theo tên, email, SĐT...">
                    </div>
                    
                    <select id="statusFilter" onchange="filterCustomers()" class="form-select bg-dark border-secondary text-white" style="min-width: 160px;">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1">Hoạt động</option> 
                        <option value="0">Bị khóa</option>
                    </select>

                    <select id="hangFilter" onchange="filterCustomers()" class="form-select bg-dark border-secondary text-white" style="min-width: 160px;">
                        <option value="all">Tất cả hạng</option>
                        <option value="Silver">Silver</option>
                        <option value="Diamond">Diamond</option>
                        <option value="Gold">Gold</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-xl-2 text-start text-xl-end">
                <button class="btn fw-bold text-white w-100 w-xl-auto shadow-sm" style="background-color: #F28B00; transition: 0.3s;" onmouseover="this.style.backgroundColor='#d67a00'" onmouseout="this.style.backgroundColor='#F28B00'">
                    <i class="fas fa-user-plus me-2"></i> Thêm Mới
                </button>
            </div>
        </div>

        <div class="table-responsive border border-secondary rounded">
            <table class="table table-dark table-hover align-middle mb-0 text-center" style="min-width: 900px;">
                <thead style="background-color: #222;">
                    <tr style="border-bottom: 2px solid #F28B00;">
                        <th scope="col" class="py-3 px-3 text-start">Khách Hàng</th>
                        <th scope="col" class="py-3 text-start">Liên Hệ</th>
                        <th scope="col" class="py-3">Ngày Tham Gia</th>
                        <th scope="col" class="py-3">Số Đơn</th>
                        <th scope="col" class="py-3 text-end">Chi Tiêu</th>
                        <th scope="col" class="py-3">Trạng Thái</th>
                        <th scope="col" class="py-3">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $customer): ?>
                            <tr class="customer-row" style="border-bottom: 1px solid #333;"
                                data-status="<?= $customer['trang_thai'] ?>"
                                data-hang="<?= htmlspecialchars($customer['hang_thanh_vien'] ?? 'Silver') ?>">
                                
                                <td class="py-3 px-3 text-start">
                                    <div class="fw-bold text-white customer-name">
                                        <?= htmlspecialchars($customer['ho_ten_dem'] . ' ' . $customer['ten']) ?>
                                    </div>
                                    <?php 
                                        $hang = htmlspecialchars($customer['hang_thanh_vien'] ?? 'Silver');
                                        $badgeColor = ($hang == 'Diamond') ? 'bg-info text-dark' : (($hang == 'Gold') ? 'bg-warning text-dark' : 'bg-secondary');
                                    ?>
                                    <span class="badge <?= $badgeColor ?> mt-1"><?= $hang ?></span>
                                </td>
                                
                                <td class="py-3 text-start">
                                    <div class="text-white small"><i class="fas fa-envelope me-1 text-muted"></i> <?= htmlspecialchars($customer['email'] ?? 'Chưa cập nhật') ?></div>
                                    <div class="text-white small customer-phone"><i class="fas fa-phone-alt me-1 text-muted"></i> <?= htmlspecialchars($customer['so_dien_thoai']) ?></div>
                                </td>

                                <td class="py-3 text-muted">
                                    <?= isset($customer['ngay_tao']) ? date('d/m/Y', strtotime($customer['ngay_tao'])) : 'N/A' ?>
                                </td>

                                <td class="py-3 fw-bold text-white">
                                    <?= number_format($customer['so_don'] ?? 0) ?>
                                </td>

                                <td class="py-3 text-end text-orange fw-bold">
                                    <?= number_format($customer['tong_chi_tieu'] ?? 0, 0, ',', '.') ?>đ
                                </td>
                               
                                <td class="py-3">
                                    <?php if ($customer['trang_thai'] == 1): ?>
                                        <span class="badge bg-success rounded-pill px-3 py-2">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger rounded-pill px-3 py-2">Bị khóa</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="py-3 text-center">
                                    <button onclick='viewCustomerDetail(<?= htmlspecialchars(json_encode($customer, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE), ENT_QUOTES, "UTF-8") ?>)' 
                                            class="btn btn-sm btn-outline-info rounded-pill px-3 shadow-sm" style="transition: 0.3s;" onmouseover="this.classList.add('text-white')" onmouseout="this.classList.remove('text-white')">
                                        <i class="fas fa-eye me-1"></i> Xem
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted"><i class="fas fa-user-slash fa-3x mb-3"></i><br>Không có dữ liệu khách hàng.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 border-top border-secondary pt-4 gap-3">
            <span class="text-muted small fw-bold">
                Hiển thị <span class="text-white"><?= $startItem ?> - <?= $endItem ?></span> trong tổng <span class="text-orange"><?= $totalCustomers ?></span> khách hàng
            </span>
            <nav>
                <ul class="pagination custom-pagination mb-0 d-flex flex-row flex-wrap justify-content-center">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?act=QuanLyKhachHang&page=<?= $page - 1 ?>">Trước</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?act=QuanLyKhachHang&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?act=QuanLyKhachHang&page=<?= $page + 1 ?>">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>

    <div id="detailModal" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.85); z-index: 9999; display: none; align-items: center; justify-content: center; backdrop-filter: blur(5px); padding: 15px;">
        <div class="card border-secondary shadow-lg d-flex flex-column" style="width: 100%; max-width: 700px; max-height: 90vh; background-color: #1a1a1a; border-radius: 12px; overflow: hidden;">
            
            <div class="p-3 d-flex justify-content-between align-items-center flex-shrink-0" style="background-color: #222; border-bottom: 2px solid #F28B00;">
                <h6 class="text-white fw-bold mb-0">
                    <i class="fas fa-id-card me-2 text-warning"></i>HỒ SƠ KHÁCH HÀNG
                </h6>
                <button onclick="closeDetailModal()" class="btn-close btn-close-white"></button>
            </div>

            <div class="p-3 p-md-4 flex-grow-1" style="overflow-y: auto;">
                
                <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start gap-3 border-bottom border-secondary pb-4 mb-4 text-center text-sm-start">
                    <img id="modalAvatar" src="" alt="Avatar" class="rounded-circle border border-2 border-warning" style="width: 100px; height: 100px; object-fit: cover; background-color: #222;"/>
                    <div class="mt-2 mt-sm-0">
                        <h4 id="modalName" class="text-white fw-bold mb-1"></h4>
                        <p class="text-muted mb-2"><i class="fas fa-envelope me-2"></i><span id="modalEmail"></span></p>
                        <span id="modalStatus" class="badge rounded-pill px-3 py-2 mt-1 shadow-sm"></span>
                    </div>
                </div>

                <div class="row g-3 text-center">
                    <div class="col-12 col-sm-4">
                        <div class="p-3 bg-dark rounded border border-secondary h-100 shadow-sm d-flex flex-row flex-sm-column justify-content-between align-items-center">
                            <p class="text-muted small mb-0 mb-sm-2 fw-bold"><i class="fas fa-box text-white-50 me-2 d-sm-none"></i>TỔNG ĐƠN</p>
                            <h4 id="modalOrders" class="text-white fw-bold mb-0"></h4>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="p-3 bg-dark rounded border border-secondary h-100 shadow-sm d-flex flex-row flex-sm-column justify-content-between align-items-center">
                            <p class="text-muted small mb-0 mb-sm-2 fw-bold"><i class="fas fa-wallet text-white-50 me-2 d-sm-none"></i>CHI TIÊU</p>
                            <h4 id="modalSpent" class="text-warning fw-bold mb-0"></h4>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="p-3 bg-dark rounded border border-secondary h-100 shadow-sm d-flex flex-row flex-sm-column justify-content-between align-items-center">
                            <p class="text-muted small mb-0 mb-sm-2 fw-bold"><i class="fas fa-calendar-alt text-white-50 me-2 d-sm-none"></i>THAM GIA</p>
                            <h5 id="modalDate" class="text-white fw-bold mb-0 mt-0 mt-sm-2"></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-dark d-flex flex-column flex-sm-row justify-content-between gap-2 border-top border-secondary flex-shrink-0">
                <div id="lockBtnContainer" class="w-100 w-sm-auto mb-2 mb-sm-0">
                    </div>
                
                <div class="d-flex flex-wrap gap-2 w-100 w-sm-auto justify-content-end">
                    <button class="btn btn-outline-info fw-bold rounded-pill px-4 flex-grow-1 flex-sm-grow-0">
                        <i class="fas fa-history me-2"></i>Lịch Sử
                    </button>
                    <button onclick="closeDetailModal()" class="btn fw-bold text-white rounded-pill px-4 flex-grow-1 flex-sm-grow-0" style="background-color: #444; transition: 0.3s;" onmouseover="this.style.backgroundColor='#555'" onmouseout="this.style.backgroundColor='#444'">
                        Đóng
                    </button>
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