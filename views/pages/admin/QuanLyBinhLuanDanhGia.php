<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start();

$reviews = $danhSachBinhLuan ?? [];
?>
<div class="container-fluid p-4" style="background-color:#111;min-height:100vh;color:#fff;">

    <h3 class="text-white fw-bold mb-4">
        <i class="fas fa-comments text-warning me-2"></i>
        Quản Lý Bình Luận & Đánh Giá
    </h3>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 rounded p-4" style="background-color:#1a1a1a;">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div class="input-group" style="max-width:400px;">
                <span class="input-group-text bg-dark border-secondary text-muted">
                    <i class="fas fa-search"></i>
                </span>
                <input
                    type="text"
                    id="searchInput"
                    class="form-control bg-dark border-secondary text-white"
                    placeholder="Tìm khách hàng hoặc sản phẩm..."
                    onkeyup="filterTable()">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr style="border-bottom:2px solid #F28B00;">
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th class="text-center">Đánh giá</th>
                        <th>Nội dung</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach($reviews as $bl): ?>
    <tr>
        <td><?= $bl['id_binh_luan'] ?></td>
        <td><div class="fw-bold"><?= htmlspecialchars($bl['ho_ten']) ?></div></td>
        <td style="max-width: 150px;">
            <div class="text-truncate" title="<?= htmlspecialchars($bl['ten_san_pham']) ?>">
                <?= htmlspecialchars($bl['ten_san_pham']) ?>
            </div>
        </td>
        <td class="text-center"><span class="text-warning fs-6"><?= str_repeat('★',$bl['so_sao']) ?></span></td>
        
        <td style="max-width: 200px;">
            <div class="d-flex align-items-center">
                <div class="text-truncate me-2" style="max-width: 160px;" title="<?= htmlspecialchars($bl['noi_dung']) ?>">
                    <?= htmlspecialchars($bl['noi_dung']) ?>
                </div>
                <button type="button" class="btn btn-sm text-info p-0" 
                        onclick="showReviewModal(<?= htmlspecialchars(json_encode($bl['noi_dung'])) ?>)">
                    <i class="fas fa-search-plus"></i>
                </button>
            </div>
        </td>
        
        <td class="text-center">
            <?php if($bl['trang_thai']==1): ?>
                <span class="badge bg-success">Đã duyệt</span>
            <?php else: ?>
                <span class="badge bg-secondary">Chờ duyệt</span>
            <?php endif; ?>
        </td>
        <td class="text-center">
            <?php if($bl['trang_thai']==1): ?>
                <a href="index.php?act=CapNhatTrangThaiBinhLuan&id=<?= $bl['id_binh_luan'] ?>&trang_thai=0" class="btn btn-sm btn-outline-warning">Ẩn</a>
            <?php else: ?>
                <a href="index.php?act=CapNhatTrangThaiBinhLuan&id=<?= $bl['id_binh_luan'] ?>&trang_thai=1" class="btn btn-sm btn-outline-success">Duyệt</a>
            <?php endif; ?>
            <a href="index.php?act=XoaBinhLuan&id=<?= $bl['id_binh_luan'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa đánh giá này?')">
                <i class="fas fa-trash"></i>
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
            </table>
        </div>

    </div>
</div>
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white border-secondary">
      <div class="modal-header border-secondary">
        <h5 class="modal-title text-warning"><i class="fas fa-comment-dots me-2"></i>Nội dung chi tiết</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="font-size: 1.1rem; line-height: 1.6;">
        <p id="modalContentBody" style="white-space: pre-wrap;"></p>
      </div>
      <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
<script>
function showReviewModal(content) {
    document.getElementById('modalContentBody').innerText = content;
    var myModal = new bootstrap.Modal(document.getElementById('reviewModal'));
    myModal.show();
}
</script>
<?php
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>