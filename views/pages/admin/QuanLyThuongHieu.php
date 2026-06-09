<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Bắt đầu đóng gói mã HTML
ob_start(); 
if (!isset($brands)) {
    $brands = [];
}
$IMAGE_BASE_URL = '/LTWNC_LTWNC_WEBTMDT/assets/images/brands/';
?>
<div class="container-fluid p-4" style="background-color: #111; min-height: 100vh; color: #fff;">

    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary">
        <div>
            <h3 class="fw-bold text-white mb-1">Quản Lý Thương Hiệu</h3>
            <p class="text-muted small mb-0">Hệ thống danh mục đối tác chiến lược của Sàn Hiệu</p>
        </div>
        <button class="btn btn-primary px-4 py-2 rounded-pill fw-bold" 
                type="button"
                data-bs-toggle="modal" 
                 data-bs-target="#brandModal"
                onclick="openAddModal()"> 
            <i class="fas fa-plus me-2"></i>Thêm Thương Hiệu
        </button>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success fw-bold">✅ <?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger fw-bold">❌ <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card border-0 mb-4 p-3" style="background-color: #1a1a1a;">
        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-muted">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" onkeyup="filterBrands()" class="form-control bg-dark border-secondary text-white" placeholder="Tìm tên thương hiệu...">
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 rounded overflow-hidden" style="background-color: #1a1a1a;">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0 text-center" id="brandsTable">
                <thead>
                    <tr style="border-bottom: 2px solid #F28B00;">
                        <th scope="col" class="py-3 text-start ps-4">Thương Hiệu</th>
                        <th scope="col" class="py-3">Mô Tả</th>
                        <th scope="col" class="py-3">Số Sản Phẩm</th>
                        <th scope="col" class="py-3">Trạng Thái</th>
                        <th scope="col" class="py-3 pe-4">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($brands) > 0): ?>
                        <?php foreach ($brands as $brand): ?>
                            <tr class="brand-row" style="border-bottom: 1px solid #2a2a2a;">
                                
                                <td class="py-3 text-start ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-white rounded p-1 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; overflow: hidden;">
                                            <?php if (!empty($brand['hinh_anh_logo'])): ?>
                                                <img src="<?= $IMAGE_BASE_URL . $brand['hinh_anh_logo'] ?>" alt="<?= $brand['ten_thuong_hieu'] ?>" style="width: 100%; height: 100%; object-fit: contain;" onerror="this.style.display='none'">
                                            <?php else: ?>
                                                <span class="text-dark fw-bold" style="font-size: 18px;"><?= mb_substr($brand['ten_thuong_hieu'], 0, 1) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="fw-bold text-white brand-name"><?= htmlspecialchars($brand['ten_thuong_hieu']) ?></span>
                                    </div>
                                </td>

                                <td class="py-3 text-muted text-start" style="max-width: 300px;">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <span style="font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; color: #f8f9fa;">
                                            <?= !empty($brand['mo_ta']) ? htmlspecialchars($brand['mo_ta']) : '<em class="text-secondary">Chưa có mô tả</em>' ?>
                                        </span>
                                        <?php if (!empty($brand['mo_ta'])): ?>
                                            <button type="button" 
                                                onclick='viewDesc(
                                                    <?= json_encode(htmlspecialchars($brand['ten_thuong_hieu'], ENT_QUOTES)) ?>, 
                                                    <?= json_encode(htmlspecialchars($brand['mo_ta'], ENT_QUOTES)) ?>)'
                                                class="btn btn-link text-warning p-0 border-0 ms-1">
                                                <i class="fas fa-search-plus"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="py-3 text-primary fw-bold">
                                    <?= isset($brand['count']) ? $brand['count'] : 0 ?> sản phẩm
                                </td>

                                <td class="py-3">
                                    <?php if ($brand['trang_thai'] == 1): ?>
                                        <span class="badge rounded-pill px-3 py-2 bg-success">Đang hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill px-3 py-2 bg-secondary text-dark">Tạm dừng</span>
                                    <?php endif; ?>
                                </td>

                                <td class="py-3 pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-warning rounded-circle" style="width: 35px; height: 35px;" onclick="openEditModal(<?= htmlspecialchars(json_encode($brand)) ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="index.php?act=xoaTH" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thương hiệu này?');">
                                            <input type="hidden" name="id_thuong_hieu" value="delete">
                                            <input type="hidden" name="id_thuong_hieu" value="<?= $brand['id_thuong_hieu'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" style="width: 35px; height: 35px;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-4 text-muted">Không tìm thấy thương hiệu nào hoặc danh sách trống.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="brandModal" tabindex="-1" aria-hidden="true" style="background-color: rgba(0,0,0,0.7); backdrop-filter: blur(4px);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-white border-0" style="background-color: #1a1a1a;">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold text-primary" id="modalTitle">Thêm Thương Hiệu Mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
           <form id="brandForm" action="index.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="act" id="formAction" value="themTH">
                <input type="hidden" name="id_thuong_hieu" id="brandId">
                <!-- giữ logo cũ khi edit -->
                <input type="hidden" name="old_image" id="oldLogo">
                <div class="modal-body">
                    <!-- TÊN THƯƠNG HIỆU -->
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold">Tên Thương Hiệu</label>
                        <input type="text"
                            name="ten_thuong_hieu"
                            id="brandName"
                            class="form-control bg-dark border-secondary text-white py-2"
                            placeholder="Ví dụ: Nike, Adidas..."
                            required>
                    </div>
                    <!-- LOGO -->
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold">Logo Thương Hiệu</label>

                        <div id="dropZone"
                            class="border border-secondary rounded p-4 text-center text-muted"
                            style="cursor:pointer; background:#222; border-style:dashed !important;">

                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p class="mb-0">Kéo thả ảnh hoặc nhấn để chọn file</p>

                        </div>

                        <input type="file"
                            name="hinh_anh_logo_file"
                            id="fileInput"
                            class="d-none"
                            accept="image/*">

                        <!-- preview -->
                        <div class="mt-2 p-2 bg-white rounded d-none"
                            id="logoPreviewContainer"
                            style="max-width:120px;">
                            <img id="logoPreview"
                                src=""
                                style="width:100%; height:60px; object-fit:contain;">
                        </div>
                    </div>

                    <!-- MÔ TẢ -->
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold">Mô Tả Thương Hiệu</label>
                        <textarea name="mo_ta"
                                id="brandDesc"
                                class="form-control bg-dark border-secondary text-white py-2"
                                rows="3"></textarea>
                    </div>

                    <!-- TRẠNG THÁI -->
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold">Trạng Thái</label>
                        <select name="trang_thai"
                                id="brandStatus"
                                class="form-select bg-dark border-secondary text-white py-2">
                            <option value="1">Đang hoạt động</option>
                            <option value="0">Tạm dừng</option>
                        </select>
                    </div>

                    <!-- META EDIT -->
                    <div class="row g-2 pt-2 border-top border-secondary mt-2 d-none"
                        id="editMeta">

                        <div class="col-6">
                            <small class="text-secondary d-block">ID:</small>
                            <small class="text-warning fw-bold" id="displayBrandId"></small>
                        </div>

                        <div class="col-6">
                            <small class="text-secondary d-block">Ngày tạo:</small>
                            <small class="text-white-50" id="displayCreatedAt"></small>
                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-secondary">
                    <button type="button"
                            class="btn btn-outline-secondary px-4 rounded-pill"
                            data-bs-dismiss="modal">
                        Hủy
                    </button>

                    <button type="submit"
                            class="btn btn-primary px-4 rounded-pill fw-bold">
                        Lưu Thông Tin
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="descModal" tabindex="-1" aria-hidden="true" style="background-color: rgba(0,0,0,0.6); backdrop-filter: blur(3px); z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-white border-0 shadow-lg" style="background-color: #222; border-radius: 15px;">
            <div class="modal-header border-secondary" style="border-bottom: 1px solid #333;">
                <h5 class="modal-title fw-bold text-warning">
                    <i class="fas fa-info-circle me-2"></i>Mô Tả Hãng: <span id="descTitle"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4" id="descContent" style="font-size: 15px; line-height: 1.6; color: #ddd; white-space: pre-line;">
            </div>
            <div class="modal-footer border-0 justify-content-end pt-0">
                <button type="button" class="btn btn-secondary px-4 py-1 rounded-pill fw-bold" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script src="/LTWNC_LTWNC_WEBTMDT/views/js/BrandManagement.js"></script>

<?php
// Trả nội dung vào Layout
$PAGE_CONTENT = ob_get_clean();
include 'AdminLayout.php';
?>