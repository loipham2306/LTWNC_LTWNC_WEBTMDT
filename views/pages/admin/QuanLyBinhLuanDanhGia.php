<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

$reviews = $danhSachBinhLuan ?? [];
?>

<style>
    /* Chống tràn và làm gọn thanh cuộn cho bảng trên Mobile */
    .table-responsive::-webkit-scrollbar { height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #555; border-radius: 4px; }
    
    .table-dark th, .table-dark td { vertical-align: middle; }
    
    /* Hiệu ứng hover cho nút Action */
    .btn-action { transition: 0.3s; }
    .btn-action:hover { transform: scale(1.1); }
</style>

<!-- Xóa p-4 ở đây vì AdminLayout đã có sẵn khoảng cách -->
<div class="container-fluid px-0">

    <h4 class="text-white fw-bold mb-4">
        <i class="fas fa-comments text-warning me-2"></i>
        Quản Lý Bình Luận & Đánh Giá
    </h4>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 rounded p-3 p-md-4 shadow-sm" style="background-color:#1a1a1a;">

        <!-- HEADER CỦA BẢNG: Ô TÌM KIẾM -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="input-group w-100" style="max-width: 400px;">
                <span class="input-group-text bg-dark border-secondary text-muted">
                    <i class="fas fa-search"></i>
                </span>
                <input
                    type="text"
                    id="searchInput"
                    class="form-control bg-dark border-secondary text-white"
                    placeholder="Tìm khách hàng, sản phẩm, nội dung..."
                    onkeyup="filterTable()">
            </div>
        </div>
        
        <!-- BẢNG DANH SÁCH -->
        <div class="table-responsive border border-secondary rounded">
            <table class="table table-dark table-hover align-middle mb-0 text-center" style="min-width: 800px;">
                <thead style="background-color: #222;">
                    <tr style="border-bottom:2px solid #F28B00;">
                        <th scope="col" class="py-3 px-3">ID</th>
                        <th scope="col" class="py-3 text-start">Khách hàng</th>
                        <th scope="col" class="py-3 text-start">Sản phẩm</th>
                        <th scope="col" class="py-3 text-center">Đánh giá</th>
                        <th scope="col" class="py-3 text-start">Nội dung</th>
                        <th scope="col" class="py-3 text-center">Trạng thái</th>
                        <th scope="col" class="py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="reviewTableBody">
                    <?php if(!empty($reviews)): ?>
                        <?php foreach($reviews as $bl): ?>
                        <tr class="review-row" style="border-bottom: 1px solid #333;">
                            <td class="fw-bold text-orange review-id">#<?= $bl['id_binh_luan'] ?></td>
                            <td class="text-start">
                                <div class="fw-bold text-white review-customer"><?= htmlspecialchars($bl['ho_ten']) ?></div>
                            </td>
                            <td class="text-start" style="max-width: 150px;">
                                <div class="text-truncate text-muted review-product" title="<?= htmlspecialchars($bl['ten_san_pham']) ?>">
                                    <?= htmlspecialchars($bl['ten_san_pham']) ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="text-warning fs-6"><?= str_repeat('★', $bl['so_sao']) ?></span>
                            </td>
                            
                            <td class="text-start" style="max-width: 200px;">
                                <div class="d-flex align-items-center">
                                    <div class="text-truncate me-2 review-content" style="max-width: 160px;" title="<?= htmlspecialchars($bl['noi_dung']) ?>">
                                        <?= htmlspecialchars($bl['noi_dung']) ?>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-info rounded-circle p-1 d-flex align-items-center justify-content-center btn-action" 
                                            style="width: 25px; height: 25px;"
                                            onclick="showReviewModal(<?= htmlspecialchars(json_encode($bl['noi_dung'])) ?>)">
                                        <i class="fas fa-eye" style="font-size: 10px;"></i>
                                    </button>
                                </div>
                            </td>
                            
                            <td class="text-center">
                                <?php if($bl['trang_thai'] == 1): ?>
                                    <span class="badge bg-success rounded-pill px-3 py-2">Đã duyệt</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">Chờ duyệt</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <?php if($bl['trang_thai'] == 1): ?>
                                        <a href="index.php?act=CapNhatTrangThaiBinhLuan&id=<?= $bl['id_binh_luan'] ?>&trang_thai=0" class="btn btn-sm btn-outline-warning rounded-pill px-3 btn-action">Ẩn</a>
                                    <?php else: ?>
                                        <a href="index.php?act=CapNhatTrangThaiBinhLuan&id=<?= $bl['id_binh_luan'] ?>&trang_thai=1" class="btn btn-sm btn-outline-success rounded-pill px-3 btn-action">Duyệt</a>
                                    <?php endif; ?>
                                    <a href="index.php?act=XoaBinhLuan&id=<?= $bl['id_binh_luan'] ?>" class="btn btn-sm btn-danger rounded-pill px-3 btn-action" onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này vĩnh viễn?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-comment-slash fa-3x mb-3"></i>
                                <br>Chưa có bình luận hoặc đánh giá nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- ==========================================
     MODAL XEM CHI TIẾT NỘI DUNG (POPUP)
     ========================================== -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white border-secondary shadow-lg">
      <div class="modal-header" style="border-bottom: 2px solid #F28B00; background-color: #222;">
        <h5 class="modal-title fw-bold" style="color: #F28B00;"><i class="fas fa-comment-dots me-2"></i>Nội dung chi tiết</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" style="font-size: 1.05rem; line-height: 1.6; background-color: #1a1a1a;">
        <p id="modalContentBody" style="white-space: pre-wrap; margin: 0;"></p>
      </div>
      <div class="modal-footer border-secondary" style="background-color: #222;">
        <button type="button" class="btn btn-outline-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

<script>
// Hàm hiển thị Popup Nội dung
function showReviewModal(content) {
    document.getElementById('modalContentBody').innerText = content;
    var myModal = new bootstrap.Modal(document.getElementById('reviewModal'));
    myModal.show();
}

// BỔ SUNG: Hàm tìm kiếm (Filtering)
function filterTable() {
    // Lấy từ khóa người dùng gõ, chuyển thành chữ thường
    let input = document.getElementById("searchInput").value.toLowerCase();
    
    // Lấy tất cả các dòng trong bảng
    let rows = document.getElementsByClassName("review-row");
    
    for (let i = 0; i < rows.length; i++) {
        // Lấy dữ liệu từ các cột cần tìm kiếm
        let customer = rows[i].querySelector(".review-customer").innerText.toLowerCase();
        let product = rows[i].querySelector(".review-product").innerText.toLowerCase();
        let content = rows[i].querySelector(".review-content").innerText.toLowerCase();
        
        // Nếu tên khách, tên sản phẩm, hoặc nội dung có chứa từ khóa thì hiện, ngược lại ẩn
        if (customer.includes(input) || product.includes(input) || content.includes(input)) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}
</script>

<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>